<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/30
 * Time: 20:56
 */

namespace backend\models;

use common\models\San;

class BkSan extends San
{

    //获取一条 对象
    public function fetchone($id = null)
    {
        return self::findone($id);
    }

    //设置首页循环播放图
    public function setindex($id, $uniacid)
    {
        $ids = explode(',', $id);
        $del = self::deleteAll(['type' => 1]);
        $i = 1;
        foreach ($ids as $key) {
            $san = new BkSan();
            $san->uniacid = $uniacid;
            $san->img = $key;
            $san->type = 1;
            $san->order = $i;
            $san->is_index = 1;
            $san->create_time = time();
            $san->update_time = time();
            $san->scenario = 'create';
            $san->save();
            $i++;
        }
        return 1;
    }

}