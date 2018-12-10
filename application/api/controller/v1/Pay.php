<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/10
 * Time: 15:28
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use \app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getpreorder']
    ];

    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        $pay->pay();
    }

    public function receiveNotify()
    {
        // 第三次检测库存量
        // 更新订单状态
        // 减库存
        $notify = new WxNotify();
        $notify->Handle();
    }
}