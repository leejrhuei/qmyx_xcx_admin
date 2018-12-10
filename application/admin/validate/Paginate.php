<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 15:52
 */

namespace app\admin\validate;

class Paginate extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'limit' => 'isPositiveInteger'
    ];

    protected $message = [
        'page.isPositiveInteger' => '页数必须是正整数',
        'limit.isPositiveInteger' => '条数必须是正整数'
    ];
}