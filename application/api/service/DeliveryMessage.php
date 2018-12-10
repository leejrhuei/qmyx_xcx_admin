<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/14
 * Time: 17:41
 */

namespace app\api\service;

use app\api\model\User;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;

class DeliveryMessage extends WxMessage
{
    const DELIVERY_MSG_ID = 'bN9r5wV5LaANcsSRplEC2GR7FYUUsEyz1sEoRBzrcwM';// 小程序模板消息ID号

    public function sendDeliveryMessage($order, $tplJumpPage = '')
    {
        if (!$order)
            throw new OrderException();
        $this->tplID = self::DELIVERY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyWord = 'keyword1.DATA';
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageData($order)
    {
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => $order->order_no
            ],
            'keyword2' => [
                'value' => $order->snap_name,
                'color' => '#27408B'
            ],
            'keyword3' => [
                'value' => $dt->format("Y-m-d H:i")
            ]
        ];
        $this->data = $data;
    }

    private function getUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user)
            throw new UserException();
        return $user->openid;
    }
}