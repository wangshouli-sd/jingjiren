<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/5
 * Time: 17:38
 */

namespace common\models;


class Table extends Base
{
    public static function tableName()
    {
        return "{{%project_table}}";
    }
    //列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        return parent::fetchListAll($where, $limit, $offset, $order);
    }
    //获取总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

}