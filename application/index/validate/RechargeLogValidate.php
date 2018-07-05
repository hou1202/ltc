<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/4
 * Time: 11:34
 */

namespace app\index\validate;
use think\Validate;

class RechargeLogValidate extends Validate
{
    protected $rule = [
        'user_id' => 'require|number|integer|egt:1',
        'number' => 'require|number|integer|egt:1',
        'recharge_id' => 'require|alphaDash',
    ];

    protected $message = [
        'user_id.require' => '充币申请信息有误',
        'user_id.number' => '充币申请信息有误',
        'user_id.integer' => '充币申请信息有误',
        'user_id.egt' => '充币申请信息有误',

        'number.require' => '充币数量不得不空',
        'number.number' => '充币数量信息有误',
        'number.integer' => '充币数量信息有误',
        'number.egt' => '充币数量信息有误',

        'recharge_id.require' => '交易ID信息有误',
        'recharge_id.alphaDash' => '交易ID信息有误',

    ];
}