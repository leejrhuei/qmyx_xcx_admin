<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/19
 * Time: 11:22
 */

namespace app\admin\controller;

use app\admin\validate\Paginate;
use think\Controller;
use app\admin\model\User as UserModel;

class User extends Controller
{
    public function manage()
    {
        return $this->fetch('manage');
    }

    public function getList()
    {
        $params = (new Paginate())->goCheck();
        if (array_key_exists('code', $params))
            return $params;
        $data = UserModel::getList($params);
        return $data;
    }
}