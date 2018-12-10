<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 14:38
 */

namespace app\admin\validate;

class Category extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'description' => 'require',
        'pic' => 'require'
    ];

    protected $message = [
        'name.require' => '名称必填',
        'description.require' => '描述必填',
        'pic.require' => '图片必传'
    ];
}