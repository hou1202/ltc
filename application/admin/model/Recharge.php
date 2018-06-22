<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/15
 * Time: 18:55
 */

namespace app\admin\model;
use think\Model;

class Recharge extends Model
{
    public static $tableName = 'think_recharge';

    protected $autoWriteTimestamp = true;

    //取值充币状态显示
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '审核中',1 => '已通过',2 => '已驳回'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    /*
     * @getRechargeForList 充币列表显示
     * */
    public function getRechargeForList($key='id',$des='DESC'){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.number,l.recharge_id,l.state,l.create_time')
            ->join('think_user r','l.user_id = r.id','left')
            -> order('l.'.$key.' '.$des.'')
            ->group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/recharge/rechargeList' ]);
    }


    /*
     * @ getCountRecharge   统计充币数量
     * */
    public function getCountRecharge(){
        return $this -> count('id');
    }

    /*
     * @ getOneRechargeInfoById  获取指定充币信息
     * $id      充币ID
     * */
    public function getOneRechargeInfoById($id,$field='id'){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.user_id,l.number,l.recharge_id,l.state,l.talk,l.create_time')
            ->join('think_user r','l.user_id = r.id','left')
            -> where('l.'.$field,$id)
            ->group('l.id')
            -> find();
    }

    /*
     * @ updateRechargeInfoById  更新指定充币信息
     * $id      充币ID
     * */
    public function updateRechargeInfoById($id,$data){
        return $this -> allowField(true)
            -> where('id',$id)
            -> update($data);
    }

    /*
     * @ getSearchRechargeByKeyword  通过关键词搜索充币信息
     * $keyword      关键词
     * */
    public function getSearchRechargeByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.number,l.recharge_id,l.state,l.create_time')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('l.id','like',$keyword)
            -> whereOr('r.number','like','%'.$keyword.'%')
            -> whereOr('r.phone','like','%'.$keyword.'%')
            -> order('l.id DESC')
            -> paginate(10,false,['path' => '/admin/main#/recharge/rechargeList' ]);
    }

    /*
     * @ getCountSearchRechargeByKeyword  统计通过关键词搜索充币信息
     * $keyword      关键词
     * */
    public function getCountSearchRechargeByKeyword($keyword){
        return $this -> alias('l')
            -> field('l.id')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('l.id','like',$keyword)
            -> whereOr('r.number','like','%'.$keyword.'%')
            -> whereOr('r.phone','like','%'.$keyword.'%')
            ->count();
    }

}