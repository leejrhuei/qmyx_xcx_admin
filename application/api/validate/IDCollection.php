<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/6
 * Time: 16:35
 */

namespace app\api\validate;

class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIds'
    ];

    protected $message = [
        'ids' => 'ids参数必须为以逗号分隔的多个正整数'
    ];

    protected function checkIDs($value)
    {
        $values = explode(',', $value);
        if (empty($values))
            return false;
        foreach ($values as $id) {
            if (!$this->isPositiveInteger($id))
                // 必须是正整数
                return false;
        }
        return true;
    }
}