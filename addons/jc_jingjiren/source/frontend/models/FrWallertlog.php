<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/13
 * Time: 9:21
 */

namespace frontend\models;


use common\models\WalletLog;

class FrWallertlog extends WalletLog
{

    //个人累计佣金获取
    public function fetchmoney($member_id,$uniacid)
    {
        $where=[
            'member_id'=>$member_id,
            'uniacid'=>$uniacid,
            'type'=>1,
            'name'=>[
                'main',
                'son',
                'grand_son',
            ]
        ];
        $ru_list=$this->fetchListAll($where);
        if (empty($ru_list)){
            return 0;
        }
        foreach ($ru_list as $key =>$value){
            $money[]=$value['change_money'];
        }
        $ru=array_sum($money);
        $where_chu=[
            'member_id'=>$member_id,
            'uniacid'=>$uniacid,
            'type'=>2
        ];
        $chu_list=$this->fetchListAll($where_chu);
        if (empty($chu_list)){
            return $ru;
        } else{
        foreach ($chu_list as $key =>$value){
            $chu_money[]=$value['change_money'];
        }
        $chu=array_sum($chu_money);
        $sum=$ru-$chu; // 余额
        return $ru;//累计佣金
        }
    }

    // 个人累计提现
    public function tixianMoney($member_id,$uniacid)
    {
        $where_chu=[
            'member_id'=>$member_id,
            'uniacid'=>$uniacid,
            'type'=>2,
            'name'=>'tixian'

        ];
        $chu_list=$this->fetchListAll($where_chu);
        if (empty($chu_list)){
            return 0;
        } else{
            foreach ($chu_list as $key =>$value){
                $chu_money[]=$value['change_money'];
            }
            $chu=array_sum($chu_money);

            return $chu;//累计佣金
        }
    }

    //个人所有分销得钱
    public function leval($member_id,$uniacid,$name)
    {
        $where=[
            'member_id'=>$member_id,
            'uniacid'=>$uniacid,
            'type'=>1,
            'name'=>$name
        ];
        $list=$this->fetchListAll($where);
        if (empty($list)){
            return '0';
        }
        foreach ($list as $key =>$value){
            $money[]=$value['change_money'];
        }
        $sum=array_sum($money);

            return $sum;

    }

}
