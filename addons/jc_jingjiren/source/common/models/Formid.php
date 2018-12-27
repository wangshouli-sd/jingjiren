<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/19
 * Time: 10:09
 */

namespace common\models;


class Formid extends Base
{
    public static function tableName()
    {
        return "{{%member_formid}}";
    }

    //获取总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    //获取一条任务详情
    public function fetchOne($where = array())
    {
        $list = $this::find()
            ->filterWhere($where)
            ->asArray()
            ->one();
        if ($list != null) {
            $list['create_time'] = date('Y-m-d H:i:s');
            $list['update_time'] = date('Y-m-d H:i:s');
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

    #更新一条
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