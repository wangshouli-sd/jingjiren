<?php
/**
 * Created by PhpStorm.
 * User: wsl
 * Date: 2018/12/3
 * Time: 15:39
 */

namespace frontend\controllers;


use common\models\Banner;
use common\models\Upload;

class FrBannerController extends BaseController
{
    // 获取首页轮播图
    public function actionIndex()
    {
        $bannerMd = new Banner();
        $upload = new Upload();
        global $_W,$_GPC;

        $uniacid = $_W['uniacid'];
        $banner_id = $_GPC['banner_id'];
        $where = [
            'uniacid' => $uniacid,
            'banner_id' => $banner_id
        ];
        $detail = $bannerMd->one($where);

        return $detail;
    }
}