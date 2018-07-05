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
use app\index\validate\TradeConfirm;
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

    //购买验证
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
            if(!$user -> checkUserAccount($userInfo->id)){
                return $this ->jsonFail('您的帐户信息尚不完整，请先完善帐户信息','/index/account/personAccount');
            }
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
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $trade = new TradeModel();
        //遍历失效交易
        $this -> foreachInvalidTrade($id,'buy_id');
        $list = $trade -> getTradeOrderByKey($id,'buy_id');
        return $this -> fetch('trade/buy_list',["List" => $list]);
    }

    //汇款详情
    public function buyDetail(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $trade = new TradeModel();
            //遍历失效交易
            $this -> foreachInvalidTrade($id,'buy_id');
            $getOne = $trade -> getTradeDetailById($id);
            $getOne ->leftTime =($getOne->trade_time+60*60*3) - time();
            return $this -> fetch('trade/buy_detail',['getOne'=>$getOne]);
        }
    }

    //确认付款验证
    public function buyConfirm(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('付款确认信息有误，请重新操作');
        }
        $data = $this -> request -> post();
        $validate = new TradeConfirm();
        if($validate -> check($data)){
            $id = Cookie::get('user');
            $user = new User();
            $userInfo = $user -> getUserPartByKey($id);
            if(md5($data['pwd_trade']) != $userInfo -> pwd_trade){
                return $this ->jsonFail('交易密码不正确...');
            }
            $trade = new TradeModel();
            //更改状态，并返回结果
            return $trade -> setTradeStateById($data['id'],2) && $trade -> setTradeStateById($data['id'],1,'remit_state') ? $this ->jsonSuccess('确认付款成功，请等待对方确认收款') : $this ->jsonFail('付款出现问题，请重新操作');

        }else{
            return $this ->jsonFail($validate->getError());
        }
    }

    //出售
    public function tradeSell(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserPartByKey($id);
        $trade = new TradeModel();
        //除自己出售信息以外的列表
        $list = $trade -> noSelfUnTraded($id);
        //LTC价格
        $price = Db::name('price') -> field('price') -> order('id DESC') -> find();
        return $this -> fetch('trade/sell',["List"=>$list,"User"=>$userInfo,'Price'=>$price]);
    }

    //出售验证
    public function sellCheck(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('非正确的出售交易，请重新操作');
        }
        $data = $this -> request -> post();
        if(!isset($data['id']) || empty($data['id'])){
            return $this ->jsonFail('非正确的出售交易，请重新操作');
        }

        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserPartByKey($id);
        $trade = new TradeModel();
        $getTrade = $trade -> getTradeInfoByKey($data['id']);
        if(!$getTrade || $getTrade -> trade_state[1] != 0){
            return $this ->jsonFail('非正确的出售交易，请重新操作');
        }
        //LTC价格
        $price = Db::name('price') -> field('price') ->order('id DESC')-> find();
        //判断可用资产是否大于交易总金额

        $total = ($getTrade->number * $price['price']) + ($getTrade->number * 0.03) ;
        if($userInfo -> asset_avali < $total){
            return $this ->jsonFail('您的可用资产不足，无法完成交易');
        }
        //整理数据
        $data['trade_id'] = 'LTC'.time();
        $data['sell_id'] = $userInfo -> id;
        $data['ltc_price'] = $price['price'];
        $data['count_price'] = $getTrade->number * $price['price'];
        $data['service_price'] = $getTrade->number * 0.03;
        $data['trade_state'] = 1;
        $data['trade_time'] = time();
        //保存数据
        if($trade -> saveTrade($data,$getTrade->id)){
            //减少用户可用资产，并生成记录
            $user ->  setUserAsset($userInfo->id,$total,2);
            //交易减少记录
            PublicFunction::SetCapitalLog($userInfo->id,$getTrade->number * $price['price'],5);
            //手续费减少记录
            PublicFunction::SetCapitalLog($userInfo->id,$getTrade->number * 0.03,7);
            return $this ->jsonSuccess('您的出售交易操作成功，请等待买方付款','/index/trade/sellList');
        }else{
            return $this ->jsonFail('出售交易出现问题，请重新操作...');
        }

    }

    //收款明细
    public function sellList(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $trade = new TradeModel();
        //遍历失效交易
        $this -> foreachInvalidTrade($id,'sell_id');
        $list = $trade -> getTradeOrderByKey($id,'sell_id');
        return $this -> fetch('trade/sell_list',["List" => $list]);
    }

    //收款详情
    public function sellDetail(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $trade = new TradeModel();
            //遍历失效交易
            $this -> foreachInvalidTrade($id,'sell_id');
            $getOne = $trade -> getTradeDetailById($id);
            $getOne ->leftTime =($getOne->trade_time+60*60*3) - time();
            return $this -> fetch('trade/sell_detail',['getOne'=>$getOne]);
        }
    }

    //确认收款验证
    public function sellConfirm(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('收款确认信息有误，请重新操作');
        }
        $data = $this -> request -> post();
        $validate = new TradeConfirm();
        if($validate -> check($data)){
            $id = Cookie::get('user');
            $user = new User();
            //var_dump($data);die;
            $userInfo = $user -> getUserPartByKey($id);
            if(md5($data['pwd_trade']) != $userInfo -> pwd_trade){
                return $this ->jsonFail('交易密码不正确...');
            }
            $trade = new TradeModel();
            //更改状态，并返回结果
            return $trade -> setTradeStateById($data['id'],3) ? $this ->jsonSuccess('确认收款成功，此次交易已完成') : $this ->jsonFail('确认收款出现问题，请重新操作');

        }else{
            return $this ->jsonFail($validate->getError());
        }
    }

    //交易列表
    public function tradeList(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $trade = new TradeModel();
        $list = $trade -> getTradeOrderByKey($id,'buy_id','0');
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

    //最新成功
    public function newestDeal(){
        Hook::listen('CheckAuth',$params);
        $trade = new TradeModel();
        $list = $trade -> getNewestDealTrade();
        return $this -> fetch('lock/new_deal',['List'=>$list]);
    }


    /*
     * @checkAccountIntact          检查用户帐户完整性
     * */
    public function checkAccountIntact(){
        $id = Cookie::get('user');
        $user = new User();
        if(!$user -> checkUserAccount($id)){
            return $this ->jsonFail('您的帐户信息尚不完整，请先完善帐户信息','/index/person/personAccount');
        }else{
            return $this ->jsonSuccess('您的帐户信息尚完整');
        }
    }


    /*
     * foreachInvalidTrade      遍历订单是否过期
     * */
    protected function foreachInvalidTrade($id,$field){
        $trade = new TradeModel();
        $list = $trade -> getTradeOrderByKey($id,$field);
        //遍历订单是否过期

        foreach($list as &$value){
            //var_dump($value -> trade_state[1]);die;
            if(!empty($value -> trade_time) && ($value -> trade_time+60*60*3) < time() && $value -> trade_state[1] != 3 && $value -> trade_state[1] == 4 ){
                $trade -> setTradeStateById($value->id,4);
            }
        }
    }





}