<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/15
 * Time: 19:33
 */

namespace app\admin\controller;

use app\common\controller\CommController;
use app\common\controller\ReturnJson;
use app\admin\model\Trade as TradeModel;

class Trade extends CommController
{


    /*
     * @ tradeList   交易列表
     * */
    public function tradeList(){
        $trade = new TradeModel();
        $tradeCount = $trade -> getCountTrade();
        $tradeList = $trade -> getTradeForList();
        //var_dump($tradeList);die;
        return $tradeList -> items() ? view('trade_list',['List' => $tradeList , 'Count' => $tradeCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    /*
     * @ tradeUpdate    操作交易信息
     * */
    public function tradeUpdate(){

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $trade = new TradeModel();
            $getOne = $trade -> getOneTradeInfoById($id);
            return $getOne ?  view('trade_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的用户信息...","#/trade/trade_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }

    /*
    * @ tradeSearch    搜索交易信息
    * */
    public function tradeSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $trade = new TradeModel();
                $List = $trade->getSearchTradeByKeyword($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('trade_list' , ['List' => $List , 'Count' => $trade->getCountSearchTradeByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }

    /*
    * @ tradeSort    按排序信息查看交易信息
    * */
    public function tradeSort(){
        //获取、展示修改信息
        if(isset($_GET['key']) && !empty($_GET['key'])){
            $data = $this -> request -> get();
            $trade = new TradeModel();
            $tradeCount = $trade -> getCountTrade();
            $tradeList = $trade -> getTradeForList($data['key']);
            return $tradeList -> items() ? view('trade_list',['List' => $tradeList , 'Count' => $tradeCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
        }else{
            ReturnJson::ReturnA("无效的操作...");
        }

    }




}