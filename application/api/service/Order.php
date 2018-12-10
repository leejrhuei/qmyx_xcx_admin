<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/9
 * Time: 19:58
 */

namespace app\api\service;

use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;
use app\api\model\Order as OrderModel;

class Order
{
    // 订单的商品列表，客户端传递过来的products参数
    protected $oProducts;

    // 真实的商品信息
    protected $products;

    protected $uid;

    public function place($uid, $oProducts)
    {
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }
        // 开始创建订单
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    private function getProductsByOrder($oProducts)
    {
        $oPIds = array_column($oProducts, 'product_id');
        $products = Product::all($oPIds);
        if ($products)
            $products = collection($products)->visible(['id', 'price', 'stock', 'name', 'main_img_url'])->toArray();
        return $products;
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];
        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
            // 第一次检测库存量
            if ($pStatus['haveStock'] == false)
                $status['pass'] = false;
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['counts'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    private function getProductStatus($oPId, $oCount, $products)
    {
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'counts' => 0,
            'price' => 0,
            'name' => '',
            'totalPrice' => 0,
            'main_img_url' => null,
        ];
        $pIndex = -1;
        $count = count($products);
        for ($i = 0; $i < $count; $i++) {
            if ($oPId == $products[$i]['id'])
                $pIndex = $i;
        }
        if ($pIndex == -1)
            throw new OrderException([
                'msg' => 'id为' . $oPId . '的商品不存在，订单创建失败'
            ]);
        $product = $products[$pIndex];
        $pStatus['id'] = $product['id'];
        $pStatus['name'] = $product['name'];
        $pStatus['counts'] = $oCount;
        $pStatus['price'] = $product['price'];
        $pStatus['main_img_url'] = $product['main_img_url'];
        $pStatus['totalPrice'] = $product['price'] * $oCount;
        if ($product['stock'] - $oCount >= 0)
            $pStatus['haveStock'] = true;
        return $pStatus;
    }

    /**
     * 生成订单快照
     */
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => '',
            'snapName' => '',
            'snapImg' => '',
        ];
        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        if (count($this->products) > 1)
            $snap['snapName'] .= '等';
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        return $snap;
    }

    private function getUserAddress()
    {
        $userAddress = UserAddress::where('user_id', $this->uid)->find();
        if (!$userAddress)
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        return $userAddress->toArray();
    }

    private function createOrder($snap)
    {
        try {
            Db::startTrans();
            $orderNo = self::makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();
            $orderId = $order->id;
            $create_time = $order->create_time;
            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderId;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $create_time
            ];
        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 生成订单号
     * @return string
     */
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));
        return $orderSn;
    }

    public function checkOrderStock($orderId)
    {
        $oProducts = OrderProduct::where('order_id', $orderId)->select();
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }

    public function delivery($orderID, $jumpPage = '')
    {
        $order = OrderModel::where('id', $orderID)->find();
        if (!$order)
            throw new OrderException();
        if ($order->status != OrderStatusEnum::PAID)
            throw new OrderException([
                'msg' => '还没付款呢，想干嘛？或者你已经更新过订单了，不要再刷了',
                'errorCode' => 80002,
                'code' => 403
            ]);
        $order->status = OrderStatusEnum::DELIVERED;
        $order->save();
        $message = new DeliveryMessage();
        return $message->sendDeliveryMessage($order, $jumpPage);
    }
}