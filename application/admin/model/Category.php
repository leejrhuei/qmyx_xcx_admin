<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 14:21
 */

namespace app\admin\model;

use think\Db;
use think\Exception;
use traits\model\SoftDelete;

class Category extends BaseModel
{
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    protected $autoWriteTimestamp = true;

    public function image()
    {
        return $this->belongsTo('image', 'topic_img_id', 'id');
    }

    public static function getList($postData)
    {
        $page = ($postData['page'] - 1 >= 0) ? ($postData['page'] - 1) : 0;
        $limit = $postData['limit'];
        $count = self::count();
        $data = self::with('image')->limit(($page * $limit), $limit)->field('id, name, description, topic_img_id')->select();
        if ($data) {
            $arr = collection($data)->toArray();
            foreach ($arr as $key => $value) {
                $arr[$key]['img_url'] = $value['image']['url'];
            }
        } else
            $arr = [];
        $ret = [
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $arr
        ];
        return $ret;
    }

    public static function add($postData)
    {
        try {
            Db::startTrans();
            $postData = array_filter($postData);
            $source = $postData['pic'];
            $destination = ROOT_PATH . 'public/images/' . pathinfo($source, PATHINFO_BASENAME);
            if (!copy(trim($source), $destination)) {
                $ret = [
                    'code' => 1001,
                    'msg' => pathinfo($source, PATHINFO_BASENAME) . '拷贝失败',
                ];
                return $ret;
            }
            $imageData['url'] = pathinfo($destination, PATHINFO_BASENAME);
            $imageData = Image::create($imageData);
            $postData['topic_img_id'] = $imageData->id;
            unset($postData['pic']);
            self::create($postData);
            Db::commit();
            $ret = [
                'code' => 0,
                'msg' => '新增成功'
            ];
        } catch (Exception $e) {
            Db::rollback();
            $ret = [
                'code' => 1001,
                'msg' => $e->getMessage()
            ];
        }
        return $ret;
    }

    public static function getInfoById($id)
    {
        $data = self::with('image')->find($id);
        if ($data)
            $data = $data->toArray();
        else
            $data = [];
        return $data;
    }

    public static function modify($postData)
    {
        try {
            Db::startTrans();
            $postData = array_filter($postData);
            $pic = dirname($postData['pic']);
            if ($pic != '.') {
                // 新图片
                $data = self::get($postData['id']);
                $source = $postData['pic'];
                $destination = ROOT_PATH . 'public/images/' . pathinfo($source, PATHINFO_BASENAME);
                if (!copy(trim($source), $destination)) {
                    $ret = [
                        'code' => 1001,
                        'msg' => pathinfo($source, PATHINFO_BASENAME) . '拷贝失败',
                    ];
                    return $ret;
                }
                $imageData['id'] = $data->topic_img_id;
                $imageData['url'] = pathinfo($destination, PATHINFO_BASENAME);
                Image::update($imageData);
            }
            unset($postData['pic']);
            self::update($postData);
            Db::commit();
            $ret = [
                'code' => 0,
                'msg' => '编辑成功'
            ];
        } catch (Exception $e) {
            Db::rollback();
            $ret = [
                'code' => 1001,
                'msg' => $e->getMessage()
            ];
        }
        return $ret;
    }

    public static function del($ids)
    {
        $msg = '';
        if (strpos($ids, ',')) {
            // 群删
            $idArr = explode(',', $ids);
            foreach ($idArr as $value) {
                $bool = self::destroy($value);
                if (!$bool)
                    $msg .= 'id: ' . $value . ' 删除失败;';
            }
        } else {
            // 单删
            $bool = self::destroy($ids);
            if (!$bool)
                $msg .= 'id: ' . $ids . ' 删除失败;';
        }
        if ($msg)
            $ret = [
                'code' => 1001,
                'msg' => substr($msg, 0, -1)
            ];
        else
            $ret = [
                'code' => 0,
                'msg' => '删除成功'
            ];
        return $ret;
    }

    public static function getAllList()
    {
        $data = self::field('id, name')->select();
        if ($data)
            $arr = collection($data)->toArray();
        else
            $arr = [];
        return $arr;
    }
}