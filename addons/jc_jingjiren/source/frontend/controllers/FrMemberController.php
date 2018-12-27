<?php
/**
 * Created by PhpStorm.
 * User: jiechenhulian
 * Date: 2018/8/27
 * Time: 9:20
 */

namespace frontend\controllers;

use common\models\Category;
use common\models\Customer;
use common\models\Formid;
use common\models\Member;
use common\models\Process;
use common\models\SysParams;
use common\models\Task;
use common\models\Upload;
use common\models\Wallet;
use Faker\ORM\Spot\Populator;
use frontend\models\FrMember;
use frontend\models\FrProject;
use phpDocumentor\Reflection\Types\This;
use yii;

class FrMemberController extends BaseController
{
    #个人中心页面初始化参数
    public function actionInfo($openid)
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $memberMd = new FrMember();
        $wallter = new Wallet();
        $sysMd = new SysParams();
        $id = $memberMd->memberid($openid);
        if ($id == null or $openid == null) {
            return false;
        }
        $memberInfo = $memberMd->One(['id' => $id]);
        $project_idArray = $memberInfo['project_id'] == '' ? null : explode(',', $memberInfo['project_id']);
        $likecount = $project_idArray == null ? '0' : count($project_idArray);
        $money = $wallter->fetchInfo($id);
        $level = $sysMd->fetchOneVar($uniacid, 'is_sale');
        return [
            'likecount' => $likecount,
            'yu_money' => $money['money'],
            'onlyid' => $memberInfo['onlyid'],
            'is_black' => $memberInfo['blacklist'],
            'level' => $level,
            'status' => $memberInfo['is_sender']
        ];
    }

    #个人用户信息
    public function actionMember()
    {
        global $_W, $_GPC;
        $openid = $_GPC['openid'];
        $uniacid = $_W['uniacid'];
        if (empty($openid)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '该用户不存在'
                ]
            ];
        } elseif (!empty($openid)) {
            $where = ['uniacid' => $uniacid, 'openid' => $openid];
            $one = new FrMember();
            $member = $one->one($where);
            return [
                'member' => $member,
                'status' => [
                    'state' => 'success'
                ]
            ];
        }
    }

    #修改用户信息
    public function actionEdit()
    {
        $member = new FrMember();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $time = time();
        $request_data = $_GPC;
        $openid = $request_data['openid'];
        $request_data['uniacid'] = $uniacid;
        $id = $member->memberid($openid);

        if (isset($request_data['formid'])){
            $formid = $request_data['formid'];
            $formidMd=new Formid();
            $formidMd->addOne(['member_id'=>$id,'formid'=>$formid]);
        }

        $nick_name = isset($request_data['nick_name']) ? $request_data['nick_name'] : '';
        $sex = isset($request_data['sex']) ? $request_data['sex'] : '';
        //$brithday = isset($request_data['brithday']) ? $request_data['brithday'] : '';      //出生年月
      //  $city = isset($request_data['city']) ? $request_data['city'] : '';      // 城市
      //  $description = isset($request_data['description']) ? $request_data['description'] : '';  // 简介
        $image = isset($request_data['image']) ? $request_data['image'] : '';       // 用户头像
        //$parentid = isset($request_data['parentid']) ? $request_data['parentid'] : '';       // 邀请码

        if (!empty($id)) {
            $data = [
                'nick_name' => $nick_name,
                'sex' => $sex,
               // 'brithday' => $brithday,
              //  'city' => $city,
                'image' => $image,
               // 'description' => $description,
                'update_time' => $time,
            ];
           // return $request_data;
            $key = array_search('formid', $request_data);
             if ($key !== false){
                 array_splice($request_data, $key, 1);
             }
           // return $request_data;
            return $member->edit($id, $request_data);
        } else if (empty($id) or empty($openid)) {
            return [
                'status' => [
                    'state' => "error",
                    'msg' => '没有查到用户的id'
                ]
            ];
        }
    }

    #修改一个字段用户信息
    public function actionEditone()
    {
        $member = new FrMember();
        global $_W, $_GPC;
        $request_data = $_GPC['__input'];
        $id = $member->memberid($request_data['openid']);


        if ($request_data['formid']!=null){
            $formid = $request_data['formid'];
            $formidMd=new Formid();
            $formidMd->addOne(['member_id'=>$id,'formid'=>$formid]);
        }

        foreach($request_data as $k=>$v){
            if($k == 'formid'){
                unset($request_data[$k]);
            }
        }
        //return $request_data;
        if ($member->editOne($id, $request_data) == true) {
            return [
                'status' => [
                    'state' => "success",
                    'msg' => $request_data
                ]
            ];
        } else {
            return [
                'status' => [
                    'state' => "error",
                    'msg' => $request_data
                ]
            ];
        }
    }

    #判断该用户否收藏该项目
    public function actionLike($openid = null, $project_id = null)
    {
        $member = new FrMember();
        $like = $member->like($openid, $project_id);
        return $like;

    }

    #我的收藏 若收藏，则取消收藏    若未收藏，则收藏该项目
    public function actionLikes($openid = null, $project_id = null)
    {
        $member = new FrMember();
        $state = $member->like($openid, $project_id);
        //执行取消收藏
        if ($state['state'] == 1) {
            $likes_id = $state['project_ids'];
            $li = array();
            foreach ($likes_id as $value) {
                if ($value != $project_id) {
                    $li[] = $value;
                }
            }
            $lii = implode(',', $li);
            $meinfo = $member->One(['openid' => $openid]);
            $id = $meinfo['id'];
            $likes = $member->fetchOne($id);
            $likes->scenario = 'like';
            $likes->attributes = ['project_id' => $lii];
            if ($likes->save()) {
                return '0';
            }
        }
        // 执行收藏
        if ($state['state'] == 0) {
            $states = implode(',', $state['project_ids']);
            if ($state['project_ids'] == null) {
                $project_ids = $project_id;
            } else {
                $project_ids = $states . ',' . $project_id;
            }
            if (!empty($project_ids)) {
                $meinfo = $member->One(['openid' => $openid]);
                $id = $meinfo['id'];
                $likes = $member->fetchOne($id);
                $likes->scenario = 'like';
                $likes->attributes = ['project_id' => $project_ids];
                if ($likes->save()) {
                    return '1';
                } else {
                    return '0';
                }
            }
        }
    }

    #获取我的收藏列表
    public function actionMlike($openid = null, $psize = null, $page = null)
    {
        $projectMd = new FrProject();
        $memberMd = new FrMember();
        $img = new Upload();

        $where = [
            'openid' => $openid,
        ];
        $memberinfo = $memberMd->One($where);
        $project_ids = $memberinfo['project_id'];
        $project_idArray = explode(',', $project_ids);
        $wheres = [
            'id' => $project_idArray,
        ];
        $page_size = $psize == NULL ? '2' : $psize;     // 每页显示的个数
        $page_index = $page == NULL ? 1 : $page;        // 第几页
        $data_total = $projectMd->fetchCountNum($wheres);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $projectMd->fetchListAll($wheres, $limit, $offset);
        $i = 0;
        foreach ($list as $key => $value) {
            $img_id = $value['slide'];
            $url = $img->One($img_id);
            $list[$i]['url'] = $url['path'];
            $i++;
        }
        $page = $pageMd->getJsonArray();

        return [
            'list' => $list,
            'page' => $page
        ];
    }

    #上传图片接口
    public function actionUpload()
    {
        $uploadMd = new Upload();
        $idca = array();
        foreach ($_FILES as $file) {
            $id = $uploadMd->uploadOne($file);
            $idca[] = $id;
        }
        if (empty($idca)) {
            return [
                'state' => 'error',
                'msg' => '失败没有返回照片id',
            ];
        } else if (!empty($idca)) {
            return $id;
        }

    }

    #我的客户列表
    public function actionCustomerList()
    {
        global $_GPC, $_W;
        $memberMd = new FrMember();
        $customerMd = new Customer();
        $id = $memberMd->memberid($_GPC['openid']);
        $where = [
            'and',
            ['=', 'is_del', '0'],
            ['=', 'member_id', $id],
            [
                'or',
                ['like', 'customer_name', $_GPC['seach']],
                ['like', 'customer_phone', $_GPC['seach']],
            ]
        ];
        $page_size = $_GPC['size'] == NULL ? '15' : $_GPC['size'];     // 每页显示的个数
        $page_index = $_GPC['page'] == NULL ? 1 : $_GPC['page'];        // 第几页
        $data_total = $customerMd->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $customerMd->fetchListAll($where, $limit, $offset, $order = ['create_time' => SORT_DESC]);
        $page = $pageMd->getJsonArray();

        return [
            'list' => $list,
            'page' => $page
        ];
    }

    # 根据首字母返回相应的用户列表
    public function actionFirst()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $memberMd = new FrMember();
        $customerMd = new Customer();
        $id = $memberMd->memberid($_GPC['openid']);
        $abc = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $i = 0;
        foreach ($abc as $value) {
            $where = [
                'and',
                ['=', 'customer_first', $value],
                ['=', 'member_id', $id],
                ['=', 'uniacid', $uniacid],
                ['=', 'is_del', '0']
            ];
            $list = $customerMd->fetchListAll($where, '', '', $order = ['create_time' => SORT_DESC]);
            if (!empty($list)) {
                $info['list'][$i] = ['letter' => $value, 'data' => $list];
            }
            $i++;
        }
        return $info;
    }

    #客户详情
    public function actionCustomerInfo($id)
    {
        global $_GPC, $_W;
        $customerMd = new Customer();
        $taskMd = new Task();

        $info = $customerMd->fetchOne(['id' => $id]);
        $where = [
            'username' => $info['customer_name'],
            'userphone' => $info['customer_phone'],
        ];
        $list = $taskMd->fetchListAll($where);
        if ($list != null) {
            $i = 0;
            foreach ($list as $key => $value) {
                $projectMd = new FrProject();
                $ProcessMd = new Process();
                $categoryMd = new Category();
                $projectinfo = $projectMd->fetchOne($value['project_id']);
                $category_id = $projectinfo['category_id'];
                $list[$i]['cate'] = $categoryMd->fetchOne($category_id);        // 类别
                $list[$i]['img_path'] = $projectinfo['img_path'];
                $list[$i]['projectname'] = $projectinfo['name'];
                $list[$i]['subname'] = $projectinfo['subname'];
                $where = [
                    'project_id' => $value['project_id'],
                    'task_id' => $value['id'],
                    'state' => '2'
                ];
                $process = $ProcessMd->fetchOne($where);
                $list[$i]['nowliucheng'] = $process['process_name'];
                $list[$i]['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
                $list[$i]['update_time'] = date('Y-m-d H:i:s', $value['update_time']);
                $i++;
            }
            $info['list'] = $list;

        } else if (empty($list)) {
            $info['list'] = [];
        }
        return $info;
    }

    #添加我的客户 联系人
    public function actionAddCustomer()
    {
        $customerMd = new Customer();
        $memberMd = new Member();
        $memberMds = new FrMember();
        global $_GPC, $_W;
        $_GPC = $_GPC['__input'];
        $data['uniacid'] = $_W['uniacid'];
        $data['customer_name'] = $_GPC['username'];
        $data['member_id'] = $memberMd->memberid($_GPC['openid']);
        $data['customer_phone'] = $_GPC['userphone'];
        $data['remarks'] = $_GPC['remark'];
        $data['status'] = '0';
        $data['customer_first'] = $memberMds->getFirstChar($data['customer_name']);

        $formid = $_GPC['formid'];
        $formidMd=new Formid();
        $formidMd->addOne(['member_id'=>$data['member_id'],'formid'=>$formid]);

        $info = $customerMd->fetchOne(['customer_phone' => $_GPC['userphone']]);
        if ($info != null) {
            return [
                'status' => [
                    'state' => 'exit',
                    'msg' => $info,
                ]
            ];
        }
        if ($customerMd->addOne($data)) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '添加成功',
                ]
            ];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '添加失败',
                ]
            ];
        }

    }

    #删除为操作过的客户
    public function actionDelCustomer($id, $openid)
    {
        $customerMd = new Customer();
        $memberMd = new Member();
        $member_id = $memberMd->memberid($openid);
        $info = $customerMd->fetchOne(['id' => $id]);
        if ($info['member_id'] == $member_id) {
            if ($customerMd->delOne(['id' => $id])) {
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '删除成功',
                    ]
                ];
            }
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '好像不是你的客户',
                ]
            ];
        }
    }
}
