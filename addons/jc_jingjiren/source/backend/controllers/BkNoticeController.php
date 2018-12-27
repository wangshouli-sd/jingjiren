<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/6
 * Time: 11:54
 */

namespace backend\controllers;


use backend\models\BkNotice;
use yii;

class BkNoticeController extends BaseController
{
    //公告列表
    public function actionIndex()
    {
        $noticeMd = new BkNotice();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $title = $request->get('title');
            $where = [
                'and',
                ['=','uniacid',$uniacid],
                ['like','title',$title],
                ['=','is_del',0],
            ];
        #分页设置
        $page_size = $size == NULL ? '20' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $noticeMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $noticeMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    //公告详情
    public function actionDeail()
    {
        global $_W, $_GPC;
        $request = Yii::$app->request;

        $json_success = [
            'status' => [
                'state' => 'success',
                'msg' => '成功',
            ]
        ];
        $json_error = [
            'status' => [
                'state' => 'error',
                'msg' => '失败',
            ]
        ];

        $id = $request->get('id');
        if ($id == NULL) {
            return $json_error;
        }

        $noticeMd = new BkNotice();
        $where_info = [
            'id' => $id,
        ];
        $info = $noticeMd->fetchDetail($where_info);
        $time=date('Y-m-d',$info['createtime']);
        $info['time']=$time;
        //如果需要后台管理员看详情也计入到浏览数再把注释删掉
//        $look = $info['look'] + 1;
//        $params = [
//            'look' => $look
//        ];
        // $edit = $noticeMd->editOne($where_info, $params);
        return $info;
    }

    #新建 操作
    /*
    request(post json):
    title           [必须]
    content         [必须]
    img              [可选]
    response:
    {
    }
    */
    public function actionCreate()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $request_data =  $_GPC['__input'];

        $json_success = [
            'status' => [
                'state' => 'success',
                'msg' => '成功',
            ]
        ];
        $json_error = [
            'status' => [
                'state' => 'error',
                'msg' => '失败',
            ]
        ];

        $title = isset($request_data['title']) ? $request_data['title'] : '';
        $content = isset($request_data['content']) ? $request_data['content'] : '';
        $img = isset($request_data['img']) ? $request_data['img'] : '';
        $look = empty($request_data['look']) ? '0' :$request_data['look'] ;
        if ($title == '' or $content == '') {
            return $json_error;
        }
        $noticeMd = new BkNotice();
        $data = [
            'uniacid' => $uniacid,
            'title' => $title,
            'content' => $content,
            'img' => $img,
            'look' => $look
        ];
        if ($noticeMd->addOne($data)) {
            return $json_success;
        } else {
            return $json_error;
        }
    }

    #修改 操作
    /*
    request(post json):
    id              [必须]
    title           [必须]
    look            [可选]
    content         [必须]
    img             [可选]
    response:
    {
    }
    */
    public function actionEdit()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $request_data =  $_GPC['__input'];

        $json_success = [
            'status' => [
                'state' => 'success',
                'msg' => '成功',
            ]
        ];
        $json_error = [
            'status' => [
                'state' => 'error',
                'msg' => '失败',
            ]
        ];

        $id = isset($request_data['id']) ? $request_data['id'] : '';
        $title = isset($request_data['title']) ? $request_data['title'] : '';
        $look = isset($request_data['look']) ? $request_data['look'] : '';
        $content = isset($request_data['content']) ? $request_data['content'] : '';
        $img = isset($request_data['img']) ? $request_data['img'] : '';
        if ($title == '' OR $id == '') {
            return $json_error;
        }

        $noticeMd = new BkNotice();
        $where = [
            'uniacid' => $uniacid,
            'id' => $id,
        ];
        $data = [
            'title' => $title,
            'look' => $look,
            'content' => $content,
            'img' => $img,
        ];
        if ($noticeMd->editOne($where, $data)) {
            return $json_success;
        } else {
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
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request_data = Yii::$app->request;

        $getid=$request_data->get('id');
        $json_success = [
            'status' => [
                'state' => 'success',
                'msg' => '成功',
            ]
        ];
        $json_error = [
            'status' => [
                'state' => 'error',
                'msg' => '失败',
            ]
        ];

        $id = isset($getid) ? $getid : '';
        if ($id == '') {
            return $json_error;
        }

        $noticeMd = new BkNotice();
        $where = [
            'uniacid' => $uniacid,
            'id' => $id,
        ];
        if ($noticeMd->delOne($where)) {
            return $json_success;
        } else {
            return $json_error;
        }
    }

}