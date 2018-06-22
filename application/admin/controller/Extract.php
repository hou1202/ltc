<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/15
 * Time: 16:19
 */

namespace app\admin\controller;

use app\common\controller\CommController;
use app\common\controller\PublicFunction;
use app\common\controller\ReturnJson;
use app\admin\model\Extract as ExtractModel;
use app\admin\model\User;

class Extract extends CommController
{

    /*
     * @ extractList   提币列表
     * */
    public function extractList(){
        $extract = new ExtractModel();
        $extractCount = $extract -> getCountExtract();
        $extractList = $extract -> getExtractForList();

        return $extractList -> items() ? view('extract_list',['List' => $extractList , 'Count' => $extractCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    /*
     * @ extractUpdate    操作提币信息
     * */
    public function extractUpdate(){
        //修改提交信息
        if($this -> request -> isPost()){
            $data = $this -> request -> Post();
            if(isset($data['id']) && empty($data['id'])){
                return ReturnJson::ReturnJ('无效的数据操作...','false','/extract/extractList');
            }
            $extract = new ExtractModel();
            if(isset($data['state']) && $data['state'] == 2){
                //驳回审核
                $user = new User();
                $oneExtract = $getOne = $extract -> getOneExtractInfoById($data['id']);
                //返还用户提币数量至可用资产，并生成资产记录
                $user -> updateAssetById($oneExtract['user_id'],$oneExtract['number']);
                PublicFunction::SetCapitalLog($oneExtract['user_id'],$oneExtract['number'],11);
            }

            $updateExtract = $extract -> updateExtractInfoById($data['id'],$data);
            if($updateExtract){
                return ReturnJson::ReturnJ('数据更新成功...','success','/extract/extractList');
            }else{
                return ReturnJson::ReturnJ('数据更新失败，请重新操作...','false');
            }

        }

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $extract = new ExtractModel();
            $getOne = $extract -> getOneExtractInfoById($id);
            return $getOne ?  view('extract_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的用户信息...","#/extract/extract_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }

    /*
    * @ extractSearch    搜索提币信息
    * */
    public function extractSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $extract = new ExtractModel();
                $List = $extract->getSearchExtractByKeyword($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('extract_list' , ['List' => $List , 'Count' => $extract->getCountSearchExtractByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }

    /*
    * @ extractSort    按排序信息查看提币信息
    * */
    public function extractSort(){
        //获取、展示修改信息
        if(isset($_GET['key']) && !empty($_GET['key'])){
            $data = $this -> request -> get();
            $extract = new ExtractModel();
            $extractCount = $extract -> getCountExtract();
            $extractList = $extract -> getExtractForList($data['key']);
            return $extractList -> items() ? view('extract_list',['List' => $extractList , 'Count' => $extractCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
        }else{
            ReturnJson::ReturnA("无效的操作...");
        }

    }













}