<?php

namespace app\api\model;

class Theme extends BaseModel
{
    protected $hidden = ['topic_img_id', 'head_img_id', 'update_time', 'delete_time'];

    /**
     * 一对一关联image表
     * @return \think\model\relation\BelongsTo
     */
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    /**
     * 一对一关联image表
     * @return \think\model\relation\BelongsTo
     */
    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    public static function getThemeList($ids)
    {
        $ids = explode(',', $ids);
        if (empty($ids))
            return [];
        $data = self::with('products')->select($ids);
        return $data;
    }

    public static function getThemeWithProducts($id)
    {
        $data = self::with('products,topicImg,headImg')->find($id);
        if ($data)
            $data->hidden(['products.summary'])->toArray();
        return $data;
    }
}
