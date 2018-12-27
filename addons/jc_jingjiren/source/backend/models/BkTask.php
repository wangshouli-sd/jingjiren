<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 15:01
 */

namespace backend\models;

use common\models\Base;
use common\models\Distribution;
use common\models\Member;
use common\models\Process;
use common\models\SysParams;
use common\models\Task;
use common\models\Upload;
use common\models\WalletLog;
use yii;


class BkTask extends Task
{


    //获取任务列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $list= parent::fetchListAll($where, $limit, $offset, $order);
        $i=0;
        foreach ( $list as $key =>$value){
            $projectMd=new BkProject();
            $memberMd=new BkMember();
            $uploadMd=new Upload();
            $processMd = new Process();

            $list[$i]['time'] = date('Y/m/d H:i:s', $value['update_time']);
            $projectinfo = $projectMd->fetchOne($value['project_id']);
            $list[$i]['taskname'] = $projectinfo['name']; // 业务名称 项目名
            $list[$i]['tags'] = $projectinfo['tags'];  // 标签

            $img=$uploadMd->fetchOne(['id'=>$projectinfo['img']]);
            $list[$i]['img_path'] = $img['path'];    // 缩略图

            $member = $memberMd->fetchOne($value['send_id']); // 经纪人名字
            $list[$i]['name'] = $member['name'];
            $params = [
                'task_id' => $value['id'],
                'state' => 2,
                'type' => $value['state_progress'],
                'is_del' => 0,
            ];
            $process = $processMd->processOne($params);
            $list[$i]['processname'] = ($process['process_name']==null)?'已完成':$process['process_name'];    // 当前进行中流程名字

            $process=$processMd->fetchListAll(['task_id'=>$value['id'],'type'=>2,]);

            $list[$i]['setprocess'] = ($process==null)?'0':'1';//待传合同列表判断又没有设置服务流程 0是为设置流程1是设置了流程

            $type=($value['state_progress']==3)?'2':($value['state_progress']==1?1:2);
            $where=[
                'task_id'=>$value['id'],
                'type'=>$type,
                'is_del'=>'0',
                'project_id'=>$value['project_id']
            ];
            $list[$i]['process']=$processMd->fetchListAll($where,$limit = null, $offset = null, ['type' => SORT_ASC,'order' => SORT_ASC]);    // 流程名字

            $vaprice = $projectMd->fetchOne($value['project_id']);// 预计佣金
            $vt_price = explode(',', $vaprice['vt_price']);
            $list[$i]['vt_price'] = $vaprice['vt_price'];     // 预计佣金
            $list[$i]['min_price'] = $vt_price[0];     // 预计佣金最小值
            $list[$i]['max_price'] = $vt_price[1];     // 预计佣金最大值

            if ($value['total_price']!=0 and $value['back_percent']!=0){
            $yj=$list[$i]['all_yongjin'] =floatval($value['total_price'])*floatval($value['back_percent'])/100;
                $fenxiaoMd=new Distribution();
                $sysMd=new SysParams();
                $level=$sysMd->fetchOneVar($value['uniacid'],'is_sale');//分销等级
                $percen=$sysMd->fetchOneVar($value['uniacid'],'price_percent');//分销等级
                $percent=explode(',',$percen);
                $one_price=$percent[0]*$value['total_price']/100;
                $two_price=$percent[1]*$value['total_price']/100;

                $info=$fenxiaoMd->one(['main_id'=>$value['send_id']]);

                $onemember=$memberMd->one(['id'=>$info['one_level']]);
                $twomember=$memberMd->one(['id'=>$info['two_level']]);
                if($value['state_progress']=='2'){
                if ($onemember['blacklist']=='1' and $onemember['is_sender']=='2' and $onemember['is_del']=='0'){
                          $one=1;
                }
                if ($twomember['blacklist']=='1' and $twomember['is_sender']=='2' and $twomember['is_del']=='0'){
                    $two=1;
                }
                if ($one==1 and $two==1){
                    $list[$i]['all_yongjin']=(string)($yj+$one_price+$two_price);// 所发放的佣金
                }elseif ($one==1 and  $two!=1){
                    $list[$i]['all_yongjin']=(string)($yj+$one_price);// 所发放的佣金
                }elseif ($one!=1 and  $two==1){
                    $list[$i]['all_yongjin']=(string)($yj+$two_price);// 所发放的佣金
                }else {
                    $list[$i]['all_yongjin']=(string)$yj;// 所发放的佣金
                }
                }
                elseif ($value['state_progress']=='3'){
                    $wallretMd=new WalletLog();

                    $wallret_list=$wallretMd->taskmoney($value['id'],['main','son','grand_son']);

                    $list[$i]['all_yongjin']=$wallret_list;
                }
            }
            $i++;
        }
        return $list;
    }

    //首页 列表
    public function  fetchListindex($where = array(), $limit = null, $offset = null, $order = null){

        $list= parent::fetchListAll($where, $limit, $offset, $order);
        $i=0;
        foreach ( $list as $key =>$value){
            $projectMd=new BkProject();
            $memberMd=new BkMember();
            $uploadMd=new Upload();
            $processMd = new Process();

            $list[$i]['time'] = date('Y/m/d H:i:s', $value['update_time']);
            $projectinfo = $projectMd->fetchOne($value['project_id']);
            $list[$i]['taskname'] = $projectinfo['name']; // 业务名称 项目名
            $list[$i]['tags'] = $projectinfo['tags'];  // 标签

            $img=$uploadMd->fetchOne(['id'=>$projectinfo['img']]);
            $list[$i]['img_path'] = $img['path'];    // 缩略图

            $member = $memberMd->fetchOne($value['send_id']); // 经纪人名字
            $list[$i]['name'] = $member['name'];
            $params = [
                'task_id' => $value['id'],
                'state' => 2,
                'type' => $value['state_progress'],
                'is_del' => 0,
            ];
            $process = $processMd->processOne($params);
            $list[$i]['processname'] = ($process['process_name']==null)?'已完成':$process['process_name'];    // 当前进行中流程名字

            $process=$processMd->fetchListAll(['task_id'=>$value['id'],'type'=>2,]);
            $list[$i]['setprocess'] = ($process==null)?'0':'1';//待传合同列表判断又没有设置服务流程

            $type=($value['state_progress']==3)?'2':($value['state_progress']==1?1:2);
            $where=[
                'task_id'=>$value['id'],
                'type'=>$type,
                'is_del'=>'0'
            ];
            //$list[$i]['process']=$processMd->fetchListAll($where,$limit = null, $offset = null, ['type' => SORT_ASC,'order' => SORT_ASC]);    // 流程名字

            $vaprice = $projectMd->fetchOne($value['project_id']);// 预计佣金
            $vt_price = explode(',', $vaprice['vt_price']);
            $list[$i]['vt_price'] = $vaprice['vt_price'];     // 预计佣金
            $list[$i]['min_price'] = $vt_price[0];     // 预计佣金最小值
            $list[$i]['max_price'] = $vt_price[1];     // 预计佣金最大值

            if ($value['total_price']!=0 and $value['back_percent']!=0){
                $yj=$list[$i]['all_yongjin'] =intval($value['total_price'])*intval($value['back_percent'])/100;
                $list[$i]['all_yongjin']=(string)$yj;// 预计佣金最大值
            }
            $i++;
        }
        return $list;

    }
    /*返回列表*/
    public function fetchListAlla($where = array(), $limit = null, $offset = null, $order = null)
    {

        return self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
    }

    //获取任务总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where); // TODO: Change the autogenerated stub
    }

    //获取一条   任务详情页
    public function One($id)
    {
        return self::findOne($id);
    }

    // 业务删除
    public function del($id = null, $values = array())
    {
        $shan = $this->One($id);
        if (empty($shan)) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '删除失败'
                ]];
        }
        $shan->scenario = 'del';
        $shan->attributes = $values;
        $shan->save();
        return [
            'status' => [
                'state' => 'success',
                'msg' => '删除成功'
            ]];
    }


    #删除任务
    public function deleteOne($values = array(), $task_id = null)
    {
        $process = new BkProcess();
        $where = [
            'task_id' => $task_id,
            'state' => 2
        ];
        $process_list = $process->fetchListAll($where);
        if (!empty($process_list)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '流程进行中，不能删除'
                ]];
        }
        $del = $this->fetchOne($task_id);
        $del->scenario = 'del';
        $del->attributes = $values;
        $del->save();
        return [
            'status' => [
                'state' => 'success',
                'msg' => '删除成功'
            ]];
    }

    #停止任务
    public function stopOne($values , $task_id )
    {
        global $_W;
        $stop = self::findOne($task_id);
        $stop->scenario = 'stop';
        $stop->attributes = $values;
        if ( $stop->save()){

            $processMd=new Process();
            $list=$processMd->fetchListAll(['task_id'=>$task_id,'uniacid'=>$_W['uniacid'],'is_del'=>'0']);
            if ($list!=null){
                foreach ($list as $key =>$value){
                    $proMd=new Process();
                    $proMd->stop($value['id']);
                }
            }
        }
        return [
            'status' => [
                'state' => 'success',
                'msg' => '成功停止任务'
            ]];
    }

    #设置总价和返佣比例
    public function set($scenario = null, $task_id = null, $value = array())
    {
        $where = [
            'id' => $task_id
        ];
        $task = $this->fetchOne($task_id);
        $task->scenario = $scenario;
        $task->attributes = $value;
        $task->save();
        return [
            'status' => [
                'state' => 'success',
                'msg' => '操作成功'
            ]];
    }

    #查询task表中的一条数据
    public function taskOne($where = array())
    {
        $list = $this::find()
            ->where($where)
            ->asArray()
            ->one();
        return $list;
    }

    // 更改task表中的state_process
    // 审核任务下的流程  洽谈      服务     项目
    public function task($id = null, $values = array())
    {
        $time = time();
        $shen = $this->One($id);
        $shen->scenario = 'taskup';
        $shen->attributes = $values;
        $shen->save();
        return [
            'state_progress' => $values['state_progress'],
            'update_time' => $time,
            'status' => [
                'state' => 'success',
                'msg' => '编辑成功'
            ]
        ];
    }



}
