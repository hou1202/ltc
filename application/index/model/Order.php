<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/2/5
 * Time: 14:19
 */

namespace app\index\model;




use think\Model;

class Order extends Model
{
    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:m:s',$value);
    }

    //取值时间显示
    protected function getUpdateTimeAttr($value){
        return date('Y-m-d H:m:s',$value);
    }

    /*
     * @saveOrderDate       保存用户提交订单数据
     * $data                保存的数据信息
     * @return
     * */
    public function saveOrderDate($data){
        return $this -> allowField(true) -> save($data);
    }

    /*
     * @getUserOrderById       获取指定用户订单信息
     * $id          用户ID
     * $type        订单状态
     * */
    public function getUserOrderById($id,$type){
        return $this -> field('id,order_id,create_time,update_time')
                    -> where('user_id',$id)
                    -> where('state',$type)
                    -> select();

    }

    /*
     * @getOrderInfoByOrderId       通过订单ID判断订单是否存在
     * $orderId                     订单ID
     * */
    public function getOrderInfoByOrderId($orderId){
        return $this-> field('id,order_id') -> where('order_id',$orderId) -> find();
    }
}