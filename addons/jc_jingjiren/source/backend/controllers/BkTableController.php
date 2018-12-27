<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/30
 * Time: 10:59
 */

namespace backend\controllers;

use backend\controllers\BaseController;
use backend\models\BkProject;
use backend\models\BkTable;
use backend\models\BkTask;
use yii;
class BkTableController extends BaseController
{
    #新增自定义字段传入项目project_id和字段id
    public function actionAddup()
    {
        global $_GPC,$_W;
        $table = new BkTable();
        $values= $_GPC['__input'];
        $order=$values['order'];
        $project_id = $values['project_id'];
        $id = $values['id'];
        //验证order唯一
        $where=['project_id' => $project_id];
        $tablelist = $table->fetchListAll($where);
        foreach ($tablelist as $key => $value) {
            if ($order == $value['order']) {
                return [
                    'status' => [
                        'state' => 'error',
                        'msg' => '排序冲突'
                    ]];
            }
        }
        $now = strtotime('now');
        // 新增
        if (empty($id)) {
            $values['create_time']=$now;
            $values['update_time']=$now;
            $values['project_id']=$project_id;
            return $table->add($values, $project_id);
            //编辑
        } else if (!empty($id)) {
            $values['update_time']=$now;
            return $table->up($values, $id);
        }

    }

    //列表 按照order排序。
    public function actionList()
    {
        $table = new BkTable();
        $request=yii::$app->request;
        $project_id=$request->get('id');
        $where = [
            'project_id' => $project_id,
            'is_del' => 0
        ];
        return $table->fetchListAll($where,$order = 'order');
    }

    //删除自定义字段
    public function actionDel()
    {
        $table = new BkTable();
        $now = strtotime('now');
        $request=yii::$app->request;
        $id=$request->get('id');
        $values = [
            'is_del' => 1,
            'update_time' => $now,
        ];
        return $table->del($id,$values);

    }

}