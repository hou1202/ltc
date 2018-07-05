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
        if($key != null){
            return $this -> allowField(true) -> save($data,[$field=>$key]);
        }else{
            return $this -> allowField(true) -> save($data);
        }
    }

    /*
     * @getTradeOrderByKey     按指定字段返回交易信息
     * */
    public function getTradeOrderByKey($id,$field='buy_id',$state='1,2,3,4'){
        return $this -> where($field,$id)
                    -> where('trade_state','in',$state)
                    -> order('id DESC')
                    -> select();
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
                    -> order('id DESC')
                    ->select();
    }

    /*
     * @getTradeInfoByKey       获取唯一指定交易信息
     * $key             主键值
     * $field           主键字段，默认id
     * */
    public function getTradeInfoByKey($key,$field='id'){
        return $this -> where($field,$key) -> find();
    }


    /*
     * @getTradeDetailById       获取唯一指定交易详细信息
     * $id             ID
     * */
    public function getTradeDetailById($id){
        return $this -> alias('t')
            -> field('l.number as buy_number,l.name as buy_name,l.phone as buy_phone,r.bank as sell_bank,r.bank_num as sell_bank_num,r.alipay as sell_alipay,r.bank_address as sell_bank_address,r.number as sell_number,r.name as sell_name,r.phone as sell_phone,t.id,t.trade_id,t.number,t.ltc_price,t.count_price,t.service_price,t.remit_state,t.trade_state,t.trade_time,t.create_time')
            -> join('think_user l','t.buy_id = l.id','left')
            -> join('think_user r','t.sell_id = r.id','right')
            -> where('t.id',$id)
            -> group('t.id')
            -> find();
    }

    /*
     * @setTradeStateById       设置指定交易订单状态
     * $id             ID
     * */
    public function setTradeStateById($id,$value,$field='trade_state'){
        return $this -> where('id',$id)
            ->setField($field,$value);
    }

    //获取最新交易订单
    public function getNewestDealTrade(){
        return $this -> alias('t')
            -> field('l.number as buy_number,r.number as sell_number,t.number,t.count_price,t.trade_time')
            ->join('think_user l','t.buy_id = l.id','left')
            ->join('think_user r','t.sell_id = r.id','right')
            -> where('t.trade_state',3)
            -> group('t.id')
            -> order('t.id DESC')
            -> select();
    }












}