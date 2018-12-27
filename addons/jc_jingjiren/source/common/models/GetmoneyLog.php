<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/11
 * Time: 19:29
 */

namespace common\models;


use backend\models\BkMember;

class GetmoneyLog extends Base
{
    public static function tableName()
    {
        return '{{%member_getmoney_log}}';
    }

    #添加提现记录
    public function addOne($member_id, $money, $type, $remarks)
    {
        $member_info = Member::findOne(['id' => $member_id]);
        if ($member_info == NULL or $member_id == NULL) {
            return false;
        }

        $getmoney_info = Getmoney::findOne(['member_id' => $member_id]);
        if ($getmoney_info == NULL) {
            return false;
        }
        $this->uniacid = $member_info->uniacid;
        $this->member_id = $member_id;
        $this->getmoney_id = $getmoney_info->id;
        $this->remarks = $remarks;
        $this->money = $money;
        $this->type = $type;
        $this->create_time = time();
        $this->update_time = time();

        if ($this->save()) {
            $id = $this->id;
            $date = date('ymd', time());
            $number = "L".$date . $id;
            $info = self::findOne($id);
            $info->number = $number;
            if ($info == NULL) {
                return false;
            }
            if ($info->save()) {
                $wallertMd=new Wallet();
                $wallertMd->minusMoney($member_id,'0',$money,'alipay_tixian');
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '成功',
                    ]
                ];
            }

        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败',
                ]
            ];
        }

    }
    #添加一条提现记录
    public function addlog($data)
    {
        $member_info = Member::findOne(['id' => $data['member_id']]);
        if ($member_info == NULL or $data['member_id'] == NULL) {
            return false;
        }

        $getmoney_info = Getmoney::findOne(['id' => $data['id']]);
        if ($getmoney_info == NULL) {
            return false;
        }
        $this->uniacid = $data['uniacid'];
        $this->member_id = $data['member_id'];
        $this->getmoney_id = $data['getmoney_id'];
        $this->remarks = $data['remarks'];
        $this->money = $data['money'];
        $this->type = $data['type'];
        $this->create_time = time();
        $this->update_time = time();

        if ($this->save()) {
            $id = $this->id;
            $date = date('ymd', time());
            $number = "L".$date . $id;
            $info = self::findOne($id);
            $info->number = $number;
            if ($info == NULL) {
                return false;
            }
            if ($info->save()) {
                $wallertMd=new Wallet();
                if ( $data['type']==1){
                    $name='alipay_tixian';
                }else if ( $data['type']==2){
                    $name='bank_tixian';
                }else if ( $data['type']==3){
                    $name='wechat_tixian';
                }
                $wallertMd->minusMoney($data['member_id'],'0',$data['money'],$name);
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '成功',
                    ]
                ];
            }

        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败',
                ]
            ];
        }

    }

    #获取转账信息
    public function getinfo($where = array())
    {
        return self::find()
            ->where($where)
            ->asArray()
            ->one();
    }

    //审核银行卡打款完成
    public function shen($id, $value)
    {
        $walartMd=new Wallet();

        $Tem=new Template();
        $member=new Member();
        $FormidMd=new Formid();
        $info=self::find()->where(['id'=>$id])->asArray()->one();
        $tiinfo=Getmoney::find()->where(['id'=>$info['getmoney_id']])->asArray()->one();
        $memberinfo=$member->one(['id'=>$info['member_id']]);
        $time=strtotime('-7 days');
        $where_form=[
            'and',
            ['=','member_id',$info['member_id']],
            ['>','create_time',$time],
            ['=','is_del','0'],
        ];
        $list_all=$FormidMd->fetchListAll($where_form, $limit = null, $offset = null, $order = ['create_time'=>SORT_ASC]);
        if ($value['state']==0){
            $why=($value['remarks']==null)?$value['remarks']:'其他原因,详情请联系客服';
            $Tem->TXfail($memberinfo['openid'],$list_all[0]['formid'],$info['number'],$tiinfo['truename'],$tiinfo['bank_card'],$info['money'],$why);
            $FormidMd->delOne(['formid'=>$list_all[0]['formid']]);
        }else if ($value['state']==3){
            $why=($value['remarks']==null)?$value['remarks']:'其他原因,详情请联系客服';
            $Tem->TXsuccess($memberinfo['openid'],$list_all[0]['formid'],$tiinfo['truename'],$tiinfo['bank'],$tiinfo['bank_card'],$info['money'],$info['money'],$why);
            $FormidMd->delOne(['formid'=>$list_all[0]['formid']]);
        }
        $checkstate = $this->state($id, $value);
        if($value['state']==0){
            $info=$this->one($id);
            if ($info['type']==1){
                $name='支付宝提现失败退款';
            }else if ($info['type']==2){
                $name='银行卡提现失败退款';
            }else if ($info['type']==3){
                $name='微信提现失败退款';
            }
            $fan=$walartMd->plusMoney($info['member_id'],'0','',$info['money'],$name);
        }
        if ($checkstate == 1) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '成功',
                ]
            ];
        }
        else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败',
                ]
            ];
        }
    }

    //该状态值
    public function state($id, $value)
    {

        $getMd = self::findOne($id);
        if ($getMd == NULL) {
            return false;
        }
        $remark='';
        if ($value['state']==0){
            $remark=empty($value['remarks'])?$value['remark']:'其他原因';
        }
        $getMd->state = $value['state'];
        $getMd->remarks = $remark;
        $getMd->update_time = time();
        if ($getMd->save()) {
            return 1;
        }
    }

    //提现列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $list = self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        $i = 0;
        foreach ($list as $key => $value) {
            $get = new Getmoney();
            $bank = new Bank();
            $memberMd = new BkMember();
            $walletMd = new Wallet();
            $wallet = $walletMd->fetchInfo($value['member_id']);
            $memberinfo = $memberMd->one(['id' => $value['member_id']]);
            $id = $value['getmoney_id'];
            $info = $get->fecthOne(['id' => $id]);
            $bankname = $bank->bankname($info['bank']);
            $list[$i]['truename'] = $info['truename'];
            $list[$i]['alipay'] = $info['alipay'];
            $list[$i]['bank_card'] = $info['bank_card'];
            $list[$i]['bankname'] = $info['bank'];
            $list[$i]['phone'] = $memberinfo['phone'];
            $list[$i]['havemoney'] = $wallet['money']+$value['money'];

            if ($value['type'] == 1) {
                $list[$i]['typename'] = '支付宝';
            } elseif ($value['type'] == 2) {
                $list[$i]['typename'] = '银行卡';
            } else if ($value['type'] == 3) {
                $list[$i]['typename'] = '微信钱包';
            }
            $list[$i]['time'] = date('Y-m-d H:i:s', $value['create_time']);
            $list[$i]['endtime'] = date('Y-m-d H:i:s', $value['update_time']);

            $list[$i]['name'] =$memberinfo['name'];
            $list[$i]['nick_name'] =$memberinfo['nick_name'];

            $i++;
        }
        return $list;
    }

    #通过id获取一条
    public function one($id)
    {
        return self::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
    }

    #获取一条
    public function fecthone($member_id, $state)
    {
        $where = [
            'member_id' => $member_id,
            'state' => $state
        ];
        return self::find()
            ->where($where)
            ->asArray()
            ->one();
    }

    #删除一条(is_del 修改)
    public function delOne($where)
    {
        $docMd = self::findOne($where);
        if ($docMd == NULL) {
            return false;
        }
        $docMd->is_del = '1';
        return $docMd->save();
    }

    #个人累计提现
    public function tixianMoney($member_id,$uniacid){

        $where_chu=[
            'member_id'=>$member_id,
            'uniacid'=>$uniacid,
            'state'=>'3',
            'is_del'=>'0'

        ];
        $chu_list=$this->fetchListAll($where_chu);
        if (empty($chu_list)){
            return 0;
        } else{
            foreach ($chu_list as $key =>$value){
                $chu_money[]=$value['money'];
            }
            $chu=array_sum($chu_money);
            return $chu;//累计佣金
        }
    }

}