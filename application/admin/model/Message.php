<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 16:10
 */
namespace app\admin\model;

use think\Model;

class Message  extends Model
{
    //自动写入创建及修改的时间戳
    protected $autoWriteTimestamp = true;

    public function getCreateTimeAttr($value){
        return date('Y-m-d' , $value);
    }

    //信息列表
    public function getMessageList(){
        return $this -> field('id,title,content,type,create_time')
                    ->order('id DESC')
                    -> paginate(5,false,['path' => '/admin/main#/message/messageList' ]);

    }

    //按id统计信息数量
    public function getCountMessage(){
        return $this ->field('id') ->count();
    }

    //添加信息
    public function addMessage($data){
        return $this -> allowField(true) -> save($data);
    }

    //检查类型代码是否存在
    public function checkTypeState($data){
        return $this -> field('id') -> where('type',$data) -> limit(1) -> find();
    }

    //删除指定信息
    public function delMessage($id)
    {
        return $this -> where('id',$id) ->delete();
    }

    //获取指定信息内容
    public function getOneMessage($id){
        return $this -> field('id,title,type,content') -> where('id',$id) -> limit(1) -> find();
    }

    //更新信息内容
    public function updateMessage($id,$data){

        return $this -> allowField(true) -> where('id',$id) -> update($data);
    }


}