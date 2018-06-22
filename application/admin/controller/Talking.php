<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/15
 * Time: 14:09
 */
namespace app\admin\controller;


use think\Controller;
use app\admin\model\Talking as TalkingModel;
use app\common\controller\ReturnJson;
use think\Validate;

class Talking extends Controller
{

    /*
      * @ talkList   反馈信息列表
      * */
    public function talkList(){
        $talk = new TalkingModel();
        $Count = $talk -> getTalkUser();
        $List = $talk -> getTalkForList();
        return $List -> items() ? view('talk_list',['List' => $List , 'Count' => $Count]) : ReturnJson::ReturnA('未查询到相关数据信息...');

    }

    /*
     * @ talkUpdate    修改反馈信息
     * */
    public function talkUpdate()
    {
        //修改提交信息
        if ($this->request->isPost()) {
            $data = $this->request->Post();
            if (isset($data['id']) && empty($data['id'])) {
                return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/talking/talkList');
            }
            $rule = [
                'reply' => 'require'
            ];
            $msg = [
                'reply.require' => '反馈回复信息不得为空...'
            ];
            $validate = new Validate($rule,$msg);
            if ($validate->check($data)) {
                $talk = new TalkingModel();
                return $talk -> updateTalkById($data,$data['id']) ? ReturnJson::ReturnJ('数据更新成功...', 'success', '/talking/talkList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');
            }
            return ReturnJson::ReturnJ($validate->getError(), 'false');
        }

        //获取、展示修改信息
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $this->request->get('id');
            $talk = new TalkingModel();
            $getOne = $talk->getOneTalkById($id);
            return $getOne ? view('talk_update', ['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的数据信息...", "#/talking/talk_list");
        } else {
            ReturnJson::ReturnA("无效的修改操作...");
        }
    }


    /*
   * @ talkDel    删除反馈信息
   * */
    public function talkDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $talk = new TalkingModel();
            $data['isdel'] = time();
            return $talk -> updateTalkById($data,$id) ? ReturnJson::ReturnJ("已成功删除此数据信息...") : ReturnJson::ReturnJ($talk -> getError(),"false");
        }
        return ReturnJson::ReturnJ("非法的数据提交信息!","false");
    }


    /*
   * @ talkSearch    搜索反馈信息
   * */
    public function talkSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $talk = new TalkingModel();
                $List = $talk->getSearchTalkByKeyword($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('talk_list' , ['List' => $List , 'Count' => $talk->getCountSearchTalkByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }
}