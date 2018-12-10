<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/5
 * Time: 11:34
 */

namespace app\api\validate;

class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id参数必须是正整数'
    ];
}