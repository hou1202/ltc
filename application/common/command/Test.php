<?php

/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/7
 * Time: 18:41
 */
namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

use think\Db;

class Test extends Command
{

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

    protected function configure(){
        $this->setName('Test')->setDescription("Here is AutoRun Test");
    }

    protected function execute(Input $input, Output $output){


        /*** 这里写计划任务列表集 START ***/
        /*
         * 计算锁仓收益
         * 计算分享收益
         * */

        //按用户分组获取进行中的锁仓
        $lockBreak = Db::name('lock')->field('id,user_id') -> where('state',0) -> group('user_id') -> select();
        for($i=0;$i<count($lockBreak);$i++){
            //循环获取单个用户的所有有效锁仓
            $user_id = $lockBreak[$i]['user_id'];
            $lockPlan = Db::name('lock')->field('id,user_id,number,lock_time,lock_ratio,create_time')
                                        ->where('state',0)
                                        ->where('user_id',$user_id)
                                        ->select();

            foreach($lockPlan as $plan){
                //计算锁仓用户收益
                $profit = $plan['number']*$plan['lock_ratio']*0.01;
                //增加可用资产
                Db::name('user')->where('id',$plan['user_id']) -> setInc('asset_avali', $profit);
                //生成资产记录
                $this -> capitalLog($plan['user_id'],$profit,3);

                //计算分销收益
                $relation = Db::name('friend')->field('id,user_id,p_id,grade')
                                                -> where('user_id',$plan['user_id'])
                                                -> select();
                foreach($relation as $rel){
                    //增加分享收益可用资产
                    switch($rel['grade']){
                        case 1:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_ONE);
                            break;
                        case 2:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_TWO);
                            break;
                        case 3:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_THREE);
                            break;
                        case 4:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_FOUR);
                            break;
                        case 5:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_FIVE);
                            break;
                        case 6:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_SIX);
                            break;
                        case 7:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_SEVEN);
                            break;
                        case 8:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_EIGHT);
                            break;
                        case 9:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_NINE);
                            break;
                        case 10:
                            $this -> setShareProfit($rel['p_id'],$profit,self::GRADE_TEN);
                            break;
                    }
                }

                //进行到第几天了
                $conductDay=intval((time()-strtotime($plan['create_time']))/86400)+1;
                //如果到期，改变锁仓状态
                if($conductDay >= $plan['lock_time']){
                    Db::name('lock')->where('id',$plan['id']) -> setField('state',1);
                }
            }

        }

        /*** 这里写计划任务列表集 END ***/

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
        $this->capitalLog($id,$shareProfit,4);
    }


    /*
     * @SetCapitalLog       生成资金变更记录
     * $id                  用户ID
     * $capital             变动资金
     * $way                 变动方式
     * */
    private function capitalLog($id,$capital,$way=1){
        $data['user_id'] = $id;
        $data['capital'] = abs($capital);
        $data['way'] = $way;
        $data['create_time'] = time();
        $data['update_time'] = time();
        Db::name('capital_log') -> insertGetId($data);
    }

}