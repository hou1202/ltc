<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/2
 * Time: 10:55
 */
namespace app\index\validate;
use think\Validate;

class LockValidate extends Validate
{

    protected $rule = [
        'number' => 'require|number|integer|egt:1|elt:50',
        'plan' => 'require|number|integer',
        'pwd_trade' => 'require|min:6',
    ];

    protected $message = [
        'number.require' => '锁仓计划数量不得不空...',
        'number.number' => '锁仓计划数量为小于50和可用资产的正整数...',
        'number.integer' => '锁仓计划数量为小于50和可用资产的正整数...',
        'number.egt' => '锁仓计划数量为小于50和可用资产的正整数...',
        'number.elt' => '锁仓计划数量为小于50和可用资产的正整数...',
        'pwd_trade.require' => '交易密码不正确...',
        'pwd_trade.min' => '交易密码不正确...',
        'plan.require' => '锁仓计划类型信息有误...',
        'plan.number' => '锁仓计划类型信息有误...',
        'plan.integer' => '锁仓计划类型信息有误...',
    ];

}