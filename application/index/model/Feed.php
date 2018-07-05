<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/4
 * Time: 17:29
 */

namespace app\index\model;
use think\Model;

class Feed extends Model
{
    public static $tableName = 'think_feed';

    protected $autoWriteTimestamp = true;

    /*protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }*/

    //取值状态显示
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '未处理',1 => '已处理'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    /*
     * @saveFeed             保存反馈信息
     * $data                    数据
     * */
    public function saveFeed($data){
        return $this -> allowField(true)
            ->save($data);
    }

    /*
     * @getFeedByUser       获取指定用户的所有反馈
     *$id                   用户ID
     * */
    public function getFeedByUser($id){
        return $this -> field('id,content,state,create_time')
            -> where('user_id',$id)
            -> order('id DESC')
            -> select();
    }


    /*
     * @getFeedDetailById       获取指定反馈
     *$id                   ID
     * */
    public function getFeedDetailById($id){
        return $this -> alias('f')
            -> field('u.number as user_number,u.portrait,f.id,f.content,f.img,f.reply,f.state,f.create_time,f.update_time')
            -> join('think_user u','u.id = f.user_id','inner')
            -> group('f.id')
            -> where('f.id',$id)
            -> find();
    }
}