<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/27
 * Time: 15:49
 */

namespace app\index\controller;
use app\common\controller\ReturnJson;
use app\common\controller\CommController;
use app\index\model\News as NewsModel;
use think\Cookie;
use think\Hook;



class News extends CommController
{
    public function news(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $news = new NewsModel();
        $list = $news -> getNewsForList($id);
        if(!$list){
            return $this ->jsonFail('暂未查询到有关于您的新闻信息...');
        }
        return $this -> fetch('person/news',['List'=>$list]);

    }

    public function newsDetail(){
        if($this -> request -> isGet()){
            if(isset($_GET['id']) && !empty($_GET['id'])){
                $id = $_GET['id'];
                $news = new NewsModel();
                $detail = $news -> getNewsDetailById($id);
                if(!$detail){
                    return $this ->jsonFail('新闻内容信息有误，请重新查看...');
                }
                return $this -> fetch('person/news_detail',['News' => $detail]);
            }
            return $this ->jsonFail('新闻内容信息有误，请重新查看...');
        }
        return $this ->jsonFail('新闻内容信息有误，请重新查看...');
    }

}