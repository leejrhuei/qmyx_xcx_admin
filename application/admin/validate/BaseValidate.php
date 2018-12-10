<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 10:14
 */

namespace app\admin\validate;

use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $request = Request::instance();
        $params = array_filter($request->param());
        // 批量验证
        if (!$this->batch()->check($params)) {
            $msg = is_array($this->error) ? implode(';', $this->error) : $this->error;
            $ret = [
                'code' => 1001,
                'msg' => $msg,
            ];
            return $ret;
        }
        return $params;
    }

    /**
     * 判断是否正整数
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0)
            return true;
        return false;
    }
}