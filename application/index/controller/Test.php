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
use app\admin\model\TaoOrder;
use think\Session;


use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;


class Test extends CommController {



    /*
     * @ todayCouponGoodsList() 今日优惠券列表
     * * */
    public function index()
    {
        $rel = 10;
        switch($rel){
            case 1:
                var_dump(1);
                break;
            case 2:
                var_dump(2);
                break;
            case 3:
                var_dump(3);
                break;
            case 4||5||6||7||8:
                var_dump(45678);
                break;
        }

       return $this->fetch('index/test');

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