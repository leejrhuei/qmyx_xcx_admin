<?php

namespace app\api\model;

class Product extends BaseModel
{
    protected $hidden = ['category_id', 'main_img_id', 'from', 'create_time', 'update_time', 'delete_time', 'pivot'];

    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    /**
     * 获取某分类下商品
     * @param $categoryID
     * @param int $page
     * @param int $size
     * @param bool $paginate
     * @return \think\Paginator
     */
    public static function getProductsByCategoryID($categoryID, $paginate = true, $page = 1, $size = 30)
    {
        $query = self::where('category_id', $categoryID);
        if (!$paginate)
            return $query->select();
        else
            // paginate 第二参数true表示采用简洁模式，简洁模式不需要查询记录总数
            return $query->paginate($size, true, [
                'page' => $page
            ]);
    }

    /**
     * 获取最新商品信息
     * @param $count
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public static function getMostRecent($count)
    {
        $data = self::limit($count)->order('create_time desc, id desc')->select();
        if ($data)
            $data = collection($data)->hidden(['summary'])->toArray();
        return $data;
    }

    public static function getProductDetail($id)
    {
        $data = self::with([
            'imgs' => function ($query) {
                $query->with('imgUrl')->order('order ASC');
            }
        ])
            ->with(['properties'])
            ->find($id);
        return $data;
    }
}
