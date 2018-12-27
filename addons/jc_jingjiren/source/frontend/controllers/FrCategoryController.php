<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/28
 * Time: 9:41
 */

namespace frontend\controllers;

use frontend\controllers\BaseController;
use frontend\models\FrCategory;
class FrCategoryController extends BaseController
{
    //获取所有分类名称
    public function actionIndex(){
       $cate=new FrCategory();
        $where=[
            'is_del'=>0,
            'is_open'=>1,
            'is_index'=>1,
        ];
       $list=$cate->fetchListAll($where, $like = array(), $limit = array(), $offset = null, $order = null);
        return $list;
    }
}