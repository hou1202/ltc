<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/14
 * Time: 15:28
 */

namespace app\admin\model;
use think\Model;

class Lock extends Model
{
    public static $tableName = 'think_lock';

    protected $autoWriteTimestamp = true;

    //取值锁仓状态显示
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '锁仓中',1 => '已完成'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    /*
     * @getLockForList 锁仓列表显示
     * */
    public function getLockForList($key='id',$des='DESC'){
        return $this -> alias('l')
            -> field('r.number as name,r.phone,l.id,l.number,l.lock_time,l.lock_ratio,l.state,l.create_time')
            ->join('think_user r','l.user_id = r.id','left')
            -> order('l.'.$key.' '.$des.'')
            ->group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/lock/lockList' ]);
    }


    /*
     * @ getCountLock   统计锁仓数量
     * */
    public function getCountLock(){
        return $this -> count('id');
    }

    /*
     * @ getOneLockInfoByKey 获取指定单个锁仓计划信息
     * $id      锁仓ID
     * */
    public function getOneLockInfoByKey($id,$field='id'){
        return $this -> where($field,$id) -> find();
    }


    /*
     * @ getSearchLockByKeyword  通过关键词搜索锁仓计划
     * $keyword      关键词
     * */
    public function getSearchLockByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as name,r.phone,l.id,l.number,l.lock_time,l.lock_ratio,l.state,l.create_time')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('l.user_id','=',$keyword)
            -> whereOr('r.phone','like','%'.$keyword.'%')
            -> whereOr('r.number','like','%'.$keyword.'%')
            -> order('l.id DESC')
            -> paginate(10,false,['path' => '/admin/main#/user/userList' ]);
    }

    /*
     * @ getCountSearchLockByKeyword  统计通过关键词搜索计划
     * $keyword      关键词
     * */
    public function getCountSearchLockByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as name,r.phone,l.id,l.number,l.lock_time,l.lock_ratio,l.state,l.create_time')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('l.user_id','=',$keyword)
            -> whereOr('r.phone','like','%'.$keyword.'%')
            -> whereOr('r.number','like','%'.$keyword.'%')
            ->count();
    }


    /*
     * @ breakLockById       中断指定锁仓计划
     * $id      锁仓ID
     * */
    public function breakLockById($id){
        $data['state'] = 1;
        $data['is_break'] = time();
        return $this -> allowField(['state','is_break']) -> where('id',$id) -> update($data);
    }

    /*
     * @ getLockGroupUser       按用户进行分组，获取锁仓唯一锁仓用户
     * */
    public function getLockGroupUser(){
        return $this -> field('id,user_id')
                    ->group('user_id')
                    ->select();
    }

    /*
     * @ getLockByUser       获取指定用户的所有有效锁仓
     * $user                 用户ID
     * */
    public function getLockByUser($user){
        return $this -> field('id,user_id,number,lock_time,lock_ratio,create_time')
            ->where('state',0)
            ->where('user_id',$user)
            ->select();
    }

    /*
     * @ setLockState       完成锁仓，更改锁仓状态
     * $id                锁仓ID
     * */
    public function setLockState($id){
        return $this -> where('id',$id) -> setField('state',1);
    }



}