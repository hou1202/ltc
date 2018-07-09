<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 16:02
 */

namespace app\index\controller;



use app\common\controller\CommController;

use think\Loader;
use think\Db;
use think\Session;

use app\index\common\controller\TimeTask;
use app\index\common\controller\DoJob;



use app\common\controller\PublicFunction;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;


class Test extends CommController {


    const GRADE_ONE = 15;            //一级返利率
    const GRADE_TWO = 10;            //二级返利率
    const GRADE_THREE = 5;           //三级返利率
    const GRADE_FOUR = 2;            //四级返利率
    const GRADE_FIVE = 2;            //五级返利率
    const GRADE_SIX = 2;             //六级返利率
    const GRADE_SEVEN = 2;           //七级返利率
    const GRADE_EIGHT = 2;           //八级返利率
    const GRADE_NINE = 2;            //九级返利率
    const GRADE_TEN = 2;             //十级返利率

    /*
     * @ todayCouponGoodsList() 今日优惠券列表
     * * */
    public function index()
    {

        $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        var_dump($url);die;


        //return $this -> fetch('index/test');
    }


    public function runTime()
    {
        /*** 这里写计划任务列表集 START ***/
        /*
         * 计算锁仓收益
         * 计算分享收益
         * */

        //按用户分组获取进行中的锁仓
        $lockBreak = Db::name('lock')->field('id,user_id')->where('state', 0)->group('user_id')->select();
        for ($i = 0; $i < count($lockBreak); $i++) {
            //循环获取单个用户的所有有效锁仓
            $user_id = $lockBreak[$i]['user_id'];
            $lockPlan = Db::name('lock')->field('id,user_id,number,lock_time,lock_ratio,create_time')
                ->where('state', 0)
                ->where('user_id', $user_id)
                ->select();

            foreach ($lockPlan as $plan) {
                //计算锁仓用户收益
                $profit = $plan['number'] * $plan['lock_ratio'] * 0.01;
                //增加可用资产
                Db::name('user')->where('id', $plan['user_id'])->setInc('asset_avali', $profit);
                //生成资产记录
                PublicFunction::SetCapitalLog($plan['user_id'], $profit, 3);

                //计算分销收益
                $relation = Db::name('friend')->field('id,user_id,p_id,grade')
                    ->where('user_id', $plan['user_id'])
                    ->select();
                foreach ($relation as $rel) {
                    //增加分享收益可用资产
                    switch ($rel['grade']) {
                        case 1:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_ONE);
                            break;
                        case 2:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_TWO);
                            break;
                        case 3:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_THREE);
                            break;
                        case 4:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_FOUR);
                            break;
                        case 5:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_FIVE);
                            break;
                        case 6:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_SIX);
                            break;
                        case 7:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_SEVEN);
                            break;
                        case 8:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_EIGHT);
                            break;
                        case 9:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_NINE);
                            break;
                        case 10:
                            $this->setShareProfit($rel['p_id'], $profit, self::GRADE_TEN);
                            break;
                    }
                }

                //进行到第几天了
                $conductDay = intval((time() - strtotime($plan['create_time'])) / 86400) + 1;
                //如果到期，改变锁仓状态
                if ($conductDay == $plan['lock_time']) {
                    Db::name('lock')->where('id', $plan['id'])->setField('state', 1);
                }
            }
        }
    }

    /*
     * @setShareProfit           计算分享收益并生成记录
     * $id                          用户ID
     * $profit                      被邀请人锁仓收益
     * $ratio                       收益利率
     * */
    private function setShareProfit($id,$profit,$ratio){
        $shareProfit = $profit*$ratio*0.01;
        //增加可用资产
        Db::name('user')->where('id',$id) -> setInc('asset_avali', $shareProfit);
        //生成资产记录
        PublicFunction::SetCapitalLog($id,$shareProfit,4);
    }










    public function img(){
        Loader::import('phpqrcode.phpqrcode');
        $value = $_GET['url'];//二维码内容
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        //生成二维码图片

        \QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
        $logo = 'web.ltc.com/static/index/images/';//准备好的logo图片
        $QR = 'qrcode.png';//已经生成的原始二维码图
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        Header("Content-type: image/png");
        ImagePng($QR);
    }





    public function wxShare(){
        $appId='wx4f12e20059703cc2';
        $secert = '710640b7ce6c0db89426b5078e6ca86d';
        $timestamp = time();
        $nonceStr = '1r6g5s6gds3fg2fg';
        $token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
            .$appId.'&secret='.$secert.'';
        //Session::delete('save_token');
        //var_dump(Session::get('save_token'));//die;
        if(Session::has('save_token') && !empty( Session::get('save_token'))){
            $session = Session::get('save_token');
            if(($session[1]+7200) < time()){
                //access_token超时,重新获取，并SESSION保存
                $access_token = file_get_contents($token);
                Session::set('save_token',[$access_token['access_token'],time()]);
                var_dump(1);
            }
        }else{
            //Session不存在或为空，获取，并SESSION保存
            $access_token = json_decode(file_get_contents($token),true);
            var_dump(2);
            Session::set('save_token',[$access_token['access_token'],time()]);

            //var_dump(3);die;
        }
        $access = Session::get('save_token');
        //获取jsapi_ticket
        $ticket = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='
            .$access[0].'&type=jsapi';
        $jsapi_ticket = json_decode(file_get_contents($ticket),true);

        $signature = $jsapi_ticket['ticket'];
        //var_dump($jsapi_ticket);
        //var_dump($signature);die;
        $return_relust = [
            'appId' => $appId, // 必填，公众号的唯一标识
            'timestamp' => $timestamp, // 必填，生成签名的时间戳
            'nonceStr' => $nonceStr, // 必填，生成签名的随机串
            'signature' => $signature,// 必填，签
        ];
        echo json_encode($return_relust);

    }

    public function visitRecordIp(){
        if($this->request->isPost()){

            $data=$this->request->post();
            $request = $this->request->instance();
            $data['ip'] = $request -> ip();
            $data['create_time'] = time();
            $visit = Db::name('visit')->insert($data);
            if($visit){
                return $this->jsonSuccess(true);
            }else{
                return $this->jsonFail(false);
            }

        }

    }

    public function inserExcel()
    {
        //引入文件（把扩展文件放入vendor目录下，路径自行修改）
        Loader::import('PHPExcel.PHPExcel');
        Loader::import('PHPExcel.PHPExcel.PHPExcel_IOFactory');
        Loader::import('PHPExcel.PHPExcel.PHPExcel_Cell');

        //获取表单上传文件
        $file = request()->file('excel');
        $info = $file->validate(['ext' => 'xlsx,xls'])->move(ROOT_PATH . 'public' . DS . 'upload' . DS . 'TaoBao');

        //数据为空返回错误
        if(empty($info)){

            return $this->jsonFail('导入数据失败...');
        }

        //获取文件名
        $exclePath = $info->getSaveName();
        //上传文件的地址
        $filename = ROOT_PATH . 'public' . DS . 'upload' . DS . 'TaoBao'. DS . $exclePath;

        //判断截取文件
        $extension = strtolower( pathinfo($filename, PATHINFO_EXTENSION) );

        //区分上传文件格式
        if($extension == 'xlsx') {
            $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        }else if($extension == 'xls'){
            $objReader =\PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($filename, $encode = 'utf-8');
        }else{
            return $this->jsonFail('上传EXCEL文件类型有误...');
        }

        //转换为数组格式
        $excel_array = $objPHPExcel->getsheet(0)->toArray();
        //删除第一个数组(标题);
        array_shift($excel_array);
        //数据逐条写入数据库
        $data = [];
        foreach($excel_array as $k=>$v) {

            $data['build_time'] = $v[0];
            $data['click_time'] = $v[1];
            $data['name'] = $v[2];
            $data['goods_id'] = $v[3];
            $data['wangwang'] = $v[4];
            $data['seller_name'] = $v[5];
            $data['num'] = $v[6];
            $data['price'] = $v[7];
            $data['order_state'] = $v[8];
            $data['type'] = $v[9];
            $data['ratio_collect'] = $v[10];
            $data['ratio_divided'] = $v[11];
            $data['real_price'] = $v[12];
            $data['est_effect'] = $v[13];
            $data['settle_price'] = $v[14];
            $data['est_collect'] = $v[15];
            $data['settle_time'] = $v[16];
            $data['ratio_commission'] = $v[17];
            $data['commission'] = $v[18];
            $data['ratio_subsidy'] = $v[19];
            $data['subsidy'] = $v[20];
            $data['type_subsidy'] = $v[21];
            $data['trade_plat'] = $v[22];
            $data['third'] = $v[23];
            $data['order_id'] = $v[24];
            $data['class'] = $v[25];
            $data['source_id'] = $v[26];
            $data['source_name'] = $v[27];
            $data['zone_id'] = $v[28];
            $data['zone_name'] = $v[29];

            $taoOrder = new TaoOrder();
            if( $taoOrder -> getTaoOrderInfoById($data['order_id'])){
                //该淘订单已经存在
                $taoOrder -> updateTaoOrder($data['order_id'],$data);
            }else{
                //该淘订单尚未存在
                $taoOrder -> insertTaoOrder($data);
            }
        }

        /*
        //批量插入
        foreach($excel_array as $k=>$v) {
            if(empty(Db::name('excel_shop')->where(['goods_id'=>$v[0]])->value('name'))){
                $data['build_time'] = $v[0];
                $data['click_time'] = $v[1];
                $data['name'] = $v[2];
            }
        }
        Db::name('excel_shop')->insertAll($city); //批量插入数据
        */

        return $this->jsonSuccess('导入数据成功...');
    }
}