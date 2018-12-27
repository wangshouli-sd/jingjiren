<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 14:05
 */

namespace frontend\models;

use common\models\base;
use common\models\Project;
use yii\db\ActiveRecord;
class FrProject extends Project
{
    //获取一条返回项目名称
    public function fetchName($where){
        $list=$this::find()
            ->where($where)
            ->asArray()
            ->one();
        return $list['name'];
    }

    // 获取项目列表
    public function actionIndex($name,$type,$psize,$page,$category_id)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $all = new Project();
        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'is_open', 1],
            ['like', 'name', $name],
            ['=', 'category_id', $category_id]
        ];
        if ($type == '2') {
            $order = ['vt_price_total' => SORT_DESC];//已成交单
        }
        if ($type == '1') {
            $order = ['create_time' => SORT_DESC];
        }
        $page_size = $psize == NULL ? '2' : $psize;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $all->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $all->fetchListAll($where, $limit, $offset, $order);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'pager' => $page,
        ];
    }


}