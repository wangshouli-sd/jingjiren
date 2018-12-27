<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/5
 * Time: 17:10
 */

namespace common\models;

use common\models\Base;
use frontend\models\FrCategory;
use frontend\models\FrMember;
use frontend\models\FrProcess;
use frontend\models\FrProject;

class Task extends Base
{
    static function tableName()
    {
        return "{{%task}}";
    }

    #安全属性
    public function scenarios()
    {
        return [
            'create' => [
                'uniacid', 'project_id', 'username', 'userphone',
                'state_check', 'state_progress', 'send_id', 'is_bind_manager', 'manager_id',
                'is_bind_executer', 'execute_id', 'back_percent', 'total_price', 'create_time', 'update_time'
            ],
            'update' => ['title', 'create_time', 'update_time', 'is_del'],
            'del' => ['is_del'],
            'shen' => ['state_check', 'state_why', 'update_time', 'state_progress', 'back_percent', 'contract_id'],     //  上传合同，back_percent === 佣金比例 ，
            'stop' => ['is_stop', 'stop_id', 'stop_remark', 'stop_state', 'update_time'],
            'price' => ['total_price', 'back_percent', 'update_time'],
            'taskup' => ['state_progress', 'update_time'],
            'check' => ['state_progress', 'update_time']
        ];
    }

    //获取任务列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
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
        return parent::fetchCountNum($where);
    }

    //获取一条任务详情
    public function fetchOne($where = array())
    {
        $list = $this::find()
            ->filterWhere($where)
            ->asArray()
            ->one();
        return $list;
    }

    //一条任务详情逻辑代码
    public function listone($list = array())
    {
        if ($list==null){
            return '';
        }
        $processMd = new FrProcess();
        $projectMd = new FrProject();
        $uploadMd = new Upload();
        $categoryMd = new FrCategory();
        $value = $list;
        $project_where = [
            'id' => $value['project_id']
        ];
        $list['projectname'] = $projectMd->fetchName($project_where);
        $list['id'] = $value['id'];//任务id
        $project = $projectMd->fetchOne($list['project_id']);

        $list['price'] = $value['total_price']*$list['back_percent']/100;//总佣金
        $url = $uploadMd->One($project['img']);
        $list['imgurl'] = $url['path'];// 缩略图片路径

        global $_W;
        if ($_W['script_name']=='/app/index.php'){
            $list['imgurl'] = $_W['siteroot'].$url['path'];
        }

        $list['category'] = $categoryMd->fetchOne($project['category_id']);//类别路径

        $list['contract']=$uploadMd->getimg( $list['contract_id']);//合同图片路径
        $list['slide']=$uploadMd->getimg( $project['slide']);//论波图图片路径

        //$list['taskinfo'] = explode(',', $value['dics_id']);
        //$list['taskidata'] = explode(',', $value['dics_data']);
        $time = $value['update_time'];
        $list['tasktime'] = date('Y/m/d', $time);
        $state = $value['state_progress'];
        if ($state == 3 ) {
            $temp_where = [
                'and',
                ['=', 'task_id', $value['id']],
                ['!=', 'type', '3'],
                ['=', 'is_del', '0'],
                ['=', 'project_id', $value['project_id']],
            ];
            $list['allprocess'] = $processMd->fetchListAll($temp_where, $limit = null, $offset = null, ['type' => SORT_ASC,'order' => SORT_ASC]);
        } else if ($state == 1 or $state == 2) {
            $temp_where = [
                'task_id' => $value['id'],
                'is_del' => ['0','2'],
                'project_id' => $value['project_id'],
                'type' => $state
            ];
            $list['allprocess'] = $processMd->fetchListAll($temp_where, $limit = null, $offset = null, ['order' => SORT_ASC]);
        }
        if (!empty($list['allprocess'])){
            $i = 0;
            foreach ($list['allprocess'] as $key => $value) {
                $time = $value['update_time'];
                $list['allprocess'][$i]['processtime'] = date('Y/m/d', $time);
                $i++;
            }
        }

        return $list;
    }

    //遍历任务列表，获取任务的相关信息
    public function foreachlist($list = array())
    {
        $processMd = new FrProcess();
        $projectMd = new FrProject();
        $img = new Upload();
        $categoryMd = new FrCategory();
        $i = 0;
        foreach ($list as $key => $value) {
            $project_where = [
                'id' => $value['project_id']
            ];
            $list[$i]['projectname'] = $projectMd->fetchName($project_where);
            $list[$i]['id'] = $value['id'];//任务id
            $list[$i]['price'] = $value['total_price']*$value['back_percent']/100;//总佣金

            $project = $projectMd->fetchOne($list[$i]['project_id']);
            $url = $img->One($project['img']);
            $list[$i]['subname'] = $project['subname'];//图片路径
            $list[$i]['imgurl'] = $url['path'];
            global $_W;
            if ($_W['script_name']=='/app/index.php'){
                $list[$i]['imgurl'] = $_W['siteroot'].$url['path'];
            }

            $list[$i]['category'] = $categoryMd->fetchOne($project['category_id']);//类别路径

            $list[$i]['taskinfo'] = explode(',', $value['dics_id']);
            $list[$i]['taskidata'] = explode(',', $value['dics_data']);
            $time = $value['update_time'];
            $list[$i]['tasktime'] = date('Y/m/d', $time);
            $state = $value['state_progress'];
            if ($state == 3 ) {
                $temp_where = [
                    'and',
                    ['=', 'task_id', $value['id']],
                    ['!=', 'type', '3'],
                    ['!=', 'is_del', '1'],
                    ['=', 'project_id', $value['project_id']],
                ];
                $list[$i]['allprocess'] = $processMd->fetchListAll($temp_where, $limit = null, $offset = null, ['type' => SORT_ASC, 'order' => SORT_ASC]);
            } else if ($state == 1 or $state == 2 or $state == 4) {
                if ($state==4){
                    $state=1;
                }
                $temp_where = [
                    'and',
                    ['=', 'task_id', $value['id']],
                    ['=', 'type',$state],
                    ['!=', 'is_del', '1'],
                    ['=', 'project_id', $value['project_id']],
                ];
                $now_where=[
                    'and',
                    ['=', 'task_id', $value['id']],
                    ['=', 'state','2'],
                    ['!=', 'is_del', '1'],
                    ['=', 'project_id', $value['project_id']],
                ];
                $list[$i]['allprocess'] = $processMd->fetchListAll($temp_where, $limit = null, $offset = null, ['order' => SORT_ASC]);
                $list[$i]['nowprocess'] = $processMd->fetchOne($now_where)['process_name'];
            }

            $i++;
        }
        $list_out = $list;
        return $list_out;
    }

    #添加一条 再往客户表加入一条数据如果不存在这个客户
    public function addOne($params)
    {
        $params['create_time'] = time();
        $params['update_time'] = time();
        foreach ($params as $key => $var) {
            $this->scenario = 'create';
            $this->$key = $var;
        }
        $this->save();
        $id = $this->id;
        $task_number = 'C' . date('Ymdhi', time()) . $id;
        $task_params = [
            'task_number' => $task_number
        ];
        if ($this->editOne($id, $task_params)) {
            $customerMd = new Customer();
            $where = [
                'member_id' => $params['send_id'],
//                'customer_name' => $params['username'],
                'customer_phone' => $params['userphone'],
            ];
            $customer = Customer::findOne($where);
            if ($customer == null) {
                $where['status']='1';
                $where['customer_name']= $params['username'];

                $memberMd=new FrMember();
                $first=$memberMd->getFirstChar($params['username']);
                $where['customer_first']= $first;
                if ($customerMd->addOne($where)) {
                    return [
                        'status' => [
                            'state' => 'success',
                            'msg' => $customerMd
                        ]];
                }
            }else {
                $customer->status='1';
                $customer->save();
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => $customer
                    ]];
            }
        }
    }

    #更新一条
    public function editOne($where, $params)
    {
        $params['update_time'] = time();
        $docMd = self::findOne($where);
        $docMd->scenario = 'shen';
        if ($docMd == NULL) {
            return false;
        }
        foreach ($params as $key => $var) {
            $docMd->$key = $var;
        }
        return $docMd->save();
    }
}