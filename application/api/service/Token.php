<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 15:16
 */

namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    /**
     * 生成令牌的key
     * @return string
     */
    public static function generateToken()
    {
        $randChar = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $tokenSalt = config('secure.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            if (!is_array($vars))
                $vars = json_decode($vars, true);
            if (array_key_exists($key, $vars))
                return $vars[$key];
            else
                throw new Exception('尝试获取的Token变量并不存在');
        }
    }

    /**
     * 用户和管理员都可访问
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if (!$scope)
            throw new TokenException();
        else {
            if ($scope >= ScopeEnum::User)
                return true;
            else
                throw new ForbiddenException();
        }
    }

    /**
     * 用户可访问
     * @return bool
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if (!$scope)
            throw new TokenException();
        else {
            if ($scope == ScopeEnum::User)
                return true;
            else
                throw new ForbiddenException();
        }
    }

    public static function isValidOperate($checkUid)
    {
        if (!$checkUid)
            throw new Exception('检测uid时必须传入一个被检查的uid');
        $currentOpearteUid = self::getCurrentUid();
        if ($currentOpearteUid != $checkUid)
            return false;
        return true;
    }

    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if ($exist) {
            return true;
        } else {
            return false;
        }
    }
}