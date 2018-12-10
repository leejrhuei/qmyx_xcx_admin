<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/14
 * Time: 16:49
 */

namespace app\api\validate;

class AppTokenGet extends BaseValidate
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty'
    ];
}