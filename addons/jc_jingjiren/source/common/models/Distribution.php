<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/7
 * Time: 12:01
 */

namespace common\models;


use frontend\models\FrMember;
use frontend\models\FrTask;
use frontend\models\FrWallet;

class Distribution extends Base
{
    public static function tableName()
    {
        return '{{%member_distribution}}';
    }

    //新增前查找其上级分销id方法
    public function one($where = array())
    {
        $list = self::find()
            ->where($where)
            ->asArray()
            ->one();
        return $list;
    }

    //新增功能  基础
    public function add($params)
    {
        $params['create_time'] = time();
        $params['update_time'] = time();
        foreach ($params as $key => $var) {
            $this->$key = $var;
        }
        return $this->save();
    }

    //一级分销新增 前台用
    public function addone($uniacid, $parentid, $id)
    {
        $where = [
            'onlyid' => $parentid
        ];
        #父级列表，获取id
        $member = new Member();
        $parent_list = $member->one($where);
        if (empty($parent_list) or $parentid==0) {
            return [
                'status' => [
                    'state' => "error",
                    'msg' => '找不到邀请人，邀请码错误'
                ]
            ];
        }
        $params = [
            'main_id' => $parent_list['id']
        ];
        $one_level = empty($parent_list['id']) ? '0' : $parent_list['id'];
        $values = [
            'one_level' => $one_level,
            'two_level' => 0,
            'main_id' => $id,
            'level' => 1,
            'uniacid' => $uniacid,
        ];
        $add = $this->add($values);
        #分销表###############
        return [
            'status' => [
                'state' => "success",
                'msg' => '新增成功'
            ]
        ];

    }

    //二级分销新增 前台用
    public function addtwo($uniacid, $parentid, $id)
    {
        $member = new Member();
        if ($parentid==null or $parentid==0) {
            $values = [
                'one_level' => 0,
                'two_level' => 0,
                'main_id' => $id,
                'level' => 2,
                'uniacid' => $uniacid,
            ];
            return $this->add($values);
        }
        $where = [
            'onlyid' => $parentid
        ];
        #父级信息，获取id
        $parent_list = $member->one($where);

        if ($parent_list!=null){
            $params = [
                'main_id' => $parent_list['id']
            ];
            # 通过父级id找二级的id
            $two_list = $this->one($params);
            $two_level = empty($two_list['one_level']) ? '0' : $two_list['one_level'];
        }else{
            $two_level='0';
        }
        $one_level = empty($parent_list['id']) ? '0' : $parent_list['id'];
        $values = [
            'one_level' => $one_level,
            'two_level' => $two_level,
            'main_id' => $id,
            'level' => 2,
            'uniacid' => $uniacid,
        ];
        return $this->add($values);
    }

    //列表  前台用
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        return parent::fetchListAll($where, $limit, $offset, $order);
    }

    //获取子级列表 前台
    public function getsons($where, $page_size, $page_index)
    {
        $memberMd = new FrMember();
        $list = $this->fetchListAll($where);
        $data_total = $this->fetchCountNum($where);
        if ($list==null){
            return [
                'list' => [],
                'page' => [
                     "current"=> "1",
                     "next"=>  "1",
                     "data_total"=>  "0",
                     "data_residue"=>  0,
                     "page_total"=>  1,
                     "page_residue"=>  0
                ]
            ];
        }
        $ids = array();
        foreach ($list as $key => $value) {
            $ids[] = $value['main_id'];
        }
        $where = [
            'id' => $ids
        ];
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $memberMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $list_out = $this->getinfo($list);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list_out,
            'page' => $page
        ];

    }

    //获取子子级列表 前台
    public function getsonsons($where , $page_size, $page_index)
    {
        $memberMd = new FrMember();
        $list_son = $this->fetchListAll($where);
        if ($list_son==null){
            return [
                'list' => [],
                'page' => [
                    "current"=> "1",
                    "next"=>  "1",
                    "data_total"=>  "0",
                    "data_residue"=>  0,
                    "page_total"=>  1,
                    "page_residue"=>  0
                ]
            ];
        }
        $id = array();
        foreach ($list_son as $key => $value) {
            $id[] = $value['main_id'];
        }
        $where = [
            'id' => $id
        ];
        $data_total = $memberMd->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $memberMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $list_out = $this->getinfo($list);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list_out,
            'page' => $page
        ];

    }

    //循环获取下级的信息  前台我的下级列表那块
    public function getinfo($list = array())
    {
        $uploadMd = new Upload();
        $taskMd = new FrTask();
        $walletMd = new FrWallet();
        $i = 0;
        foreach ($list as $key => $value) {
            //时间
            $time = $value['create_time'];
            $list[$i]['time'] = date("Y-m-d", $time);
            //头像
            $img = $uploadMd->fetchListAll(['id' => $value['image']]);
            $list[$i]['img'] = $img[0]['path'];
            $member=new FrMember();
            $member_id=$member->memberid($value['openid']);
            //共几个订单
            $where = [
                'send_id' =>$member_id,//openid和memberid不确定
                'state_progress' => 3
            ];
            $task = $taskMd->fetchCountNum($where);
            $list[$i]['task_count'] = $task==null?'0':$task;
            //获得佣金
            $wallet = $walletMd->fetchInfo($value['id']);
            $list[$i]['money'] = $wallet['money'];
            $i++;
        }
        return $list;
    }

    //总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    public function findoneinfo($id){
        return self::findOne($id);
    }



}