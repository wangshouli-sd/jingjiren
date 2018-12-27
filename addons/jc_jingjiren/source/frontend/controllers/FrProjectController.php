<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 18:14
 */

namespace frontend\controllers;

use common\models\Banner;
use common\models\SysParams;
use common\models\Upload;
use common\tools\PageJson;
use frontend\models\FrCategory;
use frontend\models\FrMember;
use frontend\models\FrNotice;
use frontend\models\FrProcess;
use frontend\models\FrProject;
use frontend\models\FrSan;
use yii;

class FrProjectController extends BaseController
{
    // 首页整合信息
    public function actionInfo()
    {
        $sys = new SysParams();
        $upload = new Upload();     // 上传
        $sanMd = new FrSan();
        $projectMd = new FrProject();
        $noticeMd = new FrNotice();
        $bannerMd = new Banner();       // banner 图
        //$request = yii::$app->request;
        //$category_id = $request->get('category_id');
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $category_id = $_GPC['cate'];
        // 首页轮播图
        $index = $sys->fetchOne($uniacid, 'index_banner');
        $path = $upload->info($index['var']);
        $i = 0;
        foreach ($path as $key => $value) {
            $path[$i]['imgurl'] = $value['path'];
            $i++;
        }
        // 中间四个小东西
        $where = [
            'is_del' => 0,
            'type' => 2,
            'uniacid' => $uniacid
        ];
        $four = $sanMd->fetchListAll($where, $limit = '5');
        // 更多项目
        $where = [
            'is_del' => 0,
            'uniacid' => $uniacid,
        ];
        // $more = $projectMd->fetchListAll($where, $limit = '1');
        // 公告

        $request = Yii::$app->request;
        $limit = $request->get('limit');
        $setlimit = $limit == NULL ? '3' : $limit;
        $where = [
            'uniacid' => $uniacid
        ];
        $gonggao = $noticeMd->fetchListAll($where, $setlimit, $offset = null, $order = 'create_time DESC');

        // 更改后的轮播加跳转链接
        $where = [
            'uniacid' => $uniacid,
        ];
        $list = $bannerMd->fetchListAll($where);
        $i = 0;
        foreach ($list as $key => $value) {
            global $_W, $_SERVER;
            $http = $_SERVER['HTTP_HOST'];
            $list[$i]['id'] = $value['img_patch'];
            $list[$i]['img'] = $upload->getimg($list[$i]['id']);
            $list[$i]['img_patch'] = 'http://' . $http . $list[$i]['img'];
            $i++;
        }
        return [
            // 'banner' => $path,     // 轮播图
            'xmtab' => $four,         // 首页中间四个小东西
            // 'more' => $more,          // 更多项目
            'gglist' => $gonggao,    // 首页公告
            'luobo' => $list,         // 跳转链接
        ];
    }
    //获取项目列表 $type是区分热门和最新的，热门传2，最新传1
    //搜索的话，也是传到这里，搜索框name是
    public function actionIndex()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $request = yii::$app->request;
        $name = $request->get('name');
        $type = $request->get('type');
        $psize = $request->get('psize');
        $page = $request->get('page');
        $category_id = $request->get('cate');
        $all = new FrProject();
        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'is_open', 1],
            ['like', 'name', $name],
            ['=', 'category_id', $category_id]
        ];
        if ($type == '2') {
            $order = ['order'=>SORT_DESC,'vt_price_total' => SORT_DESC];//已成交单
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

    //获取一条项目 详情
    public function actionOne()
    {
        global $_W;
        $request = Yii::$app->request;
        $openid = $request->get('openid');
        $id = $request->get('id');
        $one = new FrProject();
        $memberMd = new FrMember();
        $process = new FrProcess();
        $where = ['project_id' => $id, 'type' => 3, 'is_del' => 0];
        $pro = $process->fetchListAll($where, $order = 'order');
        $project = $one->fetchOne($id);
        $like = $memberMd->like($openid, $id);//是否收藏
        $project['like'] = $like['state'];
        $cate = new FrCategory();
        $title = $cate->fetchOne(['id' => $project['category_id'], 'is_del' => 0]);
        return [
            'category' => $title,
            'project' => $project,
            'process' => $pro,
        ];
    }
}
