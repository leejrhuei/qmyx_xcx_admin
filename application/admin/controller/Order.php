<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/19
 * Time: 9:48
 */

namespace app\admin\controller;

use app\admin\validate\Paginate;
use think\Controller;
use app\admin\model\Order as OrderModel;

class Order extends Controller
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
        $data = OrderModel::getList($params);
        return $data;
    }
}