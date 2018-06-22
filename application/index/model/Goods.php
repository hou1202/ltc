<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/10
 * Time: 13:48
 */
namespace app\index\model;


use think\Model;

class Goods extends Model
{
    public static $tableName = 'think_goods';

    //用户奖金，并四舍五入
    protected function getCommissionAttr($value){
        return $value = sprintf("%1\$.2f",$value*0.3);
    }

    protected function getTypeAttr($value){
        $type = ['淘宝' => '/static/index/images/t-logo-0.png','天猫' => '/static/index/images/t-logo-1.png'];
        return $type[$value];
    }

    //优惠券金额处理
    protected function getCouponMoneyAttr($value){
        $arrayPrice = array();
        preg_match_all('/\d+/',$value,$arrayPrice);
        if(count($arrayPrice[0]) > 1){
            if($arrayPrice[0][1] == '00'){
                return $arrayPrice[0][0];
            }else{
                return $arrayPrice[0][1];
            }
        }else{
            return $arrayPrice[0][0];
        }

    }

    /*
     * @getIndexPerfectCoupon() 首页精选好券20个
     * */
    public function getIndexPerfectCoupon(){
        return $this ->field('id,goods_id,name,banner,price,sales,type,coupon_money')
                    -> order('commission DESC')
                    -> limit(20)
                    ->select();
    }

    /*
     * @getPerfectCoupon() 精选好券列表
     * */
    public function getPerfectCoupon(){
        return $this ->field('id,goods_id,name,banner,price,type,sales,coupon_money')
            -> order('commission DESC')
            -> limit(1,200)
            ->select();
    }


    /*
     * @getIndexHotCoupon() 首页爆款好券20个
     * */
    public function getIndexHotCoupon(){
        return $this ->field('id,goods_id,name,banner,price,type,sales,coupon_money')
            -> order('sales DESC')
            -> limit(20)
            ->select();
    }

    /*
     * @getHotCoupon() 爆款好券列表
     * */
    public function getHotCoupon(){
        return $this ->field('id,goods_id,name,banner,price,sales,type,coupon_money')
            -> order('sales DESC')
            -> limit(1,200)
            ->select();
    }

    /*
     * @getIndexListCoupon() 首页推荐好券50个
     * */
    public function getIndexListCoupon($start=0,$end=10){
        return $this ->field('id,goods_id,name,banner,price,coupon_money,sales,type,sales')
            //-> order('sales DESC')
            -> limit("$start,$end")
            ->select();
    }

    /*
    * @getGoodsDetailsById()  产品详情
    *  $id     产品goods_id
    * */
    public function getGoodsDetailsById($id){
        return $this -> field('id,goods_id,name,banner,detail_url,price,type,tao_url,coupon_id,coupon_money,sales,commission,coupon_url,coupon_extend,check_effect')
                    ->where('goods_id',$id)
                    ->find();
    }


    /*
    * @getRecommendGoodsById()  产品详情页面下推荐产品
    *  $id     产品goods_id
    * */
    public function getRecommendGoodsById($id){
        $class = $this->getClassById($id);
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
                    -> where('class',$class['class'])
                    -> order('commission DESC')
                    -> limit(rand(1,50),8)
                    -> select();
    }
    //@ getClassById 辅助于getRecommendGoodsById
    protected function getClassById($id){
        return $this-> field('class') -> where('goods_id',$id)->find();
    }



    /*
     * @getGoodsListByTime() 今日优惠券列表
     * */
    public function getGoodsListByTime($cond='id',$sort='desc',$start=0,$end=20){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
                    -> where('coupon_start',date('Y-m-d',time()))
                    -> order("$cond $sort")
                    -> limit("$start,$end")
                    -> select();
    }

    /*
     * @getGoodsListByTmall() 天猫优惠券列表
     * */
    public function getGoodsListByTmall($cond='id',$sort='desc',$start=0,$end=20){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
                    -> where('type','天猫')
                    -> order("$cond $sort")
                    -> limit("$start,$end")
                    -> select();
    }


    /*
     * @getGoodsListByCouponMoney() 大额优惠券列表
     * */
    public function getGoodsListByCouponMoney($cond='id',$sort='desc',$start=0,$end=20){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
                    -> where('coupon_money','>',30)
                    -> order("$cond $sort")
                    -> limit("$start,$end")
                    -> select();
    }

    /*
     * @getGoodsListByNine() 9.9优惠券列表
     * */
    public function getGoodsListByNine($cond='id',$sort='desc',$start=0,$end=20){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
                    ->where('(price-coupon_money) < 10')
                    -> order("$cond $sort")
                    -> limit("$start,$end")
                    -> select();
    }

    /*
     * @getGoodsListByRatio() 高奖金优惠券列表
     * */
    public function getGoodsListByRatio($cond='id',$sort='desc',$start=0,$end=20){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
            -> where('ratio','>','30')
            -> order("$cond $sort")
            -> limit("$start,$end")
            -> select();
    }

    /*
     * @getCouponSquare() 广场
     * */
    public function getCouponSquare(){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
            -> limit(rand(1,500),500)
            -> select();
    }

    /*
     * @getCouponNav() 导航优惠券
     * */
    public function getCouponNav($nav,$order='id',$sort='desc',$start=0,$end=10){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
            -> where('class',$nav)
            -> order("$order $sort")
            -> limit("$start,$end")
            -> select();
    }

    /*
     *@ checkDelGoodsById  前端检测删除指定商品
     * @id      商品GOODS_ID
     * */
    public function checkDelGoodsById($id){
        return $this -> where('goods_id',$id) -> delete();
    }

    /*
     *@ checkGoodsById  前端检测指定商品记录
     * @id      商品GOODS_ID
     * */
    public function checkGoodsById($id){
        return $this -> where('goods_id',$id) -> setField('check_effect',time());
    }

    /*测试*/
    public function getTestGoods($start=0,$end=200){
        return $this -> field('id,goods_id,name,banner,price,type,sales,coupon_money')
            -> limit($start,$end)
            -> select();
    }




}