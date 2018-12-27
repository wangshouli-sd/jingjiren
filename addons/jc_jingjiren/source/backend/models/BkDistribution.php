<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/7
 * Time: 14:02
 */

namespace backend\models;


use common\models\Distribution;
use common\models\SysParams;
use frontend\models\FrWallertlog;

class BkDistribution extends Distribution
{
    #打款接口 $id是流程id
    public function sendmoney($id, $uniacid)
    {
        $sysMd = new SysParams();
        $walletMd = new BkWallet();
        $taskMd = new BkTask();
        $projectMd = new BkProject();
        $processMd = new BkProcess();
        $json_success = [
            'status' => [
                'state' => 'success',
                'msg' => '成功',
            ]
        ];
        $processinfo = $processMd->One($id);
        $task_id = $processinfo['task_id'];
        $taskinfo = $taskMd->fetchOne(['id' => $task_id]);
        $projectinfo = $projectMd->fetchOne($taskinfo['project_id']);

        $member_id = $taskinfo['send_id'];
        $money = $taskinfo['total_price'];
        $process_percent = $processinfo['price'] / 100;

        $var = $sysMd->fetchOne($uniacid, 'is_sale');//系统开启的几级分销
        $percent = $this->getpercent($uniacid);//系统设置的分销比例

        $main_money = $money * ($projectinfo['vt_price_percent'] / 100) * $process_percent;//当前经纪人所获得佣金

        $one_money = $money * ($percent[0] / 100) * $process_percent;//其一级分销所获得佣金
        $two_money = $money * ($percent[1] / 100) * $process_percent;//其二级分销所获得佣金
        switch ($var['var']) {
            case '0';
                $name = 'main';
                $re = $walletMd->plusMoney($member_id, $task_id,$id, $main_money, $name);
                break;

            case '1';
                $re = $this->sendmoney_one($member_id, $task_id,$id, $main_money, $one_money);
                break;

            case '2';
                $re = $this->sendmoney_two($member_id, $task_id,$id, $main_money, $one_money, $two_money);
                break;
        }
        if ($re == true) {
            return $json_success;
        } else if ($re == false) {
            return $json_success;
        }
    }

    //一级分销发放佣金 必选参数是用户的$member_id
    public function sendmoney_one($member_id, $task_id, $id,$main_money, $one_money)
    {
        $walletMd = new BkWallet();
        $where = [
            'main_id' => $member_id
        ];
        $list = $this->one($where);
        $name = 'main';
        $name_one = 'son';
        if ($list['one_level'] == 0) {
            return $walletMd->plusMoney($member_id, $task_id,$id, $main_money, $name);
        } elseif ($list['one_level'] != 0) {
            $main = $walletMd->plusMoney($member_id, $task_id, $id,$main_money, $name);
            if ($main == true) {
                return $walletMd->plusMoney($list['one_level'], $task_id, $id,$one_money, $name_one);
            } else {
                return false;
            }
        }

    }

    //二级级分销发放佣金 必选参数是当前用户的id
    public function sendmoney_two($member_id, $task_id,$id, $main_money, $one_money, $two_money)
    {
        $walletMd = new BkWallet();
        $where = [
            'main_id' => $member_id
        ];
        $list = $this->one($where);
        $name = 'main';
        $name_one = 'son';
        $name_two = 'grand_son';
        if (empty($list)) {
            return $walletMd->plusMoney($member_id, $task_id, $id,$main_money, $name);
        }
        $main = $walletMd->plusMoney($member_id, $task_id,$id, $main_money, $name);
        if ($main == true) {
            if ($list['one_level'] !== '0') {
                $walletMd->plusMoney($list['one_level'], $task_id,$id, $one_money, $name_one);
            }
            if ($list['two_level'] !== '0') {
                $walletMd->plusMoney($list['two_level'], $task_id,$id, $two_money, $name_two);
            }
            return true;
        } else {
            return false;
        }
    }

    //获取佣金比例
    public function getpercent($uniacid)
    {
        $sysMd = new SysParams();
        $percent_str = $sysMd->fetchOne($uniacid, 'price_percent');
        $percent = explode(',', $percent_str['var']);
        return $percent;

    }

    #通过任务id返回任务及分销详情
    public function taskinfo($task_id, $uniacid)
    {
        $taskMd = new BkTask();
        $projectMd = new BkProject();
        $sysMd = new SysParams();
        $walletlogMd = new FrWallertlog();
        $memberMd = new BkMember();

        $taskinfo = $taskMd->fetchOne(['id' => $task_id]);
        $projectinfo = $projectMd->fetchOne($taskinfo['project_id']);
        $send_id = $taskinfo['send_id'];

        $fenxiao = $this->one(['main_id' => $send_id]);
        $one_id = $fenxiao['one_level'];
        $two_id = $fenxiao['two_level'];

        $vt_percent = explode(',', $projectinfo['vt_price']);
        $task_money = $taskinfo['total_price'];
        $var = $sysMd->fetchOne($uniacid, 'is_sale');//系统开启的几级分销
        $percent = $this->getpercent($uniacid);//系统设置的分销比例

        $main_percent = $projectinfo['vt_price_percent'] / 100;//经纪人的佣金比例
        $one_percent = $percent['0'] / 100;//一级分销商的佣金比例
        $two_percent = $percent['1'] / 100;//二级分销商的佣金比例

        $taskinfo['projectname'] = $projectinfo['name'];
        $taskinfo['time'] = date('Y/m/d', $taskinfo['finish_time']);//签约时间
        $taskinfo['create_time'] = date('Y/m/d', $taskinfo['create_time']);//签约时间
        #经纪人信息
        $main_member = $memberMd->one(['id' => $taskinfo['send_id']]);
        $taskinfo['main_name'] = $main_member['name'];
        $taskinfo['main_phone'] = $main_member['phone'];
        $taskinfo['main_allmoney'] = $walletlogMd->fetchmoney($taskinfo['send_id'], $uniacid);//累计佣金
        $taskinfo['main_thismoney'] = $main_percent * $task_money;//经纪人获得的佣金佣金
        $taskinfo['min_vtmoney'] = $vt_percent['0'];//项目预计最小佣金
        $taskinfo['max_vtmoney'] = $vt_percent['1'];//预计最大佣金
        #一级分销商信息
        if ($var['var'] >= 1) {
            $one_member = $memberMd->one(['id' => $one_id]);
            $taskinfo['one_name'] = $one_member['name'];
            $taskinfo['one_phone'] = $one_member['phone'];
            $taskinfo['one_time'] = date('Y/m/d', $one_member['create_time']);

            $two=$walletlogMd->taskmoney($task_id,'son');
            $taskinfo['one_thismoney'] =$two;//可获得佣金==预计佣金
        }
        #二级分销商信息
        if ($var['var'] == 2) {
            $two_member = $memberMd->one(['id' => $two_id]);
            $taskinfo['two_name'] = $two_member['name'];
            $taskinfo['two_phone'] = $two_member['phone'];
            $taskinfo['two_time'] = date('Y/m/d', $two_member['create_time']);
            $taskinfo['two_thismoney'] =  $two=$walletlogMd->taskmoney($task_id,'grand_son');//可获得佣金==预计佣金
        }
        return $taskinfo;
    }

}