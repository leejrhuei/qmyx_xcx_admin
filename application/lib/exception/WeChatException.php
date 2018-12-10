<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 11:39
 */

namespace app\lib\exception;

class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = 'wechat unknown error';
    public $errorCode = 999;
}