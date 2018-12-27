<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/6
 * Time: 9:44
 */

namespace frontend\controllers;


use frontend\models\FrNotice;
use yii;

class FrNoticeController extends BaseController
{
    #详情
    public function actionDetail()
    {
        $request = Yii::$app->request;

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

        $noticeMd = new FrNotice();
        $where_info = [
            'id' => $id,
        ];
        $info = $noticeMd->fetchDetail($where_info);
        $time = $info['create_time'];
        $info['time'] = date('Y/m/d', $time);
        $look = $info['look'] + 1;
        $params = [
            'look' => $look
        ];
        $edit = $noticeMd->editOne($where_info, $params);
        return $info;
    }

    //公告列表
    public function actionList()
    {
        $noticeMd = new FrNotice();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $where = [
            'uniacid' => $uniacid,
            'is_open' => '1',
            'is_del' => 0
        ];
        #分页设置
        $page_size = $size == NULL ? '2' : $size;
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

    //首页轮播公告
    public function actionIndex()
    {
        $noticeMd = new FrNotice();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $limit = $request->get('limit');
        $setlimit = $limit == NULL ? '5' : $limit;
        $where = [
            'uniacid' => $uniacid
        ];
        $list = $noticeMd->fetchListAll($where, $setlimit, $offset = null, $order = 'create_time DESC');
        return $list;
    }

}