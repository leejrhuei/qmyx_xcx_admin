<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 15:04
 */

namespace app\admin\controller;

use app\admin\model\BannerItem;
use app\admin\validate\Banner as BannerValidate;
use app\admin\validate\Paginate;
use think\Controller;

class Banner extends Controller
{
    public function manage()
    {
        return $this->fetch('manage');
    }

    public function set($id = 0)
    {
        if (!$id) {
            $title = '新增';
        } else {
            $title = '编辑';
            $data = BannerItem::getInfoById($id);
            $this->assign('data', $data);
        }
        $this->assign('title', $title);
        return $this->fetch('set');
    }

    public function doSet()
    {
        $params = (new BannerValidate())->goCheck();
        if (array_key_exists('code', $params))
            return $params;
        if (!array_key_exists('id', $params)) {
            // 新增
            $ret = BannerItem::add($params);
        } else {
            // 编辑
            $ret = BannerItem::modify($params);
        }
        return $ret;
    }

    public function getList()
    {
        $params = (new Paginate())->goCheck();
        if (array_key_exists('code', $params))
            return $params;
        $data = BannerItem::getList($params);
        return $data;
    }

    public function del()
    {
        $ids = input('post.id/s');
        $ret = BannerItem::del($ids);
        return $ret;
    }
}