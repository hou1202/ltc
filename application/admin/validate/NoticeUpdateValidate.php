<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/13
 * Time: 17:12
 */
namespace app\admin\validate;


use think\Validate;

class NoticeUpdateValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:60',
        'content' => 'require|max:450',
    ];

    protected $message = [
        'title.require' => '通知信息标题不得为空...',
        'title.max' => '通知信息标题最大不得超过20个字...',
        'content.require' => '通知信息内容不得为空...',
        'content.max' => '通知信息内容最大不得超过150个字...',
    ];
}