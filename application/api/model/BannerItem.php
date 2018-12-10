<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id', 'img_id', 'banner_id', 'update_time', 'delete_time'];

    /**
     * 一对一关联image表
     * @return \think\model\relation\BelongsTo
     */
    public function img()
    {
        return $this->belongsTo('image', 'img_id', 'id');
    }
}
