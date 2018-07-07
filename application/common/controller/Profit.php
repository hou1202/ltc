<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/7
 * Time: 16:29
 */

namespace app\common\controller;
use app\admin\model\Lock;
use app\admin\model\User;
use app\common\model\Friend;

class Profit
{

    /*
     * @setLockProfit           计算锁仓收益
     * */
    public function setLockProfit(){
        $lock = new Lock();
        //按用户分组获取锁仓
        $lockBreak = $lock -> getLockGroupUser();
        for($i=0;$i<count($lockBreak);$i++){
            //循环获取单个用户的所有有效锁仓
            $user_id = $lockBreak[$i]['user_id'];
            $lockPlan = $lock -> getLockByUser($user_id);

            foreach($lockPlan as $plan){
                //计算锁仓用户收益
                $profit = $plan['number']*$plan['lock_ratio']*0.01;
                $user = new User();
                //增加可用资产
                $user -> updateAssetById($plan['user_id'],$profit);
                //生成资产记录
                PublicFunction::SetCapitalLog($plan['user_id'],$profit,3);

                //计算分销收益
                $friend = new Friend();
                $relation = $friend -> getFriendByUserId($plan['user_id']);
                foreach($relation as $rel){
                    //增加分享收益可用资产
                    switch($rel['grade']){
                        case 1:
                            $this -> setShareProfit($rel['p_id'],$profit,15);
                            break;
                        case 2:
                            $this -> setShareProfit($rel['p_id'],$profit,10);
                            break;
                        case 3:
                            $this -> setShareProfit($rel['p_id'],$profit,5);
                            break;
                        case 4:
                            $this -> setShareProfit($rel['p_id'],$profit,2);
                            break;
                        case 5:
                            $this -> setShareProfit($rel['p_id'],$profit,2);
                            break;
                        case 6:
                            $this -> setShareProfit($rel['p_id'],$profit,2);
                            break;
                        case 7:
                            $this -> setShareProfit($rel['p_id'],$profit,2);
                            break;
                        case 8:
                            $this -> setShareProfit($rel['p_id'],$profit,2);
                            break;
                        case 9:
                            $this -> setShareProfit($rel['p_id'],$profit,2);
                            break;
                        case 10:
                            $this -> setShareProfit($rel['p_id'],$profit,2);
                            break;
                    }
                }

                //进行到第几天了
                $conductDay=intval((time()-strtotime($plan['create_time']))/86400)+1;
                //如果到期，改变锁仓状态
                if($conductDay == $plan['lock_time']){
                    $lock -> setLockState($plan['id']);
                }
            }
            //break;

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
        $user = new User();
        //增加可用资产
        $asset = $user -> updateAssetById($id,$shareProfit);
        //生成资产记录
        $log = PublicFunction::SetCapitalLog($id,$shareProfit,4);
        return $asset && $log ? true : false;
    }

}