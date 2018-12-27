<?php
/**
 * Created by PhpStorm.
 * User: jiechenhulian
 * Date: 2018/9/6
 * Time: 17:55
 */

namespace backend\controllers;

use backend\models\BkDistribution;
use backend\models\BkMember;
use backend\models\BkProcess;
use backend\models\BkTask;
use common\models\Formid;
use common\models\Member;
use common\models\Process;
use common\models\Project;
use common\models\SysParams;
use common\models\Task;
use common\models\Template;
use common\models\Upload;
use common\models\WalletLog;
use yii;

class BkCheckController extends BaseController
{
    #审核用户 通过state传2，不通过is_sender传0，
    public function actionMember()
    {
        $memberMd = new BkMember();
        $TemplatMd=new Template();
        $FormidMd=new Formid();


        $request = Yii::$app->request;
        $id = $request->get('id');
        $state = $request->get('state');
        $why = $request->get('why');    // 经纪人审核不通过理由
        $is_sender = ($state=='1')?'2':'0';
        $time = time();
        $json_error = [
            'status' => [
                'state' => 'error',
                'msg' => '失败',
            ]
        ];

        if ($id == '') {
            return $json_error;
        }

        $value = [
            'is_sender' => $is_sender,
            'jing_time' => $time,
            'sender_remark' => $why,
            'update_time' => time(),
        ];

        if (!empty($id)) {
            $update = $memberMd->fetchOne($id);
            $update->scenario = 'is_sender';
            $update->attributes = $value;
            $update->save();

            $time=strtotime('-7 days');
            $where=[
                'and',
                ['=','member_id',$id],
                ['>','create_time',$time],
                ['=','is_del','0'],
            ];
            $list_all=$FormidMd->fetchListAll($where, $limit = null, $offset = null, $order = ['create_time'=>SORT_ASC]);
            if ($state=='1'){#审核通过
                $TemplatMd->zhuce($update->openid,$list_all[0]['formid'],$update->onlyid,$update->name,$update->phone,'认证通过','无');
                $FormidMd->delOne(['formid'=>$list_all[0]['formid']]);
            } else if ($state=='0'){#审核不通过
                $TemplatMd->zhuce($update->openid,$list_all[0]['formid'],$update->onlyid,$update->name,$update->phone,'认证不通过',$why);
                $FormidMd->delOne(['formid'=>$list_all[0]['formid']]);
            }
            return [
                'status' => [
                    'state' => "success",
                    'msg' => "操作成功"
                ]
            ];
        }
    }

    #项目管理中的        审核流程      有洽谈中 已签约 已完成  未通过
    public function actionProcess()
    {
        global $_W, $_GPC;
        $process = new Process();
        $uniacid = $_W['uniacid'];
        $value =  $_GPC['__input'];
        //验证order唯一
        $type = $value['type'];     //process 表中的判断是什么类型的流程
        $state = $value['state'];
        $project_id = $value['project_id'];      //    project_id是process表中的project_id
        $task_id = $value['task_id'];              //    task_id 是process表中的task_id
        $id = $value['id'];         // id 就是process表中的id, $id = null
        $where = ['project_id' => $project_id, 'task_id' => $task_id, 'type' => $type];
        $tablelist = $process->fetchListAll($where);

        $now = strtotime('now');
        $value['update_time'] = $now;

        $values = [
            'type' => $type,
            'state' => $state,
            'project_id' => $project_id,
            'task_id' => $task_id,
            'uniacid' => $uniacid,
            'id' => $id,
            'update_time' => $now,
        ];
        foreach ($tablelist as $key => $value) {
            if (!empty($id)) {
                return $process->shenhe($values, $id);
            }
        }
    }

    #删除业务
    public function actionDel()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $ids=explode(',',$id);

        $update_time = time();

        if (empty($ids)) {
            return [
                'status' => [
                    'state' => "error",
                    'msg' => '没有选择'
                ]
            ];
        } else {
            foreach ($ids as $value){
                $taskMd = new BkTask();
                $is_del = $taskMd->One($value);
                $is_del->scenario = 'del';
                $is_del->attributes = ['is_del'=>'1','update_time'=>$update_time];
                $is_del->save();
            }
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '批量删除成功'
                ]
            ];
        }
    }

    #批量删除任务 业务管理
    public function actionDelAll(){
        global $_W, $_GPC;
        $request = Yii::$app->request;
        $id = $request->get('id');
        $ids=explode(',',$id);

        $update_time = time();

        if (empty($ids)) {
            return [
                'status' => [
                    'state' => "error",
                    'msg' => '没有选择'
                ]
            ];
        } else {
            foreach ($ids as $value){
                $taskMd = new BkTask();
                $is_del = $taskMd->One($value);
                $is_del->scenario = 'del';
                $is_del->attributes = ['is_del'=>'1','update_time'=>$update_time];
                $is_del->save();
            }
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '批量删除成功'
                ]
            ];
        }
    }

    #上传合同     contract
    public function actionContract()
    {
        global $_W;
        $uniacid=$_W['uniacid'];
        $request = Yii::$app->request;
        $id = $request->get('id');
        $contract_id = $request->get('contract_id');
        $price = $request->get('total_price');
        $taskMd = new Task();
        $projectMd = new Project();

        $error=[
            'status' => [
                'state' => "error",
                'msg' => '合同上传失败'
            ]
        ];
        $success=[
            'status' => [
                'state' => "success",
                'msg' => '合同上传成功'
            ]
        ];

        $task = $taskMd->fetchOne(['id'=>$id]);
        $vt_price_percent = $projectMd->fetchOne($task['project_id']);
        $back_percent = $vt_price_percent['vt_price_percent'];
        if (empty($id) or $price=='0' or $contract_id==null){
            return $error;
        }
        $processMd=new BkProcess();
        $where_pro=[
            'task_id'=>$id,
            'project_id'=>$task['project_id'],
            'is_del'=>0,
            'type'=>2,
            'uniacid'=>$uniacid
        ];
        $list_pro=$processMd->fetchListAll($where_pro);
        $value = [
            'contract_id' => $contract_id,
            'total_price' => $price,
            'back_percent' => $back_percent,
            'finish_time'=>time()
        ];
        if ($list_pro!=null){
            $value = [
                'contract_id' => $contract_id,
                'total_price' => $price,
                'back_percent' => $back_percent,
                'finish_time'=>time(),
                'state_progress' => '2',
            ];
        }
        if ($taskMd->editOne($id, $value)) {
            return $success;
        }else {
            return $error;
        }
    }

    #业务详情
    public function actionTaskDetail()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $task_id = $request->get('id');
        if (empty($task_id)) {
            return [
                'status' => [
                    'state' => "error",
                ]
            ];
        } else if (!empty($task_id)) {
            $one = new BkDistribution();
            $member = $one->taskinfo($task_id, $uniacid);   //业务详情信息
            $processMd = new BkProcess();
            if ($member['state_progress']=='1'){
                $type='1';
            }else if($member['state_progress']=='2'){
                $type='2';
            }else if($member['state_progress']=='3'){
                $type=['1','2'];
            }
            $where = [
                'project_id' => $member['project_id'],
                'task_id' => $task_id,
                'type'=>$type
            ];
            $process = $processMd->fetchlist($where, 'order');   // 流程展示
            $taskMd = new Task();
            $task = $taskMd->fetchOne(['id'=>$task_id]);

            $ids = $task['contract_id'];

            $uploadMd = new Upload();
            $imgurl = $uploadMd->info($ids);
            return [
                'member' => $member,
                'process' => $process,
                'contract' => $imgurl
            ];
        }
    }

    #重新编辑为通过的原因
    public function actionEditnotpass(){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $id = $request->get('id');
        $why = $request->get('why');
        $taskMd=new BkTask();
        $where=[
            'id'=>$id
        ];
        $params=[
            'state_why'=>$why,
            'update_time'=>time()
        ];
        if ($taskMd->editOne($where,$params)){
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '成功'
                ]
            ];
        }else{
            return [
                'status' => [
                    'state' => "error",
                    'msg' => '成功'
                ]
            ];
        }


    }

    #首页财务中心：本月签单总额，发放佣金。 经纪人人数，累计完成业务
    public function actionTotalMoney()
    {
        $taskMd = new BkTask();
        $memberMd=new BkMember();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $state_check = 2;      // 任务审核状态 1：审核中/2：审核通过/0：未通过
        $state_progress = 3;     //  流程进度 1：洽谈中/2：服务中/3：已完成
        $timestamp = strtotime("now");
        $start_time = date( 'Y-m-1 00:00:00', $timestamp );
        $start_timea = strtotime($start_time);
        $mdays = date( 't', $timestamp );
        $end_time = date( 'Y-m-' . $mdays . ' 23:59:59', $timestamp );
        $end_timea = strtotime($end_time);

        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'state_check', $state_check],
            ['=', 'state_progress', $state_progress],
            ['between','update_time',$start_timea,$end_timea]
        ];
        $list = $taskMd->fetchListAlla($where);
        foreach ($list as $key => $value) {
            $price[]=$value['total_price'];//签单总额
        }
        $all=array_sum($price);
        $wallertlog=new WalletLog();
        $where=[
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=','type','1'],
            ['in','name',['main','son','grand_son']],
            ['between','update_time',$start_timea,$end_timea]
        ];
        $wallartlist=$wallertlog->fetchListAll($where);
        foreach ($wallartlist as $key =>$value){
            $pushmoney[]=$value['change_money'];
        }

        $where_member=[
            'is_sender'=>2,
            'blacklist'=>1,
            'is_del'=>0,
            'uniacid'=>$uniacid
        ];
        $m_count=$memberMd->fetchCountNum($where_member);

        $where_task=[
            'state_progress'=>3,
            'uniacid'=>$uniacid,
            'is_del'=>0,
            'is_stop'=>0,
            'state_check'=>2,
        ];
        $t_count=$taskMd->fetchCountNum($where_task);

        $yongjin=array_sum($pushmoney);
        return [
            'money' => $all,
            'getmoney' => $yongjin,
            'membercount' => $m_count,
            'taskcount' => $t_count,
        ];

    }
}
