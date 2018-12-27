<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/6
 * Time: 9:46
 */

namespace frontend\models;


use common\models\Notice;
use common\models\Upload;
use yii;
class FrNotice extends Notice
{
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        return parent::fetchListAll($where, $limit, $offset, $order);
    }
}