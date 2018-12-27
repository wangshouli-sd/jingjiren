<?php

namespace frontend\controllers;

use frontend\models\FrDocument;
use yii;

class FrDocumentController extends BaseController
{

    #详情
    /*
    request(post json):
    id              [必须]

    response:
    {
    }
    */
    public function actionDetail()
    {
        global $_W,$_GPC;
        $request=Yii::$app->request;

        $json_success= [
            'status'=>[
                'state'=>'success',
                'msg'=>'成功',
            ]
        ];
        $json_error= [
            'status'=>[
                'state'=>'error',
                'msg'=>'失败',
            ]
        ];

        $id=$request->get('id');
        if($id==NULL)
        {
            return $json_error;
        }

        $bkDocMd=new FrDocument();
        $where_info=[
            'id'=>$id,
        ];
        $info=$bkDocMd->fetchDetail($where_info);
        return $info;
    }


}
