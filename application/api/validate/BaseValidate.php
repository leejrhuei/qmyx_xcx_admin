<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/5
 * Time: 11:56
 */

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();
        $params['token'] = $request->header('token');
        // 批量验证
        if (!$this->batch()->check($params))
            throw new ParameterException([
                // $this->error有一个问题，并不是一定返回数组，需要判断
                'msg' => is_array($this->error) ? implode(';', $this->error) : $this->error,
            ]);
        return true;
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

    /**
     * 判断是否为空
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     */
    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value))
            return $field . '不允许为空';
        else
            return true;
    }

    /**
     * 根据规则获取参数
     * @param $arrays
     * @return array
     * @throws ParameterException
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) || array_key_exists('uid', $arrays))
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

    /**
     * 判断手机号码
     * @param $value
     * @return bool
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result)
            return true;
        else
            return false;
    }
}