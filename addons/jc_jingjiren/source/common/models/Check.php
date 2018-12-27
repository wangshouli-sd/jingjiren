<?php
/**
 * Created by PhpStorm.
 * User: jiechenhulian
 * Date: 2018/9/6
 * Time: 16:42
 */

namespace common\models;


use backend\models\BkMember;
use frontend\models\FrMember;

class Check extends \common\models\Base
{

    public function up($values = array(), $id = null)
    {
        $process = new BkProcess();
        $up = $process->One($id);
        $up->scenario = 'update';
        $up->attributes = $values;
        $up->save();
        return [
            'status' => [
                'state' => 'success',
                'msg' => '编辑成功'
            ]
        ];
    }

}
