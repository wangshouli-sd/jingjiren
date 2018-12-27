<?php

namespace backend\controllers;

use yii;
use backend\models;

class BkDocumentController extends BaseController
{
    #获取 列表
    /*
        request(get):
        page        [可选]
        size        [可选]

        response:
        {
            "list": [
                {},
                {}
            ],
            "page": {
                "current": "1",         //当前页码
                "next": "2",            //下一页码
                "data_total": "4",      //数据总数
                "data_residue": 2,      //剩余数据数
                "page_total": 2         //总页数
                "page_residue":"1"      //剩余页数
            }
        }
    */
    public function actionList()
    {
        global $_W,$_GPC;
        $bkDocMd=new \backend\models\BkDocument();
        $uniacid=$_W['uniacid'];
        $request=Yii::$app->request;
        $page=$request->get('page');
        $size=$request->get('size');
        $where=[
            'uniacid'=>$uniacid,
        ];
        #分页设置
        $page_size=$size==NULL?'2':$size;
        $page_index=$page==NULL?1:$page;
        $data_total=$bkDocMd->fetchCountNum($where);

        $pageMd=new \common\tools\PageJson($data_total, $base_url='', $page_index, $page_size);
        $page_limit=$pageMd->getLimit();
        $limit=$page_limit[1];
        $offset=$page_limit[0];

        $list=$bkDocMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $page=$pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    #新建 操作
    /*
    request(post json):
    title           [必须]
    description     [可选]
    content         [可选]

    response:
    {
    }
    */
    public function actionCreate()
    {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $request=Yii::$app->request;
        $request_data=$_GPC['__input'];

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

        $title=isset($request_data['title'])?$request_data['title']:'';
        $description=isset($request_data['description'])?$request_data['description']:'';
        $content=isset($request_data['content'])?$request_data['content']:'';
        if($title=='')
        {
            return  $json_error;
        }
        $bkDocMd=new models\BkDocument();
        $data=[
            'uniacid'=>$uniacid,
            'title'=>$title,
            'description'=>$description,
            'content'=>$content,
        ];
        if($bkDocMd->addOne($data))
        {
            return $json_success;
        }
        else
        {
            return $json_error;
        }
    }

    #详情
    /*
    request(get):
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
        $bkDocMd=new models\BkDocument();
        $where_info=[
            'id'=>$id,
        ];
        $info=$bkDocMd->fetchDetail($where_info);
        return $info;

    }

    #修改 操作
    /*
    request(post json):
    id              [必须]
    title           [必须]
    description     [必须]
    content         [必须]

    response:
    {
    }
    */
    public function actionEdit()
    {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $request=Yii::$app->request;
        $request_data=$_GPC['__input'];

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

        $id=isset($request_data['id'])?$request_data['id']:'';
        $title=isset($request_data['title'])?$request_data['title']:'';
        $description=isset($request_data['description'])?$request_data['description']:'';
        $content=isset($request_data['content'])?$request_data['content']:'';
        if($title=='' OR $id=='')
        {
            return  $json_error;
        }

        $bkDocMd=new models\BkDocument();
        $where=[
            'uniacid'=>$uniacid,
            'id'=>$id,
        ];
        $data=[
            'title'=>$title,
            'description'=>$description,
            'content'=>$content,
        ];
        if($bkDocMd->editOne($where,$data))
        {
            return $json_success;
        }
        else
        {
            return $json_error;
        }
    }

    #删除 操作
    /*
       request(post json):
       id              [必须]

       response:
       {
       }
   */
    public function actionDel()
    {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        $request=Yii::$app->request;
        $request_data=$_GPC['__input'];

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

        $id=isset($request_data['id'])?$request_data['id']:'';
        if($id=='')
        {
            return  $json_error;
        }

        $bkDocMd=new models\BkDocument();
        $where=[
            'uniacid'=>$uniacid,
            'id'=>$id,
        ];
        if($bkDocMd->delOne($where))
        {
            return $json_success;
        }
        else
        {
            return $json_error;
        }
    }
}
