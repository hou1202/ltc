<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/13
 * Time: 17:12
 */
namespace app\admin\validate;


use think\Validate;

class UserUpdateValidate extends Validate
{
    protected $rule = [
        'pwd_login' => 'min:6|max:30',
        'pwd_trade' => 'min:6|max:30',
        'asset_avali' => 'number',
        'name' => 'chs|min:6|max:18',
        'bank' => 'min:6|max:45',
        'bank_num' => 'number|min:16|max:19',
        'bank_address' => 'min:6|max:90',
        'alipay' => 'min:6|max:90',
        'recharge_address' => 'alphaNum',
        'state' => 'require',
    ];

    protected $message = [
        'pwd_login.max' => '用户登录密码不得大于30位...',
        'pwd_login.min' => '用户登录密码不得少于6位...',
        'pwd_trade.max' => '用户交易密码不得大于30位...',
        'pwd_trade.min' => '用户交易密码不得少于6位...',
        'asset_avali.number' => '用户可用资产只能为数字...',
        'name.max' => '用户真实姓名不得大于6位...',
        'name.min' => '用户真实姓名不得少于2位...',
        'name.chs' => '用户真实姓名只能为汉字...',
        'bank.max' => '开户行名称不得大于15位...',
        'bank.min' => '开户行名称不得少于2位...',
        'bank_num.max' => '银行帐户卡号不正确...',
        'bank_num.min' => '银行帐户卡号不正确...',
        'bank_num.number' => '银行帐户卡号不正确...',
        'bank_address.max' => '开户行支行名称不得大于30位...',
        'bank_address.min' => '开户行支行名称不得少于2位...',
        'alipay.max' => '支付宝帐户名称不得大于30位...',
        'alipay.min' => '支付宝帐户名称不得少于2位...',
        'recharge_address.alphaNum' => '充币地址信息有误...',
        'state.require' => '用户帐户状态不得为空...',
    ];
}