<?php
namespace common\models;

use yii;

class Wallet extends Base
{
    public static function tableName()
    {
        return "{{%wallet}}";
    }

    #个人余额信息获取
    public function fetchInfo($member_id)
    {
        if(!$this->formatOne($member_id))
        {
            return false;
        }
        $wallet_info=self::findOne(['member_id'=>$member_id]);
        if($wallet_info==NULL)
        {
            return false;
        }
        return $wallet_info->toArray();
    }

    #初始化钱包数据条
    public function formatOne($member_id)
    {
        $member_info=Member::findOne(['id'=>$member_id]);
        if($member_info==NULL)
        {
            return false;
        }

        $wallet_info=self::findOne(['member_id'=>$member_id]);
        if($wallet_info != NULL)
        {
            return true;
        }

        $this->uniacid=$member_info->uniacid;
        $this->member_id=$member_id;
        $this->money='0';
        $this->create_time=time();
        $this->update_time=time();
        $this->save();
        return true;
    }

    #余额增加
    public function plusMoney($member_id,$task_id,$id,$change_money,$name)
    {
        $old_info=$this->fetchInfo($member_id);

        #余额修改
        $old_money=$old_info['money'];
        //$old_money=intval($old_money);
        $old_money=floatval($old_money);
        $new_money=$old_money+floatval($change_money);

        $walletMd=self::findOne(['id'=>$old_info['id']]);
        $walletMd->money=$new_money;
        if(!$walletMd->save())
        {
            return false;
        }

        #增加log
        $type='1';//余额增加
        $walletLogMd=new WalletLog();
        if($walletLogMd->addOne($member_id,$task_id,$id,$change_money,$type,$name))
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    #余额减少 若余额不足返false
    public function minusMoney($member_id,$task_id,$change_money,$name)
    {
        $old_info=$this->fetchInfo($member_id);

        #余额修改
        $old_money=$old_info['money'];
        $old_money=floatval($old_money);
        #判断余额是否充足
        if($old_money<$change_money)
        {
            return false;
        }
        $new_money=$old_money-floatval($change_money);
        $new_money=$new_money<0?0:$new_money;

        $walletMd=self::findOne(['id'=>$old_info['id']]);
        $walletMd->money=$new_money;
        if(!$walletMd->save())
        {
            return false;
        }

        #增加log
        $type='2';//余额减少
        $walletLogMd=new WalletLog();
        if($walletLogMd->addOne($member_id,$task_id,'0',$change_money,$type,$name))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


}

