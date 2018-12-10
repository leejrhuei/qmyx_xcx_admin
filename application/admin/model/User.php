<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/19
 * Time: 11:23
 */

namespace app\admin\model;

class User extends BaseModel
{
    public static function getList($postData)
    {
        $page = ($postData['page'] - 1 >= 0) ? ($postData['page'] - 1) : 0;
        $limit = $postData['limit'];
        $where = [];
        if (isset($postData['map']) && is_numeric($postData['map']))
            $where['id'] = ['=', $postData['map']];
        if (isset($postData['map']) && !is_numeric($postData['map']))
            $where['nickname'] = ['like', "%{$postData['map']}%"];
        $count = self::where($where)->count();
        $data = self::where($where)->order('create_time DESC, id DESC')->limit(($page * $limit), $limit)->field('update_time, delete_time', true)->select();
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