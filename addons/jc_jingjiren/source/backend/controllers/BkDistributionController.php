<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/7
 * Time: 14:13
 */

namespace backend\controllers;


use backend\models\BkDistribution;
use backend\models\BkTask;
use common\models\SysParams;
use common\models\Upload;
use common\models\WalletLog;
use yii;

class BkDistributionController extends BaseController
{

    #设置分销海报参数   新增，编辑
    public function actionSetposter(){
        global $_GPC,$_W;
        $uniacid = $_W['uniacid'];

        $request_data =  $_GPC['__input'];

        $sysparamsMd = new SysParams();
        $postname = empty($request_data['name'])? '': $request_data['name'];//海报名
        $tap = empty($request_data['tap']) ? 1: $request_data['tap'];//海报模板
        $backimg = empty($request_data['backimg']) ? '': $request_data['backimg'];//海报背景图

        $addpostname = $sysparamsMd->editOne($uniacid, 'poster_name', $postname);
        $addposttap = $sysparamsMd->editOne($uniacid, 'poster_tap', $tap);
        $addpostimg = $sysparamsMd->editOne($uniacid, 'poster_backimg', $backimg);
        if ($addpostname ==true and $addposttap==true and $addpostimg==true){
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '设置海报成功',
                ]
            ];
        }

    }

    #展示海报参数
    public function actionShowposter(){
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $uploadMd=new Upload();
        $postername = $sysparamsMd->fetchOne($uniacid, 'poster_name');
        $postertap = $sysparamsMd->fetchOne($uniacid, 'poster_tap');
        $posterbackimg = $sysparamsMd->fetchOne($uniacid, 'poster_backimg');
        $path=$uploadMd->fetchOne(['id'=>$posterbackimg['var']]);
        return [
            'name'=>$postername['var'],
            'tap'=>$postertap['var'],
            'backimg'=>$posterbackimg['var'],
            'files'=>$path['path']

        ];
    }

    //设置分销开关和佣金比例
    public function actionSetpercent()
    {
        global $_GPC,$_W;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC['__input'];

        $fenxiao = $request_data['fenxiao'];//分销开关 0，1，2
        if ($fenxiao==null){
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '设置失败',
                ]
            ];
        }
        if ($fenxiao=='0'){
            $one='0';
            $two='0';
        }else if($fenxiao=='1'){
            $one = empty($request_data['one']) ? 0 : $request_data['one'];
            $two='0';
        } else if ($fenxiao=='2'){
            $one = empty($request_data['one']) ? 0 : $request_data['one'];
            $two = empty($request_data['two']) ? 0 : $request_data['two'];
        }
        $var = $one . ',' . $two;

        $sysparamsMd = new SysParams();
        $addfenxaio = $sysparamsMd->editOne($uniacid, 'is_sale', $fenxiao);
        $addparent = $sysparamsMd->editOne($uniacid, 'price_percent', $var);
        if ($addfenxaio == true and $addparent == true) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '设置成功',
                ]
            ];
        }
    }

    //展示设置的分销参数
    public function actionGetfenxiao()
    {
        global$_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $request = Yii::$app->request;
        $is_sale = $sysparamsMd->fetchOne($uniacid, 'is_sale');
        $price_precent = $sysparamsMd->fetchOne($uniacid, 'price_percent');
        $percent = explode(',', $price_precent['var']);
        return [
            'fenxiao' => $is_sale['var'],
            'one' => $percent['0'],
            'two' => $percent['1'],
        ];
    }

    //个人账户收支明细  获得的佣金
    public function actionDetailMoney()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $walletMd = new WalletLog();
        $taskMd = new BkTask();
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $member_id = $request->get('id');
        if (empty($member_id)){
            return false;
        }
        $task_number = $request->get('task_number');//订单编号
        $task_where = [
            'and',
            ['like', 'task_number', $task_number],
            ['=', 'is_del', 0]
        ];
        $task = $taskMd->fetchListAll($task_where);
        foreach ($task as $key => $value) {
            $task_ids[] = $value['id'];
        }
        $types = $request->get('type');//收入类型
        $type=($types=='grand')?'grand_son':$types;
        $where = [
            'uniacid' => $uniacid,
            'member_id' => $member_id,
            'type' => 1,
            'is_del' => 0,
            'name' => $type,
            'task_id' => $task_ids
        ];
        #分页设置
        $page_size = $size == NULL ? '20' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $walletMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $walletMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];

    }

}