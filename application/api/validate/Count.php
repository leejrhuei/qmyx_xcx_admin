<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/6
 * Time: 19:24
 */

namespace app\api\validate;

class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,15'
    ];

    protected $message = [
        'count' => 'count参数必须是正整数'
    ];
}