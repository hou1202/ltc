<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/8
 * Time: 15:12
 */
namespace app\index\model;


use think\Model;

class Notice extends Model
{
    public static $tableName = 'think_notice';

    //开启时间自动写入
    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }


    /*
     * @getUserNoticeByUid() 获取用户通知信息
     * $id 获取信息用户ID
     * */
    public function getUserNoticeByUid($id){
        return $this -> field('id,title,content,create_time')
                     -> where('uid',$id)
                     -> Order('create_time DESC')
                     -> select();
    }

    /*
     * @ CarryNoticeInfo()  创建提现提示信息
     *  $id     提现用户ID
     *  $money  提现金额
     * */
    public function CarryNoticeInfo($id,$money){
        $data['title'] = '淘币兑换申请通知';
        $data['content'] = '您已于 '.date("Y/m/d").' 发起淘币兑换申请，兑换数量：'.$money.' 淘币。小二正在积极处理中（24小时内），请您耐心等待！感谢您对折金券平台的信任！';
        $data['uid'] = $id;
        return $this -> save($data);
    }
}