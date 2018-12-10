<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 11:14
 */

namespace app\api\service;

use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxLoginUrl;
    protected $wxAppID;
    protected $wxAppSecret;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    /**
     * 获取令牌
     * @return mixed
     * @throws Exception
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if (empty($wxResult))
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        else {
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail)
                $this->processLoginError($wxResult);
            else
                return $this->grantToken($wxResult);
        }
    }

    /**
     * 登陆异常
     * @param $wxResult
     * @throws WeChatException
     */
    private function processLoginError($wxResult)
    {
        throw new WeChatException([
            'errorCode' => $wxResult['errcode'],
            'msg' => $wxResult['errmsg']
        ]);
    }

    /**
     * 生成令牌
     * @param $wxResult
     * @return mixed
     */
    private function grantToken($wxResult)
    {
        // 查询数据库是否存在openid
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if (!$user)
            $uid = $this->newUser($openid);
        else
            $uid = $user->id;
        $cachedValue = $this->prepareCachedValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    /**
     * 创建新用户
     * @param $openid
     * @return mixed
     */
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    /**
     * 准备令牌value值
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    private function prepareCachedValue($wxResult, $uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    /**
     * 将令牌写入缓存
     * @param $wxResult
     * @return mixed
     */
    private function saveToCache($cacheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        $expire_in = config('setting.token_expire_in');
        $result = cache($key, $value, $expire_in);
        if (!$result)
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        return $key;
    }
}