<?php
/**
 * Created by PhpStorm.
 * User: jiechenhulian
 * Date: 2018/8/24
 * Time: 13:49
 */

namespace backend\models;

use common\models\Member;
use yii\db\ActiveRecord;

class BkMember extends Member
{
    //获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $list = $this::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        return $list;
    }

    //获取用户总数
    public function fetchCountNum($where = array())
        {
            return parent::fetchCountNum($where);
        }

    // 审核用户信息  是否符合需求发布者权限 is_sender
    public function audit()
    {
        return bool;
    }

    //获取一条   用户信息
    public function fetchOne($id = null)
    {
        $one = $this::findOne($id);
        return $one;
    }

    #更新
    public function editOne($where, $params)
    {
        $docMd = self::findOne($where);
        if ($docMd == NULL) {
            return false;
        }
        foreach ($params as $key => $var) {
            $docMd->$key = $var;
        }
        return $docMd->save();
    }

    // 加入黑名单  经纪人
    public function add($id = null, $values = array())
    {
        $values['update_time']=time();
        $del = $this->fetchOne($id);
        if (empty($del)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '没有选择要加入黑名单的人'
                ]];
        }

        $del->scenario = 'blacklist';
        $del->attributes = $values;
        $del->save();
        return 0;
    }

    // 取消黑名单  经纪人
    public function del($id = null, $values = array())
    {
        $del = $this->fetchOne($id);
        if (empty($del)) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '没有选择要取消黑名单的人'
                ]];
        }

        $del->scenario = 'blacklist';
        $del->attributes = $values;
        $del->save();
        return 0;
    }

    #我的客户列表
    public function MyCustomer(){

    }

}
