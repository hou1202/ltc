<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 16:10
 */
namespace app\admin\model;

use think\Model;

class News  extends Model
{
    //自动写入创建及修改的时间戳
    protected $autoWriteTimestamp = true;

    //获取器自动处理state字段
    public function getStateAttr($value){
        $state = [0 => '未通过' , 1 => '通过'];
        return $state[$value];
    }

    //获取器自动处理recommend字段
    public function getRecommendAttr($value){
        $recommend = [0 => '不推荐' , 1 => '推荐'];
        return $recommend[$value];
    }

    public function getCreateTimeAttr($value){
        return date('Y-m-d' , $value);
    }


    //按id统计新闻数量
    public function getCountNews(){
        return $this -> alias('n')
                     ->field('n.id')
                     ->count();
    }


    //搜索新闻
    public function getSerachNews($keyword){
        return $this -> field('id,title,info,thumbnail,recommend,state,create_time')
                     -> where('id','=',$keyword)
                     -> whereOr('title','like','%'.$keyword.'%')
                     -> order('id DESC')
                     -> paginate(5,false,['path' => '/admin/main#/news/newsList' ]);
                     //-> select();
    }

    //按id统计搜索新闻数量
    public function getCountSerachNews($keyword){
        return $this -> field('id')
                     -> where('id','=',$keyword)
                     -> whereOr('title','like','%'.$keyword.'%')
                     ->count();
    }


}