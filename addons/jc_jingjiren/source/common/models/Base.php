<?php

namespace common\models;

use yii;
use yii\db\ActiveRecord;

class Base extends ActiveRecord
{
    /*返回列表*/
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        global $_W;
        return self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
    }

    /*计算总计*/
    public function fetchCountNum($where = array())
    {
        return self::find()
            ->filterWhere($where)
            ->count();
    }
}
