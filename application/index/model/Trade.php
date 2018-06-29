<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/29
 * Time: 10:11
 */

namespace app\index\model;
use think\Model;

class Trade extends Model
{
    public static $tableName = 'think_trade';

    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        $create_time[0] = date('Y-m-d',$value);
        $create_time[1] = $value;
        return $create_time;
    }

    //取值汇款状态显示
    protected function getRemitStateAttr($value){
        $state=array();
        $str = [0 => '待汇款',1 => '已汇款'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    //取值交易状态显示
    protected function getTradeStateAttr($value){
        $state=array();
        $str = [0 => '未交易',1 => '交易中',2 => '待确认',3 => '已完成',4 => '已失效'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    /*
     * @saveTrade       保存交易订单
     * $data            订单数据
     * $key             主键值，若主键值为空，则为保存新数据，若不为空则为更新数据
     * $field           主键字段，默认id
     * */
    public function saveTrade($data,$key=null,$field='id'){
        if($key){
            return $this -> allowField(true) -> save($data,[$field=>$key]);
        }else{
            return $this -> allowField(true) -> save($data);
        }
    }

    /*
     * @getTradeOrderByKey     按指定字段返回交易信息
     * */
    public function getTradeOrderByKey($id,$field='buy_id'){
        return $this -> where($field,$id)-> select();
    }

    /*
     * @delTrade     删除交易信息
     * */
    public function delTrade($id,$field='id'){
        return $this -> where($field,$id)-> delete();
    }

    /*
     * @noSelfUnTraded          获取非自己的未交易订单
     * $id                      用户ID
     * */
    public function noSelfUnTraded($id){
        return $this -> where('buy_id','not in',$id)
                    -> where('trade_state',0)
                    ->select();
    }












}