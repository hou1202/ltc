<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/20
 * Time: 9:31
 */

namespace app\admin\validate;


use think\Validate;

class NewsValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:90',
        'author' => 'require|max:24',
        'content' => 'require',
    ];

    protected $message = [
        'title.require' => '新闻标题不得为空...',
        'title.max' => '新闻标题最大不得超过30个字...',
        'author.require' => '新闻作者不得为空...',
        'author.max' => '新闻作者最大不得超过8个字...',
        'content.require' => '新闻内容不得为空...',
    ];
}