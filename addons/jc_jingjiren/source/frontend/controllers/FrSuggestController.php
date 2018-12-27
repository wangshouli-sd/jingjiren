<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/6
 * Time: 15:00
 */

namespace frontend\controllers;


use frontend\models\FrMember;
use frontend\models\FrSuggest;
use yii;
class FrSuggestController extends BaseController
{
    #新增提交建议 操作
    public function actionCreate()
    {
        $memberMd=new FrMember();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC['__input'];
        $openid = $request_data['openid'];
        $send_id=$memberMd->memberid($openid);
        $json_success = [
            'status' => [
                'state' => 'success',
                'msg' => '成功',
            ]
        ];
        $json_error = [
            'status' => [
                'state' => 'error',
                'msg' => '失败',
            ]
        ];

        $problem = isset($request_data['problem']) ? $request_data['problem'] : '';
        $contact = isset($request_data['contact']) ? $request_data['contact'] : '';
        $img = isset($request_data['img']) ? $request_data['img'] : '';
        if ($problem == '' or $contact == '') {
            return $json_error;
        }
        $suggestMd = new FrSuggest();
        $data = [
            'uniacid' => $uniacid,
            'problem' => $problem,
            'contact' => $contact,
            'img' => $img,
            'send_id' => $send_id,
        ];
        if ($suggestMd->addOne($data)) {
            return $json_success;
        } else {
            return $json_error;
        }
    }

}