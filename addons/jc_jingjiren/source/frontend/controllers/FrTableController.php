<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/30
 * Time: 10:23
 */

namespace frontend\controllers;

use common\models\Formid;
use common\models\Template;
use function Composer\Autoload\includeFile;
use frontend\models\FrMember;
use frontend\models\FrTable;
use frontend\models\FrTask;

class FrTableController extends \common\controllers\BaseController
{
    //这里直接对接前台传表单那里，所以把table表里的字段都列表展示，字段下test框name要为列表里的ids
    public function actionList($project_id = null)
    {
        $table = new FrTable();
        $where = [
            'project_id' => $project_id,
            'is_del' => 0
        ];
        $list = $table->fetchListAll($where, $order = 'order');
        return $list;

    }

    //用户表单提交，遍历数据库中字段id name也为id，获取值插入到数据库
    public function actionAdd()
    {
        global $_W, $_GPC;
        $table = new FrTable();
        $memberMd = new FrMember();
        $values = $_GPC['__input'];
        //$dic_value=(array)$values['dics_data'];
        $uniacid = $_W['uniacid'];
        $openid = $values['openid'];
        $member_info = $memberMd->One(['openid' => $openid]);
        $member_id = $member_info['id'];
        if ($values['formId'] != null) {
            $formid = $values['formId'];
            $formidMd = new Formid();
            $formidMd->addOne(['member_id' => $member_id, 'formid' => $formid]);
        }
        //setcookie('XDEBUG_SESSION','PHPSTORM');//断点
        if ($member_info['blacklist'] == 0) {
            return [
                'status' => [
                    'state' => 'black',
                    'msg' => '黑名单成员，不能提交'
                ]
            ];
        }
        $task = new FrTask();
        $values = [
            'project_id' => $values['project_id'],
            'uniacid' => $uniacid,
            'send_id' => $member_id,
            'describe' => $values['describe'],
            'username' => $values['username'],
            'userphone' => $values['userphone'],
        ];
        $info = $task->fetchOne(['userphone' => $values['userphone'], 'project_id' => $values['project_id']]);
        $have = $task->fetchOne(['userphone' => $values['userphone']]);     //  已经提交过项目
        $updatetime = $have['update_time'];    // 之前提交项目的最后时间

        $nowtime = date("Y-m-d H:i:s", strtotime("-100 day"));
        $nowtimes = strtotime($nowtime);

        if ($updatetime < $nowtimes) {
            $infotoo = $task->fetchOne(['userphone' => $values['userphone'], 'username' => $values['username'], 'project_id' => $values['project_id']]);
            if (!empty($infotoo) or $infotoo != null) {
                return [
                    'status' => [
                        'state' => 'formidtoo',
                        'msg' => $formid
                    ]
                ];
            } else if (!empty($info) or $info != null) {
                return [
                    'status' => [
                        'state' => 'have',
                        'msg' => $info
                    ]
                ];
            } else {
                return $task->addOne($values);
            }
        } else if($updatetime > $nowtimes){
            return [
              'status' => [
                  'state' => 'doing',
                  'msg' => '该联系人的业务三个月时间期限未过，暂不可以提交',
              ]
            ];
        }
    }


}