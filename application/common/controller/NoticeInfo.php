<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/3
 * Time: 17:30
 */
namespace app\common\controller;
use think\Db;

class NoticeInfo
{
    protected static function setInfo($title,$content,$id){
        $noticeInfo['title'] = $title;
        $noticeInfo['content'] = $content;
        $noticeInfo['uid'] = $id;
        $noticeInfo['create_time'] = time();
        $noticeInfo['update_time'] = time();
        return Db::table('think_notice') -> insert($noticeInfo);
    }

    /*
     * 淘币兑换通知信息
     * */
    static function CarryNoticeInfo($id,$money){
        $title = '淘币兑换申请通知';
        $content = '您已于 '.date("Y/m/d").' 发起淘币兑换申请，兑换数量：'.$money.' 淘币。小二正在积极处理中（24小时内），请您耐心等待！感谢您对折金券平台的信任！';
        $uid = $id;
        return NoticeInfo::setInfo($title,$content,$uid);
    }

    /*
     * 订单审核通过，并发送奖金通知信息
     * */
    static function BonusNoticeInfo($id,$order_id,$money){
        $title = '奖励金发放通知';
        $content = '您所提交的购物订单 '.$order_id.',平台已经审核通过，并将此次购物所得奖励金 ￥'.$money.'，发放至您的平台账户，请您查阅！感谢您对折金券平台的信任！';
        $uid = $id;
        return NoticeInfo::setInfo($title,$content,$uid);
    }

    /*
     * 邀请人订单审核通过，并发放返利金通知
     * */
    static function RebateNoticeInfo($id,$money){
        $title = '奖励金发放通知';
        $content = '您所邀请好友完成购物订单提交，并审核通过。此次购物您将得到平台奖励金 ￥'.$money.'，并发放至您的平台账户，请您查阅！感谢您对折金券平台的信任！';
        $uid = $id;
        return NoticeInfo::setInfo($title,$content,$uid);
    }

    /*
     * 订单审核驳回通知
     * */
    static function RejectNoticeInfo($id,$order_id){
        $title = '订单审核驳回通知';
        $content = '您所提交的购物订单 '.$order_id.'，经平台审查，并未发现此订单号，请您确认此产品是否经由平台链接购买或订单号是否提交有误！如还有疑问，可联系平台客服帮您解决！感谢您对折金券平台的信任！';
        $uid = $id;
        return NoticeInfo::setInfo($title,$content,$uid);
    }

}