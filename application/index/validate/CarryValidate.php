<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/2
 * Time: 10:55
 */
namespace app\index\validate;
use think\Validate;

class CarryValidate extends Validate
{

    protected $rule = [
        'uid' => 'require',
        'money' => 'require|number|integer',
        'alipay' => 'require',
        'phone' => 'require|length:11|number',
        'code' => 'require|number|length:6',
    ];

    protected $message = [
        'uid.require' => '数据信息错误，请重新操作...',
        'money.require' => '兑换淘币数不得为空...',
        'money.number' => '请输入正确的兑换淘币数量...',
        'money.integer' => '兑换淘币数须得为整数...',
        'alipay.require' => '兑换支付宝账户不得为空...',
        'phone.require' => '登录手机号码不得不空...',
        'phone.number' => '手机号码格式不正确...',
        'phone.length' => '手机号码格式不正确...',
        'code.require' => '验证码不得不空...',
        'code.number' => '验证码不正确...',
        'code.length' => '验证码不正确...',
    ];

}