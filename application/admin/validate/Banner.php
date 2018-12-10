<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 19:19
 */

namespace app\admin\validate;

class Banner extends BaseValidate
{
    protected $rule = [
        'banner_id' => 'isPositiveInteger',
        'type' => 'isPositiveInteger',
        'key_word' => 'isPositiveInteger',
        'pic' => 'require'
    ];

    protected $message = [
        'banner_id.isPositiveInteger' => '请选择正确的显示位置',
        'type.isPositiveInteger' => '请选择正确的跳转事件',
        'key_word.isPositiveInteger' => '请输入正确的事件值',
        'pic.require' => '图片必传'
    ];
}