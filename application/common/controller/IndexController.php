<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2017/12/27
 * Time: 13:58
 */

namespace app\common\controller;

use app\common\model\ApiUserToken;
use think\Cache;
use think\Controller;
use think\Db;
use think\Session;
use think\exception\HttpResponseException;
use think\Response;
use think\response\Redirect;

class IndexController extends BaseController
{
    const FAIL_CODE = 0;      //失败code
    const SUCCESS_CODE = 1;   //成功code

    protected $userId;


    /**
     * @var 键=》查权限  controller/action
     */
    private $mPermissionIndex;

    /**
     * @var array 1判断登录
     */
    protected static $sPermissionArr;

    /**
     * 初始操作
     */
    protected function init(){
       /* $userId = (int)Session::get('userId');
        $this->userId = $userId;

        $assignData = ['userData' => [], 'isLogin' => 0];
        $cartCount = 0;
        if($userId > 0) {
            $userData = Db::table('think_user')->field('id,user_name,portrait,invite,iphone')->where('id=' . $userId)->find();
            if(!empty($userData)) {
                $assignData = ['userData' => $userData, 'isLogin' => 1];
            }
            //$cartCount = Db::table('hui_cart')->where(['user_id' => $userId, 'is_del' => 0])->count();
        }


        $this->mPermissionIndex = $this->request->controller().'/'.$this->request->action();
        $permission = static::$sPermissionArr[$this->mPermissionIndex];
        if (!empty($permission)) {
            // 判断登录
            ($permission & 1) && $this->checkLogin();
            // 浏览记录
            //($permission & 2) && $this->getBrowse();
        }

        $this->assign($assignData);
        $this->assign(['cartCount' => $cartCount]);*/
    }

    public function checkLogin() {
        if($this->userId<=0){
            $response = $this->request->isAjax() || $this->request->isPost() ? Response::create(['code' => self::FAIL_CODE, 'msg' => '用户信息失效，请重新登录'], 'json') : new Redirect('/index/Login/attestation');
            throw new HttpResponseException($response);
        }
    }

    public function getBrowse() {
        $browse = Db::table('hui_browse')->alias('b')->field('g.id,g.poster,g.name,g.price,g.type,g.old_price,g.reserve_price,g.sales_volume')
            ->join('hui_goods g', 'g.id = b.goods_id')
            ->where(['b.user_id' => $this->userId])->order('b.last_browse_time DESC')->limit('0,3')->select();
        $this->assign(['browse' => $browse]);
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

    /**
     * 成功  json类型响应数据
     * @param $msg
     * @param array $data
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function jsonSuccess($msg, $data=[]){
        //$result = empty($data) ? ['code'=>self::SUCCESS_CODE, 'msg'=>$msg] : ['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data];
        $result = ['code'=>self::SUCCESS_CODE, 'msg'=>$msg, 'data'=>$data];
        return $this->json($result);
    }

    protected function setHeadTitle($title){
        $this->assign(['headTitle'=>$title]);
    }
}