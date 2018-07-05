<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/4
 * Time: 16:33
 */

namespace app\index\model;
use think\Model;

class Recharge extends Model
{

    public static $tableName = 'think_recharge';

    protected $autoWriteTimestamp = true;



    //取值状态显示
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '审核中',1 => '已通过',2 => '已驳回'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    /*
     * @getRechargeById          获取指定用户的充币信息
     * $id                      用户ID
     * */
    public function getRechargeById($id){
        return $this -> field('id,number,recharge_id,state')
            -> where('user_id',$id)
            -> order('id DESC')
            ->select();
    }

    /*
     * @getRechargeDetailById    获取指定提币申请详情
     * $id                      ID
     * */
    public function getRechargeDetailById($id){
        return $this -> alias('e')
            -> field('u.number as user_number,u.phone,e.id,e.user_id,e.recharge_id,e.number,e.talk,e.create_time,e.state')
            -> join('think_user u','u.id = e.user_id','inner')
            -> group('e.id')
            -> where('e.id',$id)
            -> find();
    }

    /*
     * @saveRechargeLog    保存充币记录
     * $data                      记录数据
     * */
    public function saveRechargeLog($data){
        return $this -> allowField(true) -> save($data);
    }
}