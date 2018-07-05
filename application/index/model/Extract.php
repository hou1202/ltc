<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/4
 * Time: 12:06
 */

namespace app\index\model;
use think\Model;

class Extract extends Model
{
    public static $tableName = 'think_extract';

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
     * @saveExtract             保存提币申请
     * $data                    数据
     * */
    public function saveExtract($data){
        return $this -> allowField(true)
            ->save($data);
    }


    /*
     * @getExtractById          获取指定用户的提币申请
     * $id                      用户ID
     * */
    public function getExtractById($id){
        return $this -> field('id,number,plat,create_time,state')
            -> where('user_id',$id)
            -> order('id DESC')
            ->select();
    }

    /*
     * @getExtractDetailById    获取指定提币申请详情
     * $id                      ID
     * */
    public function getExtractDetailById($id){
        return $this -> alias('e')
            -> field('u.number as user_number,u.phone,e.id,e.user_id,e.plat,e.number,e.address,e.payment,e.service_price,e.true_num,e.talk,e.create_time,e.state')
            -> join('think_user u','u.id = e.user_id','inner')
            -> group('e.id')
            -> where('e.id',$id)
            -> find();
    }

    /*
     * @delExtractById    删除指定提币申请
     * $id                      ID
     * */
    public function delExtractById($id){
        return $this -> where('id',$id) -> delete();
    }
}