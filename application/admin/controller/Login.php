<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 9:54
 */

namespace app\admin\controller;

use app\admin\model\Admin;
use think\Controller;
use app\admin\validate\Login as LoginValidate;

class Login extends Controller
{
    public function login()
    {
        return $this->fetch('login');
    }

    public function doLogin()
    {
        $params = (new LoginValidate())->goCheck();
        if (array_key_exists('code', $params))
            return $params;
        $adminModel = new Admin();
        $ret = $adminModel::check($params);
        return $ret;
    }
}