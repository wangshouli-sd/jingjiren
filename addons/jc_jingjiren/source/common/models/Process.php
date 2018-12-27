<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/5
 * Time: 17:50
 */

namespace common\models;

use backend\models\BkDistribution;
use backend\models\BkTask;

class Process extends Base
{
    static function tableName()
    {
        return "{{%process}}";
    }

    //安全属性
    public function scenarios()
    {
        return [
            'create' => [
                'uniacid', 'project_id', 'task_id', 'process_name', 'type',
                'content_type', 'price', 'order', 'create_time', 'update_time', 'state'
            ],
            'update' => ['process_name', 'type', 'content_type', 'price', 'update_time'],
            'shenhe' => ['state', 'type', 'update_time', 'uniacid'],
            'shen' => ['state', 'update_time'],
            'process' => ['state', 'update_time'],
            'del' => ['is_del'],
        ];
    }

    //获取项目流程状态
    public function fetchOne($where = array(), $order = array())
    {
        $list = $this::find()
            ->where($where)
            ->orderBy($order)
            ->asArray()
            ->one();
          //  $list['finish_time'] = date('Y-m-d h:i', $list['update_time']);
        return $list;
    }

    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = array())
    {
        $list = self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        $i = 0;
        foreach ($list as $key => $value) {
            if ($value['state'] == 3) {
                $list[$i]['finish_time'] = date('Y-m-d h:i', $value['update_time']);
                if ($value['content_type']=='2'){
                    $taskMd=new BkTask();
                    $taskinfo=$taskMd->fetchOne(['id'=>$value['task_id']]);
                    $list[$i]['process_price']=$value['price']/100*($taskinfo['total_price']*$taskinfo['back_percent']/100);
                }
            }
            $i++;
        }
        return $list;
    }

    //获取总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    //获取一条   流程
    public function One($id = null)
    {
        return self::findOne($id);
    }


    //新增项目流程
    public function add($process, $id, $uniacid)
    {
        $i = 1;
        foreach ($process as $var) {

            $add = new Process();
            $var = (array)$var;
            $var['project_id'] = $id;
            $var['task_id'] = 0;
            $var['type'] = 3;
            $var['order'] = $i;
            $var['state'] = 1;
            $var['uniacid'] = $uniacid;
            $var['create_time'] = time();
            $var['update_time'] = time();
            $add->scenario = 'create';
            $add->attributes = $var;
            $add->save();
            $i++;
        }
        return [
            'status' => [
                'state' => 'success',
                'msg' => '添加成功'
            ]];

    }

    //新增项目流程
    public function upprojectprocess($process, $id, $uniacid)
    {
        $processMd=new Process();
        $where=[
            'project_id'=>$id,
            'uniacid'=>$uniacid,
            'type'=>3,
            ];
        $list=$processMd->fetchListAll($where);
        if ($list==null or count($list)>=15){
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败'
                ]];
        }
        foreach ($list as $key =>$value){
            $customer = self::findOne($value['id']);
            $customer->delete();
        }
        return $this->add($process, $id, $uniacid);

    }

    //新增任务的流程  新增完服务
    public function add_task($process, $project_id, $task_id, $uniacid, $type)
    {
        $i = 1;
        foreach ($process as $var) {
            $add = new Process();
            $taskMd = new BkTask();
            $var = (array)$var;
            if ($i == 1) {
                $var['state'] = '3';
            }
            if ($i == 2){
                $var['state'] = '2';
            }
            $var['project_id'] = $project_id;
            $var['task_id'] = $task_id;
            $var['type'] = $type;
            $var['content_type'] = $var['content_type']=='1'?'2':'1';
            $var['uniacid'] = $uniacid;
            $var['order'] = $i;
            $var['create_time'] = time();
            $var['update_time'] = time();
            $add->scenario = 'create';
            $add->attributes = $var;
            $add->save();
            $i++;
        }
            $taskinfo=$taskMd->fetchOne(['id'=>$task_id]);
            if ($taskinfo['total_price']=='0' or $taskinfo['contract_id']==''){
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '添加成功'
                    ]];
            }else{

                if ($type == '2') {
                    $value = [
                        'state_progress' => '2',
                        'update_time' => time()
                    ];
                    $task = $taskMd->One($task_id);
                    $task->scenario = 'check';
                    $task->attributes = $value;
                    $task->save();
                }
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '添加成功'
                    ]];
            }

    }

    //编辑
    public function up($values = array(), $id = null)
    {
        $up = self::findOne($id);
        $up->scenario = 'update';
        $up->attributes = $values;
        $up->save();
        return [
            'status' => [
                'state' => 'success',
                'msg' => '编辑成功'
            ]];
    }

    //  审核
    public function shenhe($values = array(), $id = null)
    {
        $up = $this->One($id);
        $up->scenario = 'shenhe';
        $up->attributes = $values;
        $up->save();
        return [
            'state' => $values['state'],
            'type' => $values['type'],
            'status' => [
                'state' => 'success',
                'msg' => '编辑成功'
            ]];
    }

    // 审核任务流程
    public function shen($values = array(), $id = null)
    {
        $time = time();
        $shen = $this->One($id);
        $shen->scenario = 'shen';
        $shen->attributes = $values;
        $shen->save();
        return [
            'state' => $values['state'],
//            'type' => $values['type'],
            'update_time' => $time,
            'status' => [
                'state' => 'success',
                'msg' => '编辑成功'
            ]
        ];

    }
    // 审核任务下的流程  洽谈      服务     项目
    public function process($values, $id)
    {
        $distributionMd = new BkDistribution();
        $shen = self::findOne($id);
        $shen->scenario = 'process';
        $shen->attributes = $values;
        if ($shen->save()) {
            $info =self::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
            if ($info['content_type'] == 2 and $info['price'] != 0 and $info['price'] != null) {
                $dakuan = $distributionMd->sendmoney($id, $info['uniacid']);//如果涉及到金钱给经纪人打款
                if ($dakuan['status']['state'] == 'success') {
                    return 1;
                }
            } else {
                return 1;
            }
        }
    }

    //只是改变流程状态
    public function checkprocess($values, $id)
    {
        $shen = self::findOne($id);
        $shen->scenario = 'process';
        $shen->attributes = $values;
        if ($shen->save()) {
                return 1;
            }
    }

    // 查询 process表中的一条数据
    public function processOne($where = array())
    {
        $list = $this::find()
            ->where($where)
            ->asArray()
            ->one();
        return $list;
    }

    //删除
    public function del($id = null)
    {
        $values = ['is_del' => 1];
        $del = $this->One($id);
        $del->scenario = 'del';
        $del->attributes = $values;
        $del->save();
        return [
            'status' => [
                'state' => 'error',
                'msg' => '删除成功'
            ]];
    }

    //批量删除
    public function delall($id = array())
    {
        foreach ($id as $value) {
            $process = new Process();
            $del = $process->One($value);
            $del->scenario = 'del';
            $del->attributes = ['is_del' => 1];
            $del->save();
        }
        return 1;
    }
    //停止流程状态改为2
    public function stop($id = array())
    {
        $values = ['is_del' => '2'];
        $del = $this->One($id);
        $del->scenario = 'del';
        $del->attributes = $values;
        $del->save();
        return [
            'status' => [
                'state' => 'error',
                'msg' => '删除成功'
            ]];
    }
}
