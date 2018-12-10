<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/9
 * Time: 14:54
 */

namespace app\lib\exception;

class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}