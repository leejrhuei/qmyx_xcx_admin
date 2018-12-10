<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 15:29
 */

namespace app\lib\exception;

class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效Token';
    public $errorCode = 10001;
}