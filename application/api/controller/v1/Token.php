<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 10:57
 */

namespace app\api\controller\v1;

use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;

class Token
{
    /**
     * 获取令牌
     * @param string $code
     * @return array
     */
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        $wx = new UserToken($code);
        $token = $wx->get();
        return [
            'token' => $token
        ];
    }

    public function verifyToken($token = '')
    {
        if (!$token)
            throw new ParameterException([
                'token不允许为空'
            ]);
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }

    /**
     * 第三方应用获取令牌
     * @url /app_token?
     * @POST ac=:ac se=:secret
     */
    public function getAppToken($ac = '', $se = '')
    {
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        return [
            'token' => $token
        ];
    }
}