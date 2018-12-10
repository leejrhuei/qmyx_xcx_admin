<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 12:00
 */

namespace app\admin\model;

use think\Db;
use think\Exception;
use traits\model\SoftDelete;

class Product extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    protected $autoWriteTimestamp = true;

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    public function productImage()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    public static function getList($postData)
    {
        $page = ($postData['page'] - 1 >= 0) ? ($postData['page'] - 1) : 0;
        $limit = $postData['limit'];
        $where = [];
        if (isset($postData['map']) && is_numeric($postData['map']))
            $where['id'] = ['=', $postData['map']];
        if (isset($postData['map']) && !is_numeric($postData['map']))
            $where['name'] = ['like', "%{$postData['map']}%"];
        $count = self::where($where)->count();
        $data = self::where($where)->order('create_time DESC, id DESC')->limit(($page * $limit), $limit)->field('id, name, category_id, price, stock, main_img_url')->select();
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

    public static function add($postData)
    {
        try {
            Db::startTrans();
            $postData = array_filter($postData);
            // 封面图
            $source = $postData['main_img_url'];
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
            $postData['img_id'] = $imageData->id;
            $postData['main_img_url'] = pathinfo($destination, PATHINFO_BASENAME);
            $picsArr = $postData['pics'];
            unset($postData['pics']);
            $productData = self::create($postData);
            // 详情图
            foreach ($picsArr as $key => $value) {
                $source = $value;
                $destination = ROOT_PATH . 'public/images/' . pathinfo($source, PATHINFO_BASENAME);
                if (!copy(trim($source), $destination)) {
                    $ret = [
                        'code' => 1001,
                        'msg' => pathinfo($source, PATHINFO_BASENAME) . '拷贝失败',
                    ];
                    return $ret;
                }
                $saveImageData['url'] = pathinfo($destination, PATHINFO_BASENAME);
                $imageData = Image::create($saveImageData);
                $productImageData[$key]['img_id'] = $imageData->id;
                $productImageData[$key]['product_id'] = $productData->id;
            }
            $productImageModel = new ProductImage();
            $productImageModel->saveAll($productImageData);
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
        $data = self::with('productImage.image')->find($id);
        if ($data)
            $arr = $data->toArray();
        else
            $arr = [];
        return $arr;
    }

    public static function modify($postData)
    {
        try {
            Db::startTrans();
            $postData = array_filter($postData);
            $data = self::get($postData['id']);
            $dirname = dirname($postData['main_img_url']);
            if ($dirname != '.') {
                // 封面图
                $source = $postData['main_img_url'];
                $destination = ROOT_PATH . 'public/images/' . pathinfo($source, PATHINFO_BASENAME);
                if (!copy(trim($source), $destination)) {
                    $ret = [
                        'code' => 1001,
                        'msg' => pathinfo($source, PATHINFO_BASENAME) . '拷贝失败',
                    ];
                    return $ret;
                }
                $imageData['id'] = $data->img_id;
                $imageData['url'] = pathinfo($destination, PATHINFO_BASENAME);
                Image::update($imageData);
                $postData['main_img_url'] = pathinfo($destination, PATHINFO_BASENAME);
            }
            $picsArr = $postData['pics'];
            unset($postData['pics']);
            $productData = self::update($postData);
            // 详情图
            $productImageData = [];
            foreach ($picsArr as $key => $value) {
                $dirname = dirname($value);
                if ($dirname != '.') {
                    $source = $value;
                    $destination = ROOT_PATH . 'public/images/' . pathinfo($source, PATHINFO_BASENAME);
                    if (!copy(trim($source), $destination)) {
                        $ret = [
                            'code' => 1001,
                            'msg' => pathinfo($source, PATHINFO_BASENAME) . '拷贝失败',
                        ];
                        return $ret;
                    }
                    $saveImageData['url'] = pathinfo($destination, PATHINFO_BASENAME);
                    $imageData = Image::create($saveImageData);
                    $productImageData[$key]['img_id'] = $imageData->id;
                    $productImageData[$key]['product_id'] = $productData->id;
                }
            }
            if (!empty($productImageData)) {
                $productImageModel = new ProductImage();
                $productImageModel->saveAll($productImageData);
            }
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
}