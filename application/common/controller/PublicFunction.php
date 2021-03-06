<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/13
 * Time: 18:16
 */

namespace app\common\controller;

use think\Db;


class PublicFunction
{
    /*
     * @SetShareId          生成会员分享ID
     * $num                 生成ID数位
     * */
    static function SetShareId($num){
        $str = "0123456789QWERTYUPASDFGHJKLZXCVBNM";
        $share = '';
        for ( $i = 0; $i < $num; $i++ )
        {
            $share .= $str[ mt_rand(0, strlen($str) - 1) ];
        }
        return $share;
    }

    /*
     * @SetUserNumber          生成会员编号ID
     * $num                 生成ID数位
     * */
    static function SetUserNumber(){
        $number = 'LTC';
        $timeStr = time();
        $number .= substr($timeStr,3);
        return $number;
    }

    /*
     * @SetCapitalLog       生成资金变更记录
     * $id                  用户ID
     * $capital             变动资金
     * $way                 变动方式
     *          1=》系统增加；
     *          2=》系统减少；
     *          3=》锁仓收益；
     *          4=》分享收益；
     *          5=》交易支出；
     *          6=》交易收入；
     *          7=》交易手续费；
     *          8=》锁仓减少；
     *          9=》开仓增加
     *          10=》提币减少
     *          11=》提币驳回增加
     *          12=》充币增加
     *          13=》签到增加
     * */
    static function SetCapitalLog($id,$capital,$way=1){
        $data['user_id'] = $id;
        $data['capital'] = abs($capital);
        $data['way'] = $way;
        $data['create_time'] = time();
        $data['update_time'] = time();
        $getLogId = Db::name('capital_log') -> insertGetId($data);
        if($getLogId){
            return $getLogId;
        }else{
            return false;
        }
    }

    /*
     * getTotalProfit       计算收益
     * $id                  用户ID
     * $way                收益获取方式（对应way）
     * $type                收益时间
     *      1=》总收益  （默认）
     *      2=》当天收益
     * */
    static function getTotalProfit($user,$way=3,$type=1){
        switch($type){
            case 1:
                //总收益
                return Db::name('capital_log') -> where('user_id',$user) -> where('way',$way) -> sum('capital');
                break;
            case 2:
                //今天收益
                return  Db::name('capital_log') -> where('user_id',$user) -> where('way','in',$way) -> where('create_time','>',strtotime(date('Y-m-d',time()))) ->sum('capital');
                break;
            default:
                return false;
                break;
        }
        //$profit = Db::name('capital_log') -> where('user_id',$user) -> where('way',$type) -> count('capital');
    }
}