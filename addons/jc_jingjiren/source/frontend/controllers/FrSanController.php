<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/31
 * Time: 8:31
 */

namespace frontend\controllers;

use common\models\SysParams;
use common\models\Upload;
use frontend\models\FrSan;
use frontend\controllers\BaseController;
use Yii;

class FrSanController extends BaseController
{
    //获取首页轮播图
    public function actionIndex()
    {
        $sys = new SysParams();
        $upload = new Upload();
        global $_W;
        $uniacid = $_W['uniacid'];
        $index = $sys->fetchOne($uniacid, 'index_banner');
        $path = $upload->info($index['var']);
        $i = 0;
        foreach ($path as $key => $value) {
            $path[$i]['imgurl'] = $value['path'];
            $i++;
        }
        return $path;
    }

    //获取工作台banner图
    public function actionBanner()
    {
        $sys = new SysParams();
        $upload = new Upload();
        global $_W,$_SERVER;
        $http=$_SERVER['HTTP_HOST'];
        $uniacid = $_W['uniacid'];
        $index = $sys->fetchOne($uniacid, 'index_banner');
        $path = $upload->fetchOne(['id'=>$index['var']]);
        return 'http://'.$http.$path['path'];
    }


    //获取首页中间三块 的广告图片和标题
    public function actionSan()
    {
        $sanMd = new FrSan();
        global $_W;
        $uniacid = $_W['uniacid'];
        $where = [
            'is_del' => 0,
            'type' => 2,
            'uniacid' => $uniacid
        ];
        $list = $sanMd->fetchListAll($where, $limit = '3');
        return $list;
    }

}