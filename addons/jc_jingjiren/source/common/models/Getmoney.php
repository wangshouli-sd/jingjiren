<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/11
 * Time: 16:24
 */

namespace common\models;


class Getmoney extends Base
{
    public static function tableName()
    {
        return '{{%member_getmoney}}';
    }

    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        return parent::fetchListAll($where, $limit, $offset, $order);
    }

    public function  listall($where = array(), $limit = null, $offset = null, $order = null){
        return self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
    }

    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    #获取一条
    public function fecthOne($where = array())
    {
        $list= self::find()
            ->where($where)
            ->asArray()
            ->one();
        return $list;
    }

    #添加一条
    public function addOne($params)
    {
        $params['create_time'] = time();
        $params['update_time'] = time();
        foreach ($params as $key => $var) {
            $this->$key = $var;
        }
        return $this->save();
    }
    #添加一条同时新增一条记录
    public function addOnelog($params,$member_id,$money, $type,$remarks)
    {
        $getmoneylogMd=new GetmoneyLog();
        $params['create_time'] = time();
        $params['update_time'] = time();
        foreach ($params as $key => $var) {
            $this->$key = $var;
        }
        $this->save();
        $id=$this->id;
        $data=[
            'id'=>$id,
            'getmoney_id'=>$id,
            'member_id'=>$member_id,
            'uniacid'=>$params['uniacid'],
            'remarks'=>$remarks,
            'money'=>$money,
            'type'=>$type,
        ];
        return $getmoneylogMd->addlog($data);

    }

    #更新一条
    public function editOne($where, $params)
    {
        $docMd = self::findOne($where);
        if ($docMd == NULL) {
            return false;
        }
        $params['update_time'] = time();
        foreach ($params as $key => $var) {
            $docMd->$key = $var;
        }
        return $docMd->save();
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

    //支付宝方式
    public function alipay($member_id, $data, $money, $type)
    {
        $getmoneylog = new GetmoneyLog();
        $where = [
            'member_id' => $member_id
        ];
        $info = $this->fecthOne($where);
        switch ($type) {
            case '1';
                $remarks = '支付宝';
                if ($info== ''){//第一次提现
                    $add = $this->addOne($data);
                    return $getmoneylog->addOne($member_id, $money, $type, $remarks);
                }if (!empty($info)) {
                if ($info['alipay'] == '' OR $info['truename'] == '') {//如果第一次用支付宝提现，
                    $edit = $this->editOne($where, $data);//县存入支付宝信息
                    return $getmoneylog->addOne($member_id, $money, $type, $remarks );//新增提现记录
                }
                else if ($info['alipay'] !== $data['alipay']) {//跟上次支付宝号不一样
                    return $this->addOnelog($data,$member_id,$money, $type,$remarks);
                }
                else if ($info['alipay'] !== '' and $info['truename'] !== '') {
                    return $getmoneylog->addOne($member_id, $money, $type, $remarks );
                }
            }
                break;

            case '2';
                $remarks = '银行卡';
                if ($info== ''){
                    $add = $this->addOne($data);
                    return $getmoneylog->addOne($member_id, $money, $type, $remarks );
                }if (!empty($info)) {
                if ($info['bank'] == '' OR $info['truename'] == '' or $info['bank_card'] == '') {
                    $edit = $this->editOne($where, $data);
                    return $getmoneylog->addOne($member_id, $money, $type, $remarks);
                } else if ($info['bank_card'] !==$data['bank_card']) {//跟上次提交的银行卡不一样
                    return $this->addOnelog($data,$member_id,$money, $type,$remarks);
                }else if ($info['bank'] !== '' and $info['truename'] !== '' and $info['bank_card'] !== '') {
                    return $getmoneylog->addOne($member_id, $money, $type, $remarks );
                }
            }
                break;
        }

    }

}