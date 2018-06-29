<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/15
 * Time: 19:32
 */

namespace app\admin\model;
use think\Model;

class Trade extends Model
{
    public static $tableName = 'think_trade';

    protected $autoWriteTimestamp = true;

    //取值交易状态显示
    protected function getTradeStateAttr($value){
        $state=array();
        $str = [0 => '未交易',1 => '交易中',2 => '待确认',3 => '已完成',4 => '已失效'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;


    }

    //取值汇款状态显示
    protected function getRemitStateAttr($value){
        $remitState = array();
        $str = [0 => '待汇款',1 => '已汇款'];
        $remitState[0] = $str[$value];
        $remitState[1] = $value;
        return $remitState;
    }

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    //取值交易时间显示
    protected function getTradeTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }


    /*
    * @getTradeForList 交易列表显示
    * */
    public function getTradeForList($key='id',$des='DESC'){
        return $this -> alias('l')
            -> field('r.number as buy_number,t.number as sell_number,l.id,l.trade_id,l.number,l.ltc_price,l.count_price,l.service_price,l.remit_state,l.trade_state,l.trade_time')
            -> join('think_user r','r.id = l.buy_id','left')
            -> join('think_user t','t.id = l.sell_id','left')
            -> order('l.'.$key.' '.$des.'')
            -> group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/trade/tradeList' ]);
    }


    /*
     * @ getCountTrade   统计交易数量
     * */
    public function getCountTrade(){
        return $this -> count('id');
    }

    /*
     * @ getOneTradeInfoById  获取指定交易信息
     * $id      交易ID
     * */
    public function getOneTradeInfoById($id,$field='id'){
        return $this -> alias('l')
            -> field('r.number as buy_number,t.number as sell_number,l.id,l.trade_id,l.number,l.ltc_price,l.count_price,l.service_price,l.remit_state,l.trade_state,l.trade_time,l.create_time')
            -> join('think_user r','r.id = l.buy_id','left')
            -> join('think_user t','t.id = l.sell_id','left')
            -> where('l.'.$field,$id)
            -> group('l.id')
            -> find();
    }


    /*
     * @ getSearchTradeByKeyword  通过关键词搜索交易信息
     * $keyword      关键词
     * */
    public function getSearchTradeByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as buy_number,t.number as sell_number,l.id,l.trade_id,l.number,l.ltc_price,l.count_price,l.service_price,l.remit_state,l.trade_state,l.trade_time')
            -> join('think_user r','r.id = l.buy_id','left')
            -> join('think_user t','t.id = l.sell_id','left')
            -> where('l.id','like',$keyword)
            -> whereOr('r.number','like','%'.$keyword.'%')
            -> whereOr('t.number','like','%'.$keyword.'%')
            -> order('l.id DESC')
            -> paginate(10,false,['path' => '/admin/main#/trade/tradeList' ]);
    }

    /*
     * @ getCountSearchTradeByKeyword  统计通过关键词搜索交易信息
     * $keyword      关键词
     * */
    public function getCountSearchTradeByKeyword($keyword){
        return $this -> alias('l')
            -> field('l.id')
            -> join('think_user r','r.id = l.buy_id','left')
            -> join('think_user t','t.id = l.sell_id','left')
            -> where('l.id','like',$keyword)
            -> whereOr('r.number','like','%'.$keyword.'%')
            -> whereOr('t.number','like','%'.$keyword.'%')
            ->count();
    }

}