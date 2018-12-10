<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/5
 * Time: 15:20
 */

namespace app\lib\exception;

class MissException extends BaseException
{
    public $code = 404;
    public $msg = 'global: your required resource are not found';
    public $errorCode = 10001;
}