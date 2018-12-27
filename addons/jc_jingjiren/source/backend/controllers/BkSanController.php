<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/30
 * Time: 21:05
 */

namespace backend\controllers;

use backend\models\BkProject;
use backend\models\BkSan;
use common\models\San;
use common\models\SysParams;
use common\models\Upload;
use Yii;
class BkSanController extends BaseController
{
    //设置首页中间三块
    public function actionAddsan()
    {
        $san = new BkSan();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $values = $_GPC['__input'];
        if ($values==null){
            return [
                'state'=>'null',
                'msg'=>'空的'
            ];
        }
        if ($values['id']==null){
            if ($san->add($values)){
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '成功',
                    ]
                ];
            }

        }else{
            if ($san->editOne($values)){
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '成功',
                    ]
                ];
            }
        }
    }

    //获取小程序首页中间三块的广告图片和标题和名称
    public function actionSan(){
        $sanMd=new San();
        global $_W;
        $uniacid=$_W['uniacid'];
        $where=[
            'is_del'=>0,
            'type'=>2,
            'uniacid'=>$uniacid
        ];
        $list=$sanMd->fetchListAll($where);
        return $list;
    }

    #删除
    public function actionDelsan(){
        $sanMd=new San();
        $request=Yii::$app->request;
        $id=$request->get('id');
        $del=$sanMd->del($id);
        if ($del==true){
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '成功',
                    ]
            ];
        }
    }

    #获取一条详情
    public function actionOnesan(){
        $sanMd=new San();
        $uploadMd=new Upload();
        $request=Yii::$app->request;
        $id=$request->get('id');
        $list= $sanMd->one(['id'=>$id]);
        $info=$uploadMd->One($list['img']);
        $list['path']=$info->path;
        return $list;
    }

}
