<?php
/**
 * Created by PhpStorm.
 * User: zmy
 * Date: 18-10-29
 * Time: 下午5:18
 */

namespace common\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
class Wxapp extends Base
{
    //获取小程序的appid 和密钥
    public function getappinfo(){
        global  $_W;
        $uniacid=$_W['uniacid'];
        $rows = (new \yii\db\Query())
            ->select(['key', 'secret'])
            ->from('ims_account_wxapp')
            ->where(['uniacid' => $uniacid])
            ->one();
        return $rows;
    }
}