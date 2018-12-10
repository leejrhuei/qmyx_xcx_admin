<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 10:16
 */

namespace app\admin\validate;

class Login extends BaseValidate
{
    protected $rule = [
        'account' => 'require',
        'password' => 'require'
    ];

    protected $message = [
        'account.require' => '账号必填',
        'password.require' => '密码必填'
    ];
}