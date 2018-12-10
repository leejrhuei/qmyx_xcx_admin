<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 14:17
 */

namespace app\admin\controller;

use app\admin\validate\Paginate;
use think\Controller;
use app\admin\model\Category as CategoryModel;
use app\admin\validate\Category as CategoryValidate;

class Category extends Controller
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
        $data = CategoryModel::getList($params);
        return $data;
    }

    public function set($id = 0)
    {
        if (!$id) {
            $title = '新增';
        } else {
            $title = '编辑';
            $data = CategoryModel::getInfoById($id);
            $this->assign('data', $data);
        }
        $this->assign('title', $title);
        return $this->fetch('set');
    }

    public function doSet()
    {
        $params = (new CategoryValidate())->goCheck();
        if (array_key_exists('code', $params))
            return $params;
        if (!array_key_exists('id', $params)) {
            // 新增
            $ret = CategoryModel::add($params);
        } else {
            // 编辑
            $ret = CategoryModel::modify($params);
        }
        return $ret;
    }

    public function del()
    {
        $ids = input('post.id/s');
        $ret = CategoryModel::del($ids);
        return $ret;
    }
}