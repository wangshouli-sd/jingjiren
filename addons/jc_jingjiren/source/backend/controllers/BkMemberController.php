<?php
/**
 * Created by PhpStorm.
 * User: jiechenhulian
 * Date: 2018/8/24
 * Time: 17:10
 */

namespace backend\controllers;

use backend\models\BkMember;
use backend\models\BkTask;
use common\models\Customer;
use common\models\Distribution;
use common\models\GetmoneyLog;
use common\models\Member;
use common\models\Process;
use common\models\Task;
use common\models\Upload;
use frontend\models\FrMember;
use frontend\models\FrProject;
use frontend\models\FrWallertlog;
use yii;
use yii\db\ActiveRecord;
class BkMemberController extends BaseController
{

    #后台经纪人管理列表
    public function actionMemberAll($page = null, $size = null)
    {
        global $_W, $_GPC;
        $bkDocMd = new BkMember();
        $img = new Upload();
        $wallertlogMd = new FrWallertlog();
        $getmoneylgMd=new GetmoneyLog();
        $uniacid = $_W['uniacid'];
        $taskMd = new BkTask();

        $request = yii::$app->request;
        $black = $request->get('blacklist');
        $name = $request->get('name');
        $onlyid = $request->get('onlyid');

        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'is_sender', 2],
            ['like', 'name', $name],
            ['=', 'blacklist', $black],
            ['like', 'onlyid', $onlyid],
        ];
        #分页设置
        $page_size = $size == NULL ? '3' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $bkDocMd->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $bkDocMd->fetchListAll($where, $limit, $offset, $order = 'update_time DESC');
        $i = 0;
        foreach ($list as $key => $value) {
            $list[$i]['time'] = date('Y/m/d', $value['create_time']);

            $moneylog = $wallertlogMd->fetchmoney($value['id'], $uniacid);       // 累计佣金
            $list[$i]['money'] = $moneylog;
            $moneychu = $getmoneylgMd->tixianMoney($value['id'], $uniacid);       // 累计提现
            $list[$i]['tixian'] = $moneychu;

            // 业务提交次数
            $wheres = [
                'send_id' => $value['id'],
            ];
            $number = $taskMd->fetchCountNum($wheres);
            $list[$i]['number'] = $number;
            $list[$i]['url'] = $value['image'];
            $i++;
        }
        $page = $pageMd->getJsonArray();
        $black=[
            'blacklist'=>0,
            'is_del'=>0,
            'uniacid'=>$uniacid,
            'is_sender'=>2
        ];
        $page['black_total']=$bkDocMd->fetchCountNum($black);
        return [
            'list' => $list,
            'page' => $page,
        ];
    }

    #用户详情信息信息====佣金明细
    public function actionMember()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $id = $request->get('id');
        if (empty($id)) {
            return [
                'status' => [
                    'state' => "error",
                ]
            ];
        } else if (!empty($id)) {
            $one = new BkMember();
            $member = $one->oneinfo($id, $uniacid);
            return $member;
        }
    }

    #用户删除 是否删除 返回bool值
    public function actionMemberDel()
    {
        global $_W, $_GPC;
        $request = Yii::$app->request;
        $id = $request->get('id');

        $update_time = strtotime(now);

        if (empty($id)) {
            return [
                'status' => [
                    'state' => "error",
                    'msg' => '该用户不存在'
                ]
            ];
        } else {
            $uniacid = $_W['uniacid'];
            $value = [
                'uniacid' => $uniacid,
                'update_time' => $update_time,
                'is_del' => 1,
                'idcard_pic'=>'',
                'phone'=>''
            ];
            $member = new BkMember();
            $is_del = $member->fetchOne($id);
            $is_del->scenario = 'is_del';
            $is_del->attributes = $value;
            $is_del->save();
            $disMd=new Distribution();
            $info=$disMd->one(['main_id'=>$id]);
            $one=$disMd->findoneinfo($info['id']);
            $one->delete();
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '删除成功'
                ]
            ];
        }
    }

    #批量删除
    public function actionDelall()
    {
        global $_W, $_GPC;
        $request = Yii::$app->request;
        $id = $request->get('id');
        $ids = explode(',', $id);

        $update_time = time();

        if (empty($ids)) {
            return [
                'status' => [
                    'state' => "error",
                    'msg' => '没有选择'
                ]
            ];
        } else {
            foreach ($ids as $value) {
                $member = new BkMember();
                $is_del = $member->fetchOne($value);
                $data=[
                    'is_del' => '1',
                    'update_time' => $update_time,
                    'idcard_pic'=>'',
                    'phone'=>''
                ];
                $is_del->scenario = 'is_del';
                $is_del->attributes =$data;
                $is_del->save();
                $disMd=new Distribution();
                $info=$disMd->one(['main_id'=>$value]);
                $one=$disMd->findoneinfo($info['id']);
                $one->delete();
            }
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '批量删除成功'
                ]
            ];
        }
    }

    #后台未通过审核的经纪人列表
    public function actionAudit0($page = null, $size = null)
    {
        global $_W, $_GPC;
        $bkDocMd = new BkMember();
        $img = new Upload();
        $uniacid = $_W['uniacid'];
        $request = yii::$app->request;
        $name = $request->get('name');
        $where = ['uniacid' => $uniacid, 'is_sender' => 0, 'is_del' => 0,];
        if (!empty($name)) {
            $where = [
                'and',
                ['=', 'is_del', 0],
                ['=', 'is_sender', 0],
                ['=', 'uniacid', $uniacid],
                ['like', 'name', $name]
            ];
        }

        #分页设置
        $page_size = $size == NULL ? '3' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $bkDocMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $bkDocMd->fetchListAll($where, $limit, $offset, $order = 'update_time DESC');
        $i = 0;
        foreach ($list as $key => $value) {
            $img_id = $value['image'];
            $url = $img->One($img_id);
            $list[$i]['url'] = $value['image'];
            $list[$i]['time'] = date('Y/m/d', $value['create_time']);
            $list[$i]['why'] = $value['sender_remark'];
            $i++;
        }
        $page = $pageMd->getJsonArray();

        return ['list' => $list,
            'page' => $page,
        ];
    }

    #后台审核中的经纪人列表
    public function actionAudit1($page = null, $size = null)
    {
        global $_W, $_GPC;
        $bkDocMd = new BkMember();
        $img = new Upload();
        $uniacid = $_W['uniacid'];

        $request = yii::$app->request;
        $name = $request->get('name');
        $where = ['uniacid' => $uniacid,
            'is_del' => 0,
            'is_sender' => 1,];

        if (!empty($name)) {
            $where = [
                'and',
                ['=', 'is_del', 0],
                ['=', 'is_sender', 1],
                ['=', 'uniacid', $uniacid],
                ['like', 'name', $name]
            ];
        }

        #分页设置
        $page_size = $size == NULL ? '3' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $bkDocMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];


        $list = $bkDocMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $i = 0;
        foreach ($list as $key => $value) {
            $img_id = $value['image'];
            $url = $img->One($img_id);
            $list[$i]['url'] = $value['image'];
            $list[$i]['jing_time'] = date('Y/m/d', $value['update_time']);
            $idcard_pic = $value['idcard_pic']; // 身份证
            $idcard = $img->getimg($idcard_pic);
            $list[$i]['idcard_url'] = $idcard;  // 身份证链接

            $i++;
        }
        $page = $pageMd->getJsonArray();


        return [
            'list' => $list,
            'page' => $page,
        ];
    }

    #经纪人管理 批量加入黑名单
    public function actionBlackAdd()
    {
        global $_W;
        $request = Yii::$app->request;
        $id = $request->get('id');
        $ids = explode(',', $id);
        $values = [
            'blacklist' => 0,
        ];
        foreach ($ids as $key) {
            $memberMd = new BkMember();
            $black[] = $memberMd->add($key, $values);
        }

        if (array_sum($black) == 0) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '加入成功'
                ]];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败'
                ]];
        }

    }

    #经纪人管理  批量取消黑名单
    public function actionBlackDel()
    {
        global $_W;
        $request = Yii::$app->request;
        $id = $request->get('id');
        $ids = explode(',', $id);
        $values = [
            'blacklist' => 1,
        ];
        foreach ($ids as $key) {
            $project = new BkMember();
            $black[] = $project->del($key, $values);
        }
        if (array_sum($black) == 0) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '加入成功'
                ]];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败'
                ]];
        }

    }

}
