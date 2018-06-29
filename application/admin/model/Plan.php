<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/23
 * Time: 15:45
 */

namespace app\admin\model;
use think\Model;

class Plan extends Model
{

    public static $tableName = 'think_plan';

    protected $autoWriteTimestamp = true;

    //取值开始时间显示
    protected function getStartTimeAttr($value){
        return date('H:i',$value);
    }

    //取值结束时间显示
    protected function getEndTimeAttr($value){
        return date('H:i',$value);
    }

    /*
     * 状态显示
     * 0 =》 关闭
     * 1 =》 启用
     * */
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '关闭',1 => '启用'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    /*
    * @getPlanForList 锁仓计划列表显示
    * */
    public function getPlanForList($key='id',$des='DESC'){
        return $this -> field('id,day,ratio,number,start_time,end_time,state')
                    -> order(''.$key.' '.$des.'')
                    -> paginate(10,false,['path' => '/admin/main#/setup/setLockList' ]);
    }

    /*
     * @ getCountPlan   统计锁仓计划数量
     * */
    public function getCountPlan(){
        return $this -> count('id');
    }

    /*
     * @ insertPlan   新增锁仓计划
     * */
    public function insertPlan($data){
        return $this -> allowField(true)->insert($data);
    }


    /*
     * @ getOnePlanInfo  获取指定锁仓计划信息
     * $id      用户ID
     * */
    public function getOnePlanInfo($id,$field='id'){
        return $this -> field('id,day,ratio,number,start_time,end_time,state')
            -> where($field , $id)
            -> find();
    }


    /*
     * @ updatePlanById  更新指定锁仓计划信息
     * $id      锁仓计划ID
     * */
    public function updatePlanById($id,$data){
        return $this -> allowField(true)
            -> where('id',$id)
            -> update($data);
    }


    /*
     * @ delPlanById   删除锁仓计划
     * $id              锁仓计划ID
     * */
    public function delPlanById($id){
        return $this -> where('id',$id)->delete();
    }


}