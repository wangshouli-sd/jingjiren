<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 18:38
 */

namespace backend\controllers;

use backend\models\BkProcess;
use backend\controllers\BaseController;
use Yii;
class BkProcessController extends BaseController
{
    //获取当前任务所有流程
    public function actionOne($task_id=null)
    {
        $process=new BkProcess();
        $where=[
            'task_id'=>$task_id,
        ];
        $order = [
            'type' => SORT_ASC,
            'order' => SORT_ASC
        ];
        $list=$process->fetchlist($where,$order);
        return $list;
    }

    //删除项目流程
    public function actionDelprocess(){
        $processMd = new BkProcess();
        $request = Yii::$app->request;
        $id=$request->get('id');
        return $processMd->del($id);
    }

    //编辑流程
    public function actionUpprocess(){
        $processMd = new BkProcess();
        global $_W, $_GPC;
        $values = $_GPC['__input'];
        $proce=(array)$values['process'];
        $project_id=$values['id'];
        $uniacid = $_W['uniacid'];
        $process=$processMd->fetchListAll(['project_id'=>$project_id]);
        foreach ($process as $key =>$var){
            $id[]=$var['id'];
        }
        if ($processMd->delall($id)==1){
            return $processMd->add($proce, $project_id, $uniacid);
        }
    }

}