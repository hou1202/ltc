<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/4
 * Time: 11:34
 */

namespace app\index\validate;
use think\Validate;

class ExtractValidate extends Validate
{
    protected $rule = [
        'user_id' => 'require|number|integer|egt:1',
        'phone' => 'require|length:11|number',
        'number' => 'require|number|integer|egt:1',
        'plat' => 'require',
        'address' => 'require|alphaDash',
        'payment' => 'alphaDash|max:35',
        'pwd_trade' => 'require|min:6',
        'code' => 'require|length:6|number|integer',
    ];

    protected $message = [
        'user_id.require' => '提币申请信息有误',
        'user_id.number' => '提币申请信息有误',
        'user_id.integer' => '提币申请信息有误',
        'user_id.egt' => '提币申请信息有误',

        'phone.require' => '提币申请信息有误',
        'phone.number' => '提币申请信息有误',
        'phone.length' => '提币申请信息有误',

        'number.require' => '提币数量不得不空',
        'number.number' => '提币数量信息有误',
        'number.integer' => '提币数量信息有误',
        'number.egt' => '提币数量信息有误',

        'plat.require' => '提币平台信息有误',

        'address.require' => '提币地址信息有误',
        'address.alphaDash' => '提币地址信息有误',

        'payment.alphaDash' => '提币Payment ID信息有误',
        'payment.max' => '提币Payment ID信息有误',


        'pwd_trade.require' => '交易密码不正确',
        'pwd_trade.min' => '交易密码不正确',

        'code.require' => '验证码信息不正确',
        'code.length' => '验证码信息不正确',
        'code.number' => '验证码信息不正确',
        'code.integer' => '验证码信息不正确',
    ];
}