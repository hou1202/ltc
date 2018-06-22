<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/15
 * Time: 14:09
 */
namespace app\admin\controller;


use think\Controller;
use app\admin\model\Notice as NoticeModel;
use app\common\controller\ReturnJson;
use app\admin\validate\NoticeUpdateValidate;

class Notice extends Controller
{

    /*
      * @ noticeList   通知信息列表
      * */
    public function noticeList(){
        $notice = new NoticeModel();
        $Count = $notice -> getNoticeUser();
        $List = $notice -> getNoticeForList();
        return $List -> items() ? view('notice_list',['List' => $List , 'Count' => $Count]) : ReturnJson::ReturnA('未查询到相关数据信息...');

    }

    /*
     * @ noticeUpdate    修改通知信息
     * */
    public function noticeUpdate()
    {
        //修改提交信息
        if ($this->request->isPost()) {
            $data = $this->request->Post();
            if (isset($data['id']) && empty($data['id'])) {
                return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/notice/noticeList');
            }
            $validate = new NoticeUpdateValidate();
            if ($validate->check($data)) {
                $notice = new NoticeModel();
                return $notice -> updateNoticeById($data,$data['id']) ? ReturnJson::ReturnJ('数据更新成功...', 'success', '/notice/noticeList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');
            }
            return ReturnJson::ReturnJ($validate->getError(), 'false');
        }

        //获取、展示修改信息
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $this->request->get('id');
            $notice = new NoticeModel();
            $getOne = $notice->getOneNoticeById($id);
            return $getOne ? view('notice_update', ['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的数据信息...", "#/notice/notice_list");
        } else {
            ReturnJson::ReturnA("无效的修改操作...");
        }
    }


    /*
   * @ noticeDel    删除通知信息
   * */
    public function noticeDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $notice = new NoticeModel();
            return $notice -> delNoticeById($id) ? ReturnJson::ReturnJ("已成功删除此数据信息...") : ReturnJson::ReturnJ($notice -> getError(),"false");
        }
        return ReturnJson::ReturnJ("非法的数据提交信息!","false");
    }


    /*
   * @ noticeSearch    搜索通知信息
   * */
    public function noticeSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $notice = new NoticeModel();
                $List = $notice->getSearchNoticeByKeyword($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('notice_list' , ['List' => $List , 'Count' => $notice->getCountSearchNoticeByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }
}