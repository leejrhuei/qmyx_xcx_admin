<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/19
 * Time: 10:06
 */

namespace app\admin\model;

class Order extends BaseModel
{
    public static function getList($postData)
    {
        $page = ($postData['page'] - 1 >= 0) ? ($postData['page'] - 1) : 0;
        $limit = $postData['limit'];
        $where = [];
        if (isset($postData['map']) && $postData['map'])
            $where['order_no'] = ['=', $postData['map']];
        $count = self::where($where)->count();
        $data = self::where($where)->order('create_time DESC, id DESC')->limit(($page * $limit), $limit)->field('id, order_no, snap_name, total_count, total_price, status, create_time')->select();
        if ($data) {
            $arr = collection($data)->toArray();
        } else
            $arr = [];
        $ret = [
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $arr,
        ];
        return $ret;
    }
}