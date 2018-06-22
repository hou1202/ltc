<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/20
 * Time: 9:31
 */

namespace app\admin\validate;


use think\Validate;

class NavValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:18',
        'img' => 'require|max:255',
        'key' => 'require|max:30',
    ];

    protected $message = [
        'title.require' => '导航标题不得为空...',
        'title.max' => '导航标题最大不得超过6个字...',
        'img.require' => '导航图内容不得为空...',
        'img.max' => '导航图内容最大不得超过255个字符...',
        'key.require' => '一级类目不得为空...',
        'key.max' => '一级类目最大不得超过10个字...',
    ];
}