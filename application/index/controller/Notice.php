<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/8
 * Time: 13:41
 */
namespace app\index\controller;


use app\common\controller\CommController;
use app\index\model\Notice as NoticeModel;
use think\Hook;
use think\Cookie;
use app\index\model\User;
use app\common\controller\ReturnJson;

class Notice extends CommController
{
    public function notice(){
        Hook::listen('CheckAuth', $params);
        $uid = Cookie::get('user');
        $user = new User();
        $result = $user -> getUserInfoByMobile($uid);
        if (!$result) {
            return ReturnJson::ReturnA("未查找到你们账户信息，请重新确认账户，或注册...");
        }
        $notice = new NoticeModel();
        $notice_result = $notice -> getUserNoticeByUid($result['id']);
        return $this->fetch('personal/notice',['Notice'=>$notice_result]);
    }
}