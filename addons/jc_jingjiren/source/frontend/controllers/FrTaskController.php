<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 18:41
 */

namespace frontend\controllers;

use frontend\models\FrMember;
use frontend\models\FrProcess;
use frontend\models\FrProject;
use frontend\models\FrTask;
use frontend\controllers\BaseController;
use Yii;

class FrTaskController extends BaseController
{

    //我的任务总数
    public function actionCount()
    {
        $task = new FrTask();
        $count = $task->fetchCountNum();
        return $count;
    }

    //我的任务详情=======修改查出所有流程洽谈服务
    public function actionOne()
    {
        $TaskMd = new FrTask();
        $request = Yii::$app->request;
        $id = $request->get('id');
        $task = $TaskMd->fetchOne(['id' => $id]);
        if (empty($task) or $task==null){
            return false;
        }
        $list = $TaskMd->listone($task);
        return [
            'task' => $list,
        ];
    }

    //未通过详情页
    public function actionNotpass($id = 1)
    {
        $TaskMd = new FrTask();
        $task = $TaskMd->fetchOne(['id' => $id]);

        return $task;
    }

    //我的任务列表，各种状态state：0未通过，1洽谈中，2是服务中，3是已完成,4是待传合同，5是审核中，6是洽谈失败，7是服务中止
    public function actionMylist()
    {
        $memberMd = new FrMember();
        $request = Yii::$app->request;
        $psize = $request->get('psize');
        $page = $request->get('page');
        $state = $request->get('state');
        $openid = $request->get('openid');
        $name = $request->get('username');      // 搜索客户姓名
        $userid = $memberMd->memberid($openid)==null?'0':$memberMd->memberid($openid);

        $page_size = $psize == NULL ? '10' : $psize;
        $page_index = $page == NULL ? 1 : $page;
        $task = new FrTask();
        #洽谈中，服务中，代传合同，完成
        if ($state=='1' or $state=='2' or $state=='3' or $state=='4'){
            $list = $task->fetchListSate($state, $userid, $page_size, $page_index,$name);
        }else if ($state=='5'  or $state=='6' or $state=='7' or $state=='0'){
            $list = $task->fetchListSate_0($state, $userid, $page_size, $page_index,$name);
        }
        return $list;

    }

    #我的所有任务列表
    public function actionAllTask(){
        $memberMd = new FrMember();
        $taskMd=new FrTask();
        $request = Yii::$app->request;
        $psize = $request->get('psize');
        $page = $request->get('page');
        $openid = $request->get('openid');
        $search = $request->get('search');
        $id=$memberMd->memberid($openid);
        $page_size = $psize == NULL ? '2' : $psize;
        $page_index = $page == NULL ? 1 : $page;
        $where=[
            'and',
            ['=','send_id',$id],
            [
                'or',
                ['like', 'userphone', $search],     // 搜索客户手机
                ['like', 'username', $search],      // 搜索客户姓名
            ]
        ];
        $data_total = $taskMd->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $taskMd->fetchListAll($where, $limit, $offset, $order=['update_time'=>SORT_DESC]);
        $list_out=$taskMd->foreachlist($list);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list_out,
            'pager' => $page,
        ];

    }

}