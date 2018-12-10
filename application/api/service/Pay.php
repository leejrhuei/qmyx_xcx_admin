<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/10
 * Time: 15:39
 */

namespace app\api\service;

use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use think\Loader;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class Pay
{
    private $orderId;
    private $orderNo;

    public function __construct($orderId)
    {
        if (!$orderId)
            throw new Exception('订单id不允许为空');
        $this->orderId = $orderId;
    }

    public function pay()
    {
        $this->checkOrderValid();
        // 第二次检测库存量
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderId);
        if (!$status['pass'])
            return $status;
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    private function checkOrderValid()
    {
        // 订单id可能根本不存在
        $order = OrderModel::where('id', $this->orderId)->find();
        if (!$order)
            throw new OrderException();
        // 订单id存在，但是订单id不属于用户id
        if (!Token::isValidOperate($order['user_id']))
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        // 订单是否未支付
        if ($order['status'] != OrderStatusEnum::UNPAID)
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
        $this->orderNo = $order['order_no'];
        return true;
    }

    private function makeWxPreOrder($totalPrice)
    {
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid)
            throw new TokenException();
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('千面英雄');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url'));
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        // 失败时不会返回result_code
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrderData, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id', $this->orderId)->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }
}