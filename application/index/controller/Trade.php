<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/29
 * Time: 10:10
 */

namespace app\index\controller;
use app\common\controller\ReturnJson;
use app\common\controller\CommController;
use app\index\model\User;
use app\index\model\Trade as TradeModel;
use app\index\validate\TradeValidate;
use app\common\controller\PublicFunction;
use think\Session;
use think\Cookie;
use think\Hook;
use think\Db;

class Trade extends CommController
{

    //购买
    public function tradeBuy(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserPartByKey($id);
        //LTC价格
        $price = Db::name('price') -> field('price') ->order('id DESC')-> find();
        return $this -> fetch('trade/buy',['User'=>$userInfo,'Price'=>$price]);
    }

    public function buyCheck(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('非正确的购买交易，请重新操作');
        }
        $data = $this -> request -> post();
        $validate = new TradeValidate();
        if($validate -> check($data)){
            $id = Cookie::get('user');
            $user = new User();
            $userInfo = $user -> getUserPartByKey($id);
            if(md5($data['pwd_trade']) != $userInfo -> pwd_trade){
                return $this ->jsonFail('交易密码不正确...');
            }
            if($data['code'] != Session::get('buy_'.$data['phone'])){
                return $this ->jsonFail('您的验证码信息有误，请重新确认...');
            }
            $trade = new TradeModel();
            $data['buy_id'] = $userInfo -> id;
            //写入数据，并返回结果
            if($trade -> saveTrade($data)){
                //清除短信Session
                Session::delete('buy_'.$data['phone']);
                //更新验证码记录
                Db::table('think_log_verify')->where('phone='.$data['phone'].' AND type=4 AND verify='.$data['code'])->update(['status'=>1, 'e_time'=>date('Y-m-d H:i:s')]);
                return $this ->jsonSuccess('您的购买交易创建成功...','/index/trade/tradeList');
            }else{
                return $this ->jsonFail('购买交易出现问题，请重新操作...');
            }

        }else{
            return $this ->jsonFail($validate->getError());
        }
    }

    //汇款明细
    public function buyList(){
        return $this -> fetch('trade/buy_list');
    }

    //汇款详情
    public function buyDetail(){
        return $this -> fetch('trade/buy_detail');
    }

    //出售
    public function tradeSell(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserPartByKey($id);
        $trade = new TradeModel();
        $list = $trade -> noSelfUnTraded($id);
        //LTC价格
        $price = Db::name('price') -> field('price') -> order('id DESC') -> find();
        //var_dump($list);die;
        return $this -> fetch('trade/sell',["List"=>$list,"User"=>$userInfo,'Price'=>$price]);
    }

    //收款明细
    public function sellList(){
        return $this -> fetch('trade/sell_list');
    }

    //收款详情
    public function sellDetail(){
        return $this -> fetch('trade/sell_detail');
    }

    //交易列表
    public function tradeList(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $trade = new TradeModel();
        $list = $trade -> getTradeOrderByKey($id);
        //LTC价格
        $price = Db::name('price') -> field('price') -> order('id DESC') -> find();
        return $this -> fetch('trade/trade_list',['List'=>$list,'Price'=>$price]);
    }

    //取消交易订单
    public function cancelTrade(){
        if(!$this -> request -> isPost()){
            $this -> jsonFail('购买交易订单取消失败，请重新操作');
        }
        $data = $this -> request -> post('id');
        $trade = new TradeModel();
        return $trade -> delTrade($data) ? $this -> jsonSuccess('购买交易订单取消成功') : $this -> jsonFail('购买交易订单取消失败，请重新操作');

    }





}