<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/23
 * Time: 11:04
 */

namespace app\admin\controller;

use app\common\controller\CommController;
use app\common\controller\ReturnJson;
use app\admin\model\Feed as FeedModel;


/*
 * 反馈管理
 **/
class Feed extends CommController
{

    /*
     * @ feedList   反馈列表
     * */
    public function feedList(){
        $feed = new FeedModel();
        $feedCount = $feed -> getCountFeed();
        $feedList = $feed -> getFeedForList();
        /*foreach($feedList as $value){
            $value['img'] = explode('|',$value['img']);
        }*/
        return $feedList -> items() ? view('feed_list',['List' => $feedList , 'Count' => $feedCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    /*
     * @ feedUpdate    编辑反馈信息
     * */
    public function feedUpdate(){
        //修改提交信息
        if($this -> request -> isPost()){
            $data = $this -> request -> Post();
            //var_dump($data);die;
            if(isset($data['id']) && empty($data['id'])){
                return ReturnJson::ReturnJ('无效的数据操作...','false','/feed/feedList');
            }
            $feed = new FeedModel();
            return $feed -> updateFeedInfoById($data['id'],$data) ? ReturnJson::ReturnJ('数据更新成功...', 'success', '/feed/feedList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');

        }

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $feed = new FeedModel();
            $getOne = $feed -> getOneFeedInfoById($id);
            return $getOne ?  view('feed_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的用户信息...","#/feed/feed_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }


    /*
   * @ feedSearch    搜索反馈信息
   * */
    public function feedSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $feed = new FeedModel();
                $List = $feed->getSearchFeedByKeyword($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('feed_list' , ['List' => $List , 'Count' => $feed->getCountSearchFeedByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }


}