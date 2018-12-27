<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/6
 * Time: 15:25
 */

namespace backend\controllers;

use backend\models\BkMember;
use yii;
use backend\models\BkSuggest;

class BkSuggestController extends BaseController
{
    //意见反馈列表
    public function actionIndex()
    {
        $suggestMd = new BkSuggest();
        $memberMd=new BkMember();
        global $_W, $_GPC;
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $uniacid = $_W['uniacid'];
        $name = $request->get('name');
        if (!empty($name)){
            $params=[
                'and',
                ['=','uniacid',$uniacid],
                ['like','name',$name],

            ];
            $member=$memberMd->fetchListAll($params);
            foreach ($member as $key =>$val){
                $sendids[]=$val['id'];
            }
        }
        $where = [
            'uniacid'=>$uniacid,
            'send_id'=>$sendids,
            'is_del'=>0

        ];
        #分页设置
        $page_size = $size == NULL ? '20' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $suggestMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $suggestMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    //意见详情
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

        $suggestMd = new BkSuggest();
        $where_info = [
            'id' => $id,
        ];
        $info = $suggestMd->fetchDetail($where_info);
        $time = $info['create_time'];
        $info['time'] = date('Y/m/d', $time);
        return $info;
    }


    #删除 操作
    public function actionDel()
    {
        global $_W, $_GPC;
        $request_data =Yii::$app->request;
        $request_id=$request_data->get('id');
        $uniacid = $_W['uniacid'];
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

         $id=isset($request_id) ? $request_id : '';
        if ($id == '') {
            return $json_error;
        }

        $suggestMd = new BkSuggest();
        $where = [
            'uniacid' => $uniacid,
            'id' => $id,
        ];
        if ($suggestMd->delOne($where)) {
            return $json_success;
        } else {
            return $json_error;
        }
    }

    #后台管理员已读操作
    public function actionRead(){
        global $_W, $_GPC;
        $request_data =Yii::$app->request;
        $request_id=$request_data->get('id');
        $uniacid = $_W['uniacid'];
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

        $id=isset($request_id) ? $request_id : '';
        if ($id == '') {
            return $json_error;
        }

        $suggestMd = new BkSuggest();
        $where = [
            'uniacid' => $uniacid,
            'id' => $id,
        ];
        if ($suggestMd->readOne($where)) {
            return $json_success;
        } else {
            return $json_error;
        }
    }

}