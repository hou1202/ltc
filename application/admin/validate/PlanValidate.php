<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/25
 * Time: 11:47
 */

namespace app\admin\validate;
use think\Validate;

class PlanValidate extends Validate
{
    protected $rule = [
        'day' => 'require|number|integer|gt:0',
        'ratio' => 'require|number|gt:0',
        'number' => 'require|number|integer|gt:0',
        'start_time' => 'require',
        'end_time' => 'require',
        'state' => 'require',
    ];

    protected $message = [
        'day.require' => '锁仓时间不得为空...',
        'day.number' => '锁仓时间必须为大于0的正整数...',
        'day.integer' => '锁仓时间必须为大于0的正整数...',
        'day.gt' => '锁仓时间必须为大于0的正整数...',
        'ratio.require' => '锁仓利率不得为空...',
        'ratio.number' => '锁仓利率必须为大于0的正数...',
        'ratio.gt' => '锁仓利率必须为大于0的正数...',
        'number.require' => '锁仓数量不得为空...',
        'number.number' => '锁仓数量必须为大于0的正整数...',
        'number.integer' => '锁仓数量必须为大于0的正整数...',
        'number.gt' => '锁仓数量必须为大于0的正整数...',
        'start_time.require' => '锁仓开始时间不得为空...',
        'end_time.require' => '锁仓结束时间不得为空...',
        'state.require' => '锁仓状态不得为空...',
    ];
}