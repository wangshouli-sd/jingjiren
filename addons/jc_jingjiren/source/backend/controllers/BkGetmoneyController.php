<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/11
 * Time: 16:35
 */

namespace backend\controllers;

use backend\models\BkBank;
use backend\models\BkDistribution;
use backend\models\BkMember;
use backend\models\BkTask;
use common\models\Getmoney;
use common\models\GetmoneyLog;
use common\models\Pay;
use common\models\SysParams;
use common\models\WalletLog;
use yii;

class BkGetmoneyController extends BaseController
{
    //admin设置开启的提现方式 支付宝是alipay,银行卡是bank_member,自动打款是selfpushmoney
    public function actionSetway()
    {
        global $_GPC,$_W;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC['__input'];
        $name = $request_data['name'];
        $var = $request_data['var'];
        $sysparamsMd = new SysParams();
        if ($sysparamsMd->editOne($uniacid, $name, $var)){
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '成功',
                ]
            ];
        }else{
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败',
                ]
            ];
        }

    }

    //admin银行配置  bank_name,is_open,uniacid,order
    public function actionSetbank()
    {
        global $_GPC,$_W;
        $uniacid = $_W['uniacid'];
        $bankMd = new BkBank();
        $data =  $_GPC['__input'];
        $where = [
            'id' => $data['id'],
            'uniacid'=>$uniacid
        ];
        $datas['is_open']=$data['is_open'];
        return $bankMd->editOne($where, $datas);

    }

    //新增银行
    public function actionAddbank()
    {
        global $_W;
        $bankMd = new BkBank();
        global $_GPC;
        $data =  $_GPC['__input'];
        $data['uniacid']=$_W['uniacid'];
        return $bankMd->addOne($data);

    }

    //提现方式列表
    public function actionTypelist()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysMd = new SysParams();
        $request = yii::$app->request;
        $where = [
            'and',
            ['like', 'name', 'getmoney_'],
            ['=', 'is_del', '0'],
            ['=', 'uniacid', $uniacid],
        ];
        $list = $sysMd->fetchList($where, $limit = null, $offset = null, $order = null);
        if ($list==null){
            return [
//                ['id'=>'1','name'=>'getmoney_wechat','type'=>'0','typename'=>'微信钱包',''],
                ['id'=>'2','name'=>'getmoney_alipay','type'=>'0','typename'=>'支付宝',''],
                ['id'=>'3','name'=>'getmoney_bank','type'=>'0','typename'=>'银行卡',''],
            ];
        }
        $i=0;
        foreach ($list as $key =>$value){
            if ($value['name']=='getmoney_wechat'){
                $sysMd->delOne($uniacid,'getmoney_wechat');
            }
            $list[$i]['type']=$value['var'];

            if ($value['name']=='getmoney_alipay'){
                $list[$i]['typename']='支付宝';
            }elseif ($value['name']=='getmoney_bank'){
                $list[$i]['typename']='银行卡';
           // }else if ($value['name']=='getmoney_wechat'){
              //  $list[$i]['typename']='微信钱包';
            }
            $i++;
        }
        return $list;
    }

    //银行列表
    public function actionBanklist()
    {
        $bankMd = new BkBank();
        $list = $bankMd->fetchListAll(['is_del' => 0], $limit = null, $offset = null, $order = null);
        return ['list' => $list,];

    }

    //所有提现列表
    public function actionList()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $log = new GetmoneyLog();
        $memberMd = new BkMember();
        $getmoneyMd = new Getmoney();
        $request = yii::$app->request;
        $state = $request->get('state');
        $type = $request->get('type');//方式
        $page = $request->get('page');
        $size = $request->get('size');
        $number = $request->get('number');//编号

        $name = $request->get('name');//真实姓名
        if ($name!==''){
            $member_where = [
                'and',
                ['like', 'name', $name]
            ];
            $member = $memberMd->fetchListAll($member_where);
            if($member==null){
                return [
                    'list' => '',
                    'page' => ''
                ];
            }
            foreach ($member as $key => $var) {
                $ids[] = $var['id'];
            }
        }
        $where = [
            'and',
            ['=', 'uniacid', $uniacid],
            ['=', 'type', $type],
            ['=', 'is_del', 0],
            ['=', 'state', $state],
            ['like', 'number', $number],
            ['in', 'member_id', $ids],

        ];

        #分页设置
        $page_size = $size == NULL ? '20' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $log->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $log->fetchListAll($where, $limit, $offset, ['update_time'=>SORT_DESC]);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    //提现列表审核
    public function actionShen()
    {
        $log = new GetmoneyLog();
        global $_GPC;
        $data = $_GPC['__input'];
        $id = $data['id'];
        $value['state'] = ($data['state'] == 0) ? 0 : 2;
        $value['remarks'] = $data['remarks'];
        return $log->shen($id, $value);

    }

    //银行卡打款完成  改状态为已完成 1shi t通过 0是未通过
    public function actionShenpush()
    {
        $log = new GetmoneyLog();
        $request = Yii::$app->request;
        $id = $request->get('id');
        $state = $request->get('state');
        $value['state'] = ($state == 1) ? 3 : 0;
        $value['remarks'] =$request->get('remarks');
        return $log->shen($id, $value);

    }

    //个人佣金明细也的提现列表
    public function actionMylist()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $log = new GetmoneyLog();
        $sysMd=new SysParams();
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $member_id = $request->get('id');//人的id
        if (empty($member_id)){
            return false;
        }
        $number = $request->get('number');//提现编号
        $type_id = $request->get('type');//收入类型
        if ($type_id!=null){
            $types=$sysMd->one($type_id);
            if ($types['name']=='getmoney_alipay'){
                $type='1';
            }else if ($types['name']=='getmoney_bank'){
                $type='2';
            }else if ($types['name']=='getmoney_wechat'){
                $type='3';
            }
        }
        $where = [
            'and',
            ['=', 'uniacid', $uniacid],
            ['=', 'type', $type],
            ['=', 'is_del', 0],
            ['like', 'number', $number],
            ['=', 'member_id', $member_id],
            ['=','state','3']
        ];
        #分页设置
        $page_size = $size == NULL ? '2' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $log->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $log->fetchListAll($where, $limit, $offset, 'create_time');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    //删除提现
    public function actionDeltixian(){
        global $_W;
        $uniacid = $_W['uniacid'];
        $log = new GetmoneyLog();
        $request = yii::$app->request;
        $id = $request->get('id');
        $getmoneylgMd=new GetmoneyLog();
        if ($getmoneylgMd->delOne(['id'=>$id])){
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '成功',
                ]
            ];
        }else{
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败',
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
        if ($list!=null){
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
        if($wallartlist!=null){
            foreach ($wallartlist as $key =>$value){
                $pushmoney[]=$value['change_money'];
            }
            $yongjin=array_sum($pushmoney);
        }else {
            $yongjin='0';
        }
        }else if ($list==null){
                $all='0';
                $yongjin='0';
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

        return [
            'money' => $all,
            'getmoney' => $yongjin,
            'membercount' => $m_count,
            'taskcount' => $t_count,
        ];

    }



}
