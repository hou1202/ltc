<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/15
 * Time: 12:09
 */

namespace app\common\model;
use think\Model;

class Friend extends Model

{
    public static $tableName = 'think_lock';

    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }


    /*
    * @ getFriendByUserId     获取分销关系用户
    * $id                     用户ID
    * @return
    * */
    public function getFriendByUserId($id){
        return $this -> field('id,user_id,p_id,grade')
                    -> where('user_id',$id)
                    -> select();
    }








}