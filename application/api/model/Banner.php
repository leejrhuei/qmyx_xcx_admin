<?php

namespace app\api\model;

class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    /**
     * 一对多关联banner_item表
     * @return \think\model\relation\HasMany
     */
    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    /**
     * 根据id获取信息
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getBannerById($id)
    {
        $data = self::with(['items', 'items.img'])->find($id);
        return $data;
    }
}