<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/25
 * Time: 16:25
 */

namespace app\admin\model;
use think\Model;

class Message extends Model
{

    public static $tableName = 'think_message';

    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    /*
     * 状态显示
     * 0 =》 文字状态
     * 1 =》 数值状态
     * */
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '未启用',1 => '已启用'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    /*
     * @getMessageForList   信息列表列表显示
     * $type    信息类型
     *      1=》LTC简介
     *      2=》注册协议
     *      3=》提币平台
     *      4=》充币地址
     * */
    public function getMessageForList($type,$list,$key='id',$des='DESC'){
        return $this -> field('id,title,content,state,create_time')
            -> where('type','in',$type)
            -> order(''.$key.' '.$des.'')
            -> paginate(10,false,['path' => '/admin/main#/setup/'.$list.'' ]);
    }

    /*
     * @ getCountMessage   统计信息数量
     * $type    信息类型
     * */
    public function getCountMessage($type){
        return $this -> where('type','in',$type) -> count('id');
    }

    /*
     * @ saveMessageById   保存信息
     * */
    public function saveMessageById($data,$id=null){
        if(empty($id)){
            return $this -> allowField(true) -> save($data);
        }else{
            return $this -> allowField(true) -> where('id',$id) -> update($data);
        }
    }

    /*
     * @ getOneMessageById  获取指定信息
     * $id      信息ID
     * */
    public function getOneMessageById($id,$field='id'){
        return $this -> alias('l')
            -> field('id,title,content,type,state,create_time')
            -> where($field , $id)
            -> find();
    }


    /*
     * @ delMessageById  删除指定信息
     * $id      信息ID
     * */
    public function delMessageById($id){
        return $this -> where('id' , $id)
            -> delete();
    }





}