<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/27
 * Time: 10:37
 */

namespace common\models;


use frontend\models\FrMember;
use frontend\models\FrTask;

class Customer extends Base
{
    static function tableName()
    {
        return "{{%customer_remarks}}";
    }

    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        global  $_W;
        $memberMd=new FrMember();
        $taskMd=new FrTask();
        $list= parent::fetchListAll($where, $limit, $offset, $order);
        if ($list!=null){
            $i=0;
            foreach ($list as $key=>$value){
                $list[$i]['create_time']=date('Y-m-d H:i:s');
                $list[$i]['jing_name']=$memberMd->One(['id'=>$value['member_id']])['name'];
                $list[$i]['task_count']=$taskMd->fetchCountNum(['userphone'=>$value['customer_phone']]);
                $i++;
            }
        }
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


    //获取一条任务详情
    public function fetchOne($where = array())
    {
        $list = $this::find()
            ->filterWhere($where)
            ->asArray()
            ->one();
        if ($list!=null){
            $list['create_time'] = date('Y-m-d H:i:s');
            $list['update_time'] = date('Y-m-d H:i:s');
        }
        return $list;
    }

    #更新一条
    public function editOne($where, $params)
    {
        $params['update_time'] = time();
        $docMd = self::findOne($where);
        if ($docMd == NULL) {
            return false;
        }
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

}