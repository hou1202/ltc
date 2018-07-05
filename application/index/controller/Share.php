<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/22
 * Time: 14:25
 */

namespace app\index\controller;
use app\common\controller\CommController;
use app\index\model\User;
use app\index\validate\TradeValidate;
use app\index\validate\TradeConfirm;
use app\common\controller\PublicFunction;
use think\Session;
use think\Cookie;
use think\Hook;
use think\Db;
use think\Loader;

class Share extends CommController
{
    //分享
    public function share(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserAllByKey($id);
        return $this -> fetch('share/share',['User'=>$userInfo]);
    }

    //下载详情页面
    public function downApp(){
        if(isset($_GET['type']) && !empty($_GET['type'])){
            $type = $_GET['type'];
            $detail = Db::name("message") -> field('title,content') -> where('type',$type) -> find();
            if(!$detail){
                return $this ->jsonFail('新闻内容信息有误，请重新查看...');
            }
            return $this -> fetch('person/news_detail',['News' => $detail,'Title'=>'下载 APP']);
        }
    }

    //分享好友 列表
    public function shareFriend(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $list = $user -> getShareFriends($id);
        foreach($list as $value){
            $value['first'] = Db::name('friend') -> where('p_id',$value->id) -> where('grade',1) -> count();
            $value['total'] = Db::name('friend') -> where('p_id',$value->id) -> count();
            $totalFriend = Db::name('friend') -> field('user_id,p_id') -> where('p_id',$value->id) -> select();
            $activeFriend = 0;
            foreach($totalFriend as $tv){
                $lock = Db::name('lock') -> field('id') -> where('user_id',$tv['user_id']) -> limit(1) -> find();
                if($lock){
                    $activeFriend++;
                }
            }
            $value['active'] = $activeFriend;
        }
        return $this -> fetch('share/sharefriend',['List'=>$list]);

    }

    //分享收益
    public function shareProfit(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $data['today'] = PublicFunction::getTotalProfit($id,4,2);
        $data['total'] = PublicFunction::getTotalProfit($id,4,1);
        $friend = Db::name('friend') -> field('user_id,p_id,grade') -> where('p_id',$id) -> select();
        //var_dump($friend);die;
        return $this -> fetch('share/shareprofit',['Profit'=>$data]);

    }

    //生成二维码
    public function shareImg(){

        Loader::import('phpqrcode.phpqrcode');
        $url=$this -> request ->get('url');
        //生成二维码URL
        $text= $url;
        //表示是否输出二维码图片 文件，默认false
        $outfile = false;
        /*
         * 表示容错率，也就是有被覆盖的区域还能识别，分别是
         * L（QR_ECLEVEL_L，7%），默认
         * M（QR_ECLEVEL_M，15%），
         * Q（QR_ECLEVEL_Q，25%），
         * H（QR_ECLEVEL_H，30%）；
         * */
        $level = QR_ECLEVEL_L;
        //表示生成图片大小，默认是3；
        $size = 8;
        //表示二维码周围边框空白区域间距值,默认是4；
        $margin = 4;
        //表示是否保存二维码并 显示,默认false
        $saveandprint=false;

        \QRcode::png($text, $outfile, $level, $size, $margin, $saveandprint);
        exit;
    }


}