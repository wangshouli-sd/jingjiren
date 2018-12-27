<?php
/**
 * Created by PhpStorm.
 * User: zmy
 * Date: 18-10-12
 * Time: 下午4:57
 */

namespace backend\controllers;

use common\models\Customer;
use common\models\Member;
use common\models\Process;
use common\models\Task;
use frontend\models\FrProject;
use Yii;

class BkCustomerController extends BaseController
{
    #客户列表
    public function actionCustomerList(){
        global $_GPC,$_W;
        $customerMd=new Customer();
        $memberMd=new Member();
        $jing=$_GPC['jing'];
        $mem_where=[
            'and',
            ['like','name',$jing]
        ];
        $memberlist=$memberMd->fetchListAll($mem_where);
        if ($memberlist!=null){
            foreach ($memberlist as $key =>$value){
                $ids[]=$value['id'];
            }
        }
        $where=[
            'and',
            ['=','is_del','0'],
            ['like','customer_name',$_GPC['name']],
            ['like','customer_phone',$_GPC['phone']],
        ];
        $page_size = $_GPC['size'] == NULL ? '15' : $_GPC['size'];     // 每页显示的个数
        $page_index = $_GPC['page'] == NULL ? 1 : $_GPC['page'];        // 第几页
        $data_total = $customerMd->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list=$customerMd->fetchListAll($where,$limit, $offset,$order=['create_time'=>SORT_DESC]);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];

    }

    #客户详情
    public function actionCustomerInfo($id){
        global $_GPC,$_W;
        $customerMd=new Customer();
        $taskMd=new Task();
        $memberMd=new Member();
        $info=$customerMd->fetchOne(['id'=>$id]);
        $where=[
            'username'=>$info['customer_name'],
            'userphone'=>$info['customer_phone'],
        ];
        $list=$taskMd->fetchListAll($where);
        if ($list!=null){
            $i=0;
            foreach ($list as $key=>$value){
                $projectMd=new FrProject();
                $ProcessMd=new Process();
                $projectinfo= $projectMd->fetchOne($value['project_id']);
                $list[$i]['projectname']=$projectinfo['name'];
                $list[$i]['subname']=$projectinfo['subname'];
                $where=[
                    'project_id'=>$value['project_id'],
                    'task_id'=>$value['id'],
                    'state'=>'2'
                ];
                $process=$ProcessMd->fetchOne($where);
                $list[$i]['nowliucheng']=$process['process_name'];
                $type=$value['state_progress'];
                if ($value['state_progress']=='3'){
                    $type=['1','2'];
                }
                $pro_where=[
                    'task_id'=>$value['id'],
                    'project_id'=>$value['project_id'],
                    'type'=>$type,
                    'is_del'=>'0',
                    'uniacid'=>$_W['uniacid'],
                ];
                $list[$i]['process']=$ProcessMd->fetchListAll($pro_where);
                $list[$i]['jing_name']=$memberMd->one(['id'=>$value['send_id']])['name'];
                $list[$i]['jing_phone']=$memberMd->one(['id'=>$value['send_id']])['phone'];
                $list[$i]['create_time']=date('Y-m-d H:i:s',$value['create_time']);
                $list[$i]['update_time']=date('Y-m-d H:i:s',$value['update_time']);
                $i++;
            }
            $info['list']=$list;
        }
        return $info;
    }


}