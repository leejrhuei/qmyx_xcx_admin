<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 17:11
 */

namespace app\admin\model;

use traits\model\SoftDelete;

class ProductImage extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    protected $autoWriteTimestamp = true;

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    public function image()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }

    public static function del($id)
    {
        $bool = self::destroy($id);
        if (!$bool)
            $ret = [
                'code' => 1001,
                'msg' => '删除失败'
            ];
        else
            $ret = [
                'code' => 0,
                'msg' => '删除成功'
            ];
        return $ret;
    }
}