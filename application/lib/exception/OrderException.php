<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/9
 * Time: 20:32
 */

namespace app\lib\exception;

class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单不存在，请检查ID';
    public $errorCode = 80000;
}