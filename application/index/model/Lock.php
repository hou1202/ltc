<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/27
 * Time: 17:29
 */

namespace app\index\model;
use think\Model;

class Lock extends Model
{
    public static $tableName = 'think_lock';

    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        $create_time[0] = date('Y-m-d',$value);
        $create_time[1] = $value;
        return $create_time;
    }

    //取值状态显示
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '锁仓中',1 => '已完成',2 => '中断锁仓'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    /*
     * @getTodayLock  获取当前时间点的有效锁仓计划
     * */
    public function getTodayLock(){
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',time()).' 23:59:59');
        return $this -> field('id,number,lock_time,create_time')
            -> where('create_time','>',$today_start)
            -> where('create_time','<',$today_end)
            ->select();
    }

    /*
     * @saveLock        保存锁仓计划
     * */
    public function saveLock($data){
        return $this -> allowField(true)
                    -> save($data);
    }

    /*
     * @getAppointLock      按KEY查找锁仓信息
     * */
    public function getAppointLock($key,$field="user_id"){
        return $this -> where($field,$key) -> order('id DESC') -> select();
    }

    /*
     * @getOneLockInfo      按KEY查找指定锁仓信息
     * */
    public function getOneLockInfo($key,$field="id"){
        return $this -> where($field,$key) -> find();
    }
}