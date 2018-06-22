<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/8
 * Time: 15:12
 */
namespace app\common\model;


use think\Model;
use think\Db;

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

    //提现申请驳回通知信息
    static function NoticeCarryReject($id,$date,$money,$remark){
        if(empty($remark)){
            $remark='信息有误';
        }
        $data['title'] = '淘币兑换申请驳回通知';
        $data['content'] = '您已于 '.$date.' 发起淘币兑换申请，兑换数量：'.$money.' 淘币。未通过平台审核，申请信息已经驳回，兑换淘币已返还至您的账户。驳回原因:'.$remark.'。您可确认信息后再次提交！感谢您对折金券平台的信任！';
        $data['uid'] = $id;
        $data['create_time'] = time();
        return Db::table(static::$tableName) -> insert($data);
        //return $this -> save($data);
    }

    //提现申请通过通知信息
    static function NoticeCarrySuc($id,$date,$money,$alipay){
        $data['title'] = '淘币兑换申请通过通知';
        $data['content'] = '您已于 '.$date.' 发起淘币兑换申请，兑换数量：'.$money.' 淘币。已通过平台平台审核，并发放至你的申请账户：'.$alipay.'，请查阅！感谢您对折金券平台的信任！';
        $data['uid'] = $id;
        $data['create_time'] = time();
        return Db::table(static::$tableName) -> insert($data);
        //return $this -> save($data);
    }
}