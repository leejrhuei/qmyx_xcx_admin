<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/11
 * Time: 15:14
 */

namespace app\api\validate;

class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'page' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数'
    ];
}