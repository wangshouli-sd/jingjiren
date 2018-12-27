<?php

namespace common\models;

use  backend\models\BkTask;
use frontend\models\FrWallertlog;

class Member extends Base
{
    public static function tableName()
    {
        return "{{%member}}";
    }

    //    验证
    public function scenarios()
    {
        return [
            'create' => ['name', 'uniacid','openid', 'onlyid', 'nick_name', 'sex', 'city', 'image', 'brithday', 'description', 'phone', 'idcard', 'idcard_pic', 'is_sender', 'is_manager', 'is_executer', 'create_time', 'update_time'],
            'update' => ['name','code', 'uniacid','is_sender', 'nick_name', 'sex', 'city', 'image', 'brithday', 'description', 'phone', 'idcard', 'idcard_pic', 'is_sender', 'is_manager', 'is_executer', 'create_time', 'update_time'],
            'idcard' => ['idcard'],
            'is_sender' => ['is_sender', 'jing_time','sender_remark'],
            'upload' => ['name', 'id', 'uniacid', 'idcard', 'idcard_pic', 'create_time', 'update_time'],    // 更新
            'like' => ['project_id'],       //  我的收藏
            'is_del' => ['is_del', 'update_time'],
            'blacklist' => ['blacklist','update_time'],

        ];
    }
    //获取总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    // 获取列表总数
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        return parent::fetchListAll($where, $limit, $offset, $order);
    }


    //获取一条   用户信息
    public function fetchOne($id = null)
    {
        $one = $this::findOne($id);
        return $one;
    }

    //暂存验证码
    public function editone($id, $data){
        $edit = Member::findOne($id);
        $edit->scenario = 'update';
        $edit->code = $data['code'];
        $edit->phone = $data['phone'];
        $edit->access_time = $data['access_time'];
       if ($edit->save()) {
           return 1;
       }
    }

    //修改用户信息
    public function edit($id, $data)
    {
        $edit = Member::findOne($id);
        $edit->scenario = 'update';
        $edit->attributes = $data;
       if ($edit->save()){
           return [
               'status' => [
                   'state' => "success",
                   'msg' => '成功',
                   'dat' =>$data,
               ]
           ];
       }
    }

    //根据其他条件获取一条
    public function one($where = array())
    {

        $list = self::find()
            ->where($where)
            ->asArray()
            ->one();
        return $list;
    }

    //根据id获取用户各种信息 佣金等
    public function getinfo($id, $uniacid)
    {
        $walletlogMd = new FrWallertlog();
        $walletMd = new Wallet();
        $uploadMd = new Upload();
        $taskMd = new BkTask();
        $fenxiaoMd = new Distribution();
        $list = self::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $list['allmoney'] = $walletlogMd->fetchmoney($id, $uniacid); //个人累计佣金
        $balance = $walletMd->fetchInfo($id);
        $list['balance'] = $balance['money'];//个人账户余额
        $list['all_tixian'] = $walletlogMd->tixianMoney($id, $uniacid);//累计提现
        $list['idcard_img'] = $uploadMd->getimg($list['idcard_pic']);//身份证正反照片
        $list['ok_task'] = $taskMd->fetchCountNum(['send_id' => $id,]);//已完成的任务数
        $list['all_task'] = $taskMd->fetchCountNum(['send_id' => $id, 'state_progress' => 3]);//所有任务数
        $list['all_son'] = $fenxiaoMd->fetchCountNum(['one_level' => $id]);//下级人数
        $list['isjng_time'] = date('Y/m/d h:i', $list['jing_time']);
        return $list;
    }


    //根据id获取这个经纪人及其上级的详细信息
    public function oneinfo($id, $uniacid)
    {
        $fenxiaoMd = new Distribution();
        #当前人的
        $main = $this->getinfo($id, $uniacid);
        $main['touxiang']=$main['image'];
        $shang = $fenxiaoMd->one(['main_id' => $id]);//上级id
        #爸爸的数组
        $parent = ($shang['one_level'] == 0) ? '' : $this->getinfo($shang['one_level'], $uniacid);//父级
        $parent['touxiang']=$main['image'];
        #爷爷的数组
        $grend = ($shang['two_level'] == 0) ? '' : $this->getinfo($shang['two_level'], $uniacid);//爷爷
        $grend['touxiang']=$main['image'];

        return [
            'main' => $main,
            'parent' => $parent,
            'grend' => $grend,
        ];
    }

    // 添加用户
    public function add($data = array())
    {
        $this->scenario = 'create';
        $this->attributes = $data;
        $this->save();
        $id = $this->id;
        #插入openid
        $onlyid=date('ymd',time()).$id;
        $this->onlyid=$onlyid;
        $this->save();
         return $onlyid;

    }

//    //添加分销商
//    public function addfenxiao($uniacid, $parentid, $id)
//    {
//        $fenxiao = new Distribution();
//        $sysparamsMd = new SysParams();
//        $sys = $sysparamsMd->fetchOne($uniacid, 'is_sale');
//        #0是关闭分销，1是一级分销，2是二级分销
//        switch ($sys['var']) {
//            case '0';
//                break;
//
//            case '1';
//                return $fenxiao->addone($uniacid, $parentid, $id);
//                break;
//
//            case '2';
//                return $fenxiao->addtwo($uniacid, $parentid, $id);
//                break;
//        }
//
//    }

    //根据openid返回memberid
    public function memberid($openid)
    {
        $list = self::find()
            ->where(['openid' => $openid])
            ->asArray()
            ->one();
        return $list['id'];
    }
}
