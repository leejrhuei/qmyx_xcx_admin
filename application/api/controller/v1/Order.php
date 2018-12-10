<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/9
 * Time: 16:54
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeorder'],
        'checkPrimaryScope' => ['only' => 'getsummarybyuser,getdetail']
    ];

    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;
    }

    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty())
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        $data = $pagingOrders->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
    }

    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail)
            throw new OrderException();
        return $orderDetail->hidden(['prepay_id']);
    }

    /**
     * 获取全部订单简要信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummary($page = 1, $size = 20)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new PagingParameter())->goCheck();
        $pagingOrders = OrderModel::getSummaryByPage($page, $size);
        if ($pagingOrders->isEmpty())
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        $data = $pagingOrders->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
    }

    public function delivery($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success)
            return new SuccessMessage();
    }
}