<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/28
 * Time: 9:30
 */

namespace frontend\models;

use common\models\base;
use common\models\Category;
use yii\db\ActiveRecord;
class FrCategory extends Category
{
    //获取所有分列名称或者id
    public function fetchListAll($where = array(), $like = array(), $limit = array(), $offset = null, $order = null)
    {

        global $_W;
        $uniacid = $_W['uniacid'];
        $where = ['uniacid' => $uniacid, 'is_del' => 0];
        $list = $this::find()
            ->where($where)
            ->andWhere($like)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        foreach ($list as $value) {
            $ids[] = $value['id'];
            $title[] = $value['title'];
        }
        if (empty($like)) {
            return $list;
        }
        return $ids;
    }

    //通过id获取类目名
    public function fetchOne($id = null)
    {
        return parent::fetchOne($id);
    }

}