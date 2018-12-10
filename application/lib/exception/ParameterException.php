<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/5
 * Time: 15:09
 */

namespace app\lib\exception;

class ParameterException extends BaseException
{
    public $code = 400;
    public $errorCode = 10000;
    public $msg = 'invalid parameters';
}