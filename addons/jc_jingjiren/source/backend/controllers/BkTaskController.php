<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 18:41
 */

namespace backend\controllers;

use backend\models\BkDistribution;
use backend\models\BkProcess;
use backend\models\BkProject;
use backend\models\BkTask;
use common\models\Formid;
use common\models\Member;
use common\models\Project;
use common\models\Template;
use yii;

class BkTaskController extends BaseController
{
    #获取任务列表 check： 1是审核中，2是通过 0是审核不通过。 state： 1洽谈 2服务 4 待传合同 3 完成 。stop:1是打断 0是不打断
    public function actionIndex()
    {
        $taskMd = new BkTask();
        $projectMd = new BkProject();
        global $_W;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $state_check = $request->get('check');
        $stop = $request->get('stop');//1是停止
        $state_process = $request->get('state');
        $name = $request->get('name');
        $category_id = $request->get('category_id');
        $projectlist = $projectMd->fetchListAll(['category_id' => $category_id, 'is_del' => 0]);//通过分类的id取出相应的项目id
        if (!empty($category_id) and $projectlist == null) {
            return [
                'list' => '',
                'page' => $page
            ];
        }
        foreach ($projectlist as $key => $value) {
            $project_ids[] = $value['id'];
        }
        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'state_check', $state_check],
            ['=', 'is_stop', $stop],
            ['=', 'state_progress', $state_process],
            ['like', 'username', $name],
            ['in', 'project_id', $project_ids],
        ];
        #分页设置
        $page_size = $size == NULL ? '20' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $taskMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $taskMd->fetchListAll($where, $limit, $offset, $order = ['update_time' => SORT_DESC]);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    #审核任务 state 1是通过，0是不通过 state_why通过之后设置任务流程 新增
    public function actionShentask()
    {
        global $_W, $_GPC;
        $processMd = new BkProcess();
        $taskMd = new BkTask();
        $teleMd = new Template();
        $FormidMd = new Formid();
        $value = $_GPC['__input'];
        $uniacid = $_W['uniacid'];
        $task_id = empty($value['id']) ? '0' : $value['id'];
        $state = $value['state'] == '1' ? '2' : '0';//2是通过 0是不通过
        $why = $value['why'];//不通过的理由
        $where = ['id' => $task_id];
        $pro = ($state == '0') ? '0' : '1';
        $params = [
            'state_check' => $state,
            'state_why' => $why,
            'state_progress' => $pro
        ];
        $taskinfo = $taskMd->fetchOne(['id' => $task_id]);
        $projectinfo = Project::find()->where(['id' => $taskinfo['project_id']])->asArray()->one();
        $memberinfo = Member::find()->where(['id' => $taskinfo['send_id']])->asArray()->one();
        #模板消息
        $time = strtotime('-7 days');
        $where_form = [
            'and',
            ['=', 'member_id', $taskinfo['send_id']],
            ['>', 'create_time', $time],
            ['=', 'is_del', '0'],
        ];
        $list_all = $FormidMd->fetchListAll($where_form, $limit = null, $offset = null, $order = ['create_time' => SORT_ASC]);
        if ($taskMd->editOne($where, $params)) {
            if ($state == '2') {
                $teleMd->ShenHe($memberinfo['openid'], $list_all[0]['formid'], $taskinfo['task_number'], $projectinfo['name'] . '介绍单', '审核通过', '无', date('Y-m-d', $taskinfo['create_time']));
                $FormidMd->delOne(['formid' => $list_all[0]['formid']]);
                $process = (array)$value['process'];
                $task = $taskMd->fetchOne(['id' => $task_id]);
                $project_id = $task['project_id'];
                return $processMd->add_task($process, $project_id, $task_id, $uniacid, 1);
            } else if ($state == '0') {
                $teleMd->ShenHe($memberinfo['openid'], $list_all[0]['formid'], $taskinfo['task_number'], $projectinfo['name'] . '介绍单', '审核不通过', $why, date('Y-m-d', $taskinfo['create_time']));
                $FormidMd->delOne(['formid' => $list_all[0]['formid']]);
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '审核成功'
                    ]];
            }
        }
    }

    #新增服务流程
    public function actionAddprocess()
    {
        global $_W, $_GPC;
        $processMd = new BkProcess();
        $taskMd = new BkTask();

        $value = $_GPC['__input'];

        $uniacid = $_W['uniacid'];
        $process = (array)$value['process'];
        $task_id = $value['id'];
        $taskinfo = $taskMd->fetchOne(['id' => $task_id]);
        if ($taskinfo['contract_id'] == null and $taskinfo['total_price'] == '0') {
            return [
                'status' => [
                    'state' => 'contract',
                    'msg' => '没有设置合同或者'
                ]];
        }
        return $processMd->add_task($process, $taskinfo['project_id'], $task_id, $uniacid, '2');

    }

    #审核任务的具体洽谈和服务流程
    public function actionShenprocess()
    {
        global $_W, $_GPC;
        $processMd = new BkProcess();

        $value = $_GPC['__input'];
        $uniacid = $_W['uniacid'];
        $id = $value['id'];//流程id
        $pro = $processMd->processOne(['id' => $id]);//一条流程
        $type = $pro['type'];//1是洽谈2是服务
        if ($pro['state'] == 2) {
            $params = [
                'and',
                ['>', 'order', $pro['order']],
                ['=', 'task_id', $pro['task_id']],
                ['=', 'type', $type],
            ];
            $next_prolist = $processMd->fetchListAll($params, $limit = null, $offset = null, ['order' => SORT_ASC]);//所有当前流程的下几条
            $next_pro = $next_prolist['0'];//下一条流程
            $where = [
                'state' => 3,
                'update_time' => time()
            ];
            $this_pro = $processMd->process($where, $id);//当前流程状态改变
            if ($next_pro !== null) {//如果不是是最大一条 有下一步流程
                if ($this_pro == 1) { //上一条状态改变完成
                    $where_next = [
                        'state' => 2,
                        'update_time' => time()
                    ];
                    $next_shen = $processMd->checkprocess($where_next, $next_pro['id']);//下条流程状态改变
                    if ($next_shen == 1) {
                        return [
                            'status' => [
                                'state' => 'success',
                                'msg' => '成功1'
                            ]];
                    }
                }
            } else {
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '成功1'
                    ]];
            }
        }
    }

    #审核任务 从洽谈过渡到代传合同 从服务中到已完成 停止任务 state 0是停止或不通过，1是通过
    public function actionStop()
    {
        global $_W, $_GPC;
        $task = new BkTask();
        $values = $_GPC['__input'];
        $admin = $_W['openid'];
        $task_id = $values['id'];
        $state = $values['state'];
        $why = $values['why'];
        $now = strtotime('now');
        if (empty($task_id)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '找不到该任务'
                ]];
        } else {
            if ($state == 0) {
                $value['is_stop'] = 1;
                //$value['stop_id'] = $admin;
                $value['stop_state'] = 2;
                $value['stop_remark'] = $why;
                $value['update_time'] = $now;
                return $task->stopOne($value, $task_id);
            } else
                if ($state == 1) {
                    $taskMd = new BkTask();
                    $taskinfo = $taskMd->fetchOne(['id' => $task_id]);
                    if ($taskinfo['state_progress'] == 1) {
                        $state_process = 4;
                    } else if ($taskinfo['state_progress'] == 4) {
                        $state_process = 2;
                    } else if ($taskinfo['state_progress'] == 2) {
                        $state_process = 3;
                    }
                    if ($taskMd->editOne(['id' => $task_id], ['state_progress' => $state_process, 'update_time' => time()])) {
                        return [
                            'status' => [
                                'state' => 'success',
                                'msg' => '成功'
                            ]];
                    }
                }
        }

    }
}
