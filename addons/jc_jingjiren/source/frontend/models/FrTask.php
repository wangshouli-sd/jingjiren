<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 14:54
 */

namespace frontend\models;

use common\models\Base;
use common\models\Process;
use common\models\Task;
use common\models\Upload;

class FrTask extends Task
{

    //0未通过，5是审核中，6是洽谈失败，7是服务中止
    public function fetchListSate_0($state,$userid, $page_size, $page_index,$name)
    {
        global $_W,$_GPC;
        $projectMd = new FrProject();
        $img = new Upload();
        $categoryMd = new FrCategory();
        $uniacid = $_W['uniacid'];
        if ($state == '0' or $state == '5') {
            $state_check=$state=='5'?'1':$state;
            $where = [
                'and',
                ['=', 'is_del', 0],
                ['=', 'uniacid', $uniacid],
                ['=', 'send_id', $userid],      // 经纪人的id
                ['=', 'state_check', $state_check],
                [
                    'or',
                    ['like','userphone',$name],    // 搜索客户手机
                    ['like','username',$name],      // 搜索客户姓名

                ],
            ];
        } else if ($state == '6' or $state == '7') {
            $state_progress=$state=='6'?'1':'2';
            $where = [
                'and',
                ['=', 'is_del', 0],
                ['=', 'uniacid', $uniacid],
                ['=', 'send_id', $userid],      // 经纪人的id
                ['=', 'state_check', '2'],
                ['=', 'is_stop', '1'],
                ['=', 'state_progress',$state_progress ],
                [
                    'or',
                    ['like','userphone',$name],    // 搜索客户手机
                    ['like','username',$name],      // 搜索客户姓名

                ],
            ];
        }
        $data_total = $this->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $this->fetchListAll($where, $limit, $offset, $order = null);
        $i = 0;
        foreach ($list as $key => $var) {
            $list[$i]['taskinfo'] = explode(',', $var['dics_id']);
            $list[$i]['taskidata'] = explode(',', $var['dics_data']);
            $project = $projectMd->fetchOne($list[$i]['project_id']);
            $url = $img->One($project['img']);
            $list[$i]['imgurl'] = $_W['siteroot'] . $url['path'];//图片路径

            $list[$i]['category'] = $categoryMd->fetchOne($project['category_id']);//类别路径
            $list[$i]['projectname'] = $project['name'];
            $list[$i]['subname'] = $project['subname'];
            $list[$i]['time'] = date('Y/m/d',  $var['update_time']);
            $processMd=new Process();
            $temp_where = [
                'and',
                ['=', 'task_id', $var['id']],
                ['!=', 'type', '3'],
                ['!=', 'is_del', '1'],
                ['=', 'project_id', $var['project_id']],
            ];
            $list[$i]['allprocess'] = $processMd->fetchListAll($temp_where, $limit = null, $offset = null, ['type' => SORT_ASC, 'order' => SORT_ASC]);
            $i++;
        }
        $page = $pageMd->getJsonArray();
        return [
            'task' => $list,
            'pager' => $page,
        ];
    }

    //1洽谈中，2是服务中，3是已完成,4是待传合同，
    public function fetchListSate($state_projress, $userid, $page_size, $page_index,$name)
    {
        global $_W,$_GPC;
        $uniacid = $_W['uniacid'];
        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'send_id', $userid],      // 经纪人的id
            ['=', 'state_check', '2'],
            ['=', 'is_stop', '0'],
            ['=', 'state_progress', $state_projress],
            [
                'or',
                ['like', 'userphone', $name],     // 搜索客户手机
                ['like', 'username', $name],      // 搜索客户姓名
            ]
        ];
        $data_total = $this->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $this->fetchListAll($where, $limit, $offset, $order = null);
        $list = $this->foreachlist($list);
        $page = $pageMd->getJsonArray();
        return [
            'task' => $list,
            'pager' => $page,
        ];

    }

    //根据用户id获取其待确认的佣金
    public function getmoney($id)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $processMd = new FrProcess();
        $where = [
            'uniacid' => $uniacid,
            'send_id' => $id,
            'state_progress' => 2,
            'is_stop' => 0,
            'state_check' => 2,
            'is_del' => '0',
        ];
        $tasklist = $this->fetchListAll($where);
        if ($tasklist == null) {
            return '0';
        }
        foreach ($tasklist as $key => $value) {
            $task_id[] = $value['id'];
        }
        $params = [
            'task_id' => $task_id,
            'state' => ['1', '2'],
            'content_type' => '2',
            'type' => 2
        ];
        $process = $processMd->fetchListAll($params);
        if ($process == null) {
            return '0';
        }
        $dai = array();
        foreach ($process as $key => $var) {
            $taskMd = new FrTask();
            $task = $taskMd->fetchOne(['id' => $var['task_id']]);
            $allmoney = $task['total_price'] * $task['back_percent'] / 100;
            $daimoney = $allmoney * $var['price'] / 100;
            $dai[] = $daimoney;
        }
        $money = array_sum($dai);
        return $money;

    }

}