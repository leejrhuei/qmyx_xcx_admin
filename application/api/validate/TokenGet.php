<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 10:58
 */

namespace app\api\validate;

class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => '抱歉，无code值'
    ];
}