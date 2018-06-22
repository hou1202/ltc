<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/3
 * Time: 17:53
 */
namespace app\common\controller;


use think\Controller;
use think\Response;

class CommController extends Controller
{

    const FAIL_CODE = 0;      //失败code
    const SUCCESS_CODE = 1;   //成功code

    protected $userId;



    /**
     * @param $data array
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    protected function json($data=[]){
        return Response::create($data,'json');
    }


    /**
     * 成功  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonSuccess($msg, $data=[]){
        $result = empty($data) ? ['code'=>self::SUCCESS_CODE, 'msg'=>$msg] : ['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data];
        //$result = ['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data];
        return $this->json($result);
    }


    /**
     * 失败  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonFail($msg, $data=[]){
        $result = empty($data) ? ['code'=>self::FAIL_CODE, 'msg'=>$msg] : ['code'=>self::FAIL_CODE, 'msg'=>$msg, 'data'=>$data];
        return $this->json($result);
    }
}