<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/3
 * Time: 17:53
 */

namespace app\index\validate;
use think\Validate;

class AccountValidate extends Validate
{
    protected $rule = [
        'id' => 'require|number|integer|egt:1',
        'phone' => 'require|length:11|number',
        'portrait' => 'require',
        'bank' => 'max:60',
        'bank_num' => 'min:16|max:19|number',
        'bank_address' => 'max:90',
        'alipay' => 'max:30',
        'code' => 'require|length:6|number|integer',
    ];

    protected $message = [
        'id.require' => '帐户资料修改信息有误',
        'id.number' => '帐户资料修改信息有误',
        'id.integer' => '帐户资料修改信息有误',
        'id.egt' => '帐户资料修改信息有误',

        'phone.require' => '帐户资料修改信息有误',
        'phone.number' => '帐户资料修改信息有误',
        'phone.length' => '帐户资料修改信息有误',

        'portrait.require' => '头像资料信息有误',

        'bank.max' => '开户银行资料信息有误',

        'bank_num.min' => '银行卡号资料信息有误',
        'bank_num.max' => '银行卡号资料信息有误',
        'bank_num.number' => '银行卡号资料信息有误',

        'bank_address.max' => '开户银行支行资料信息有误',

        'alipay.max' => '支付宝信息有误',

        'code.require' => '验证码信息不正确',
        'code.length' => '验证码信息不正确',
        'code.number' => '验证码信息不正确',
        'code.integer' => '验证码信息不正确',
    ];
}