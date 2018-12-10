<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/9
 * Time: 16:05
 */

namespace app\lib\exception;

class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}