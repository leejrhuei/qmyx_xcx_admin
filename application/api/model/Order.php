<?php

namespace app\api\model;

class Order extends BaseModel
{
    protected $hidden = ['user_id', 'update_time', 'delete_time'];

    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value)
    {
        if (empty($value))
            return null;
        return json_decode($value);
    }

    public function getSnapAddressAttr($value)
    {
        if (empty($value))
            return null;
        return json_decode(($value));
    }

    public static function getSummaryByUser($uid, $page, $size)
    {
        $pagingData = self::where('user_id', $uid)->field('snap_items, snap_address', true)->order('create_time DESC')->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }

    public static function getSummaryByPage($page = 1, $size = 20)
    {
        $pagingData = self::field('snap_items, snap_address', true)->order('create_time desc')->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }
}
