<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/29
 * Time: 11:09
 */

namespace backend\controllers;

use backend\models\BkCategory;
use yii;

class BkCategoryController extends BaseController
{
    //获取类目列表
    public function actionIndex()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $open = $request->get('open');
        if ($open == 1) {
            $where = ['uniacid' => $uniacid, 'is_del' => 0];
        } else {
            $where = ['uniacid' => $uniacid, 'is_del' => 0, 'is_open' => 1];
        }
        $cate = new BkCategory();
        $page_size = $size == NULL ? '15' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $cate->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $cate->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page,
        ];
    }

    //获取一条
    public function actionOne()
    {
        $category = new BkCategory();
        $request = yii::$app->request;
        $id = $request->get('id');
        $list = $category->fetchOne($id);
        return $list;
    }

    //新增、编辑类目
    public function actionCreateup()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $values = $_GPC['__input'];
        $id = $values['id'];
        $now = strtotime('now');
        $category = new BkCategory();
        //编辑
        if (!empty($id)) {
            $values['update_time'] = $now;
            return $category->up($id, $values);
            //新增
        } else if (empty($id)) {
            $values['create_time'] = $now;
            $values['update_time'] = $now;
            $values['uniacid'] = $uniacid;
            return $category->add($values);
        }
    }

    //删除类目（类别）
    public function actionDel($id = null)
    {
        $values = [
            'is_del' => 1,
        ];
        $category = new BkCategory();
        return $category->del($id, $values);
    }

    #首页的业务统计折线图 各个类别的每个月的成交额
    public function actionIncome()
    {
        global $_W;
        $categoryMd = new BkCategory();
        $uniacid = $_W['uniacid'];

        $list = $categoryMd->fetchListAll(['is_del' => 0, 'is_open' => 1]);
        $i = 0;
        foreach ($list as $key => $value) {
            $category = new BkCategory();
            $money = $category->monthmoney($value['id'], $uniacid);
            $list[$i]['money'] = $money;
            $i++;
        }
        return ['list' => $list];

    }

}
