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




use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;


class Test extends CommController {



    /*
     * @ todayCouponGoodsList() 今日优惠券列表
     * * */
    public function index()
    {

        $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        var_dump($url);die;

        /* array(
             '1438156396' => array(
                 array(1,array('Class','Func'), array(), true),
             )
         );*/
        /*
         * 说明:
         *  1438156396 时间戳
         *  array(1,array('Class','Func'), array(), true)
         *  参数依次表示:
         *      执行时间间隔,
         *      回调函数,
         *      传递给回调函数的参数,
         *      是否持久化(ture则一直保存在数据中,否则执行一次后删除)
         * */


        //return $this -> fetch('index/test');
    }


    public function runTime(){

        TimeTask::dellAll();

        //TimeTask::add( 1, array('DoJob','job'), array(),true);

        TimeTask::add( 3, array('DoJob','job'),array('a'=>1), false);

        echo "Time start: ".time()."\n";

        TimeTask::run();

         while(1)
         {
             sleep(1);
             pcntl_signal_dispatch();
         }
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