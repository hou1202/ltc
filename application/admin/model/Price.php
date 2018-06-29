<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/25
 * Time: 15:48
 */

namespace app\admin\model;
use think\Model;

class Price extends Model
{

    public static $tableName = 'think_price';

    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }


    /*
   * @getNowPrice 获取当前LTC价格
   * */
    public function getNowPrice(){
        return $this -> field('id,price,create_time')
                    -> order('id DESC')
                    ->find();
    }

    /*
    * @getPlanForList  LTC价格列表显示
    * */
    public function getPriceForList(){
        return $this -> field('id,price,create_time')
            -> order('id DESC')
            -> paginate(10,false,['path' => '/admin/main#/setup/setLtcPriceList' ]);
    }

    /*
     * @ getCountPrice   统计LTC价格数量
     * */
    public function getCountPrice(){
        return $this -> count('id');
    }

    /*
   * @insertNewPrice  插入新LTC价格
   * */
    public function insertNewPrice($data){
        return $this -> allowField(true)
            -> save($data);
    }


}