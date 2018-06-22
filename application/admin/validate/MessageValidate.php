<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/20
 * Time: 9:31
 */

namespace app\admin\validate;


use think\Validate;

class MessageValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:30',
        'content' => 'require',
        'type' => 'require|number|integer',
    ];

    protected $message = [
        'title.require' => '信息标题不得为空...',
        'title.max' => '信息标题最大不得超过10个字...',
        'content.require' => '信息内容不得为空...',
        'type.require' => '信息类型不得为空...',
        'type.number' => '信息类型不正确...',
        'type.integer' => '信息类型不正确...',
    ];
}