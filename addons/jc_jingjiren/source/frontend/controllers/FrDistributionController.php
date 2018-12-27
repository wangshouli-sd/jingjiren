<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/10
 * Time: 11:11
 */

namespace frontend\controllers;

use common\models\Distribution;
use common\models\SysParams;
use frontend\models\FrMember;
use yii;
class FrDistributionController extends BaseController
{
    #type为1是子级，type为2是子子级
    public function actionGetson(){
        global $_W;
        $memberMd=new FrMember();
        $distributionMd=new Distribution();
        $request = Yii::$app->request;

        $openid= $request->get('openid');
        $uniacid = $_W['uniacid'];
        $page=$request->get('page');
        $size=$request->get('size');
        $type= $request->get('type');
        $page_size=$size==NULL?'2':$size;
        $page_index=$page==NULL?1:$page;

        $myid=$memberMd->memberid($openid);
        if ($myid==null){
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

        $where=[
            'one_level'=>$myid,
            'uniacid'=>$uniacid,
        ];
        switch ($type){
            case 1;
            return $distributionMd->getsons($where,$page_size,$page_index);
            break;

            case 2;
                $where=[
                    'two_level'=>$myid,
                    'uniacid'=>$uniacid,
                ];
                return $distributionMd->getsonsons($where,$page_size,$page_index);
            break;
        }
    }

    public function actionIndex()
    {
        $sysMs=new SysParams();

        global $_W;
        $uniacid = $_W['uniacid'];
        $fenxiao=$sysMs->fetchOne($uniacid,'is_sale');
        return  ['level'=>$fenxiao['var']];
    }

    #获取佣金的自定义字段
    public function actionGetdic(){
        $sysMs=new SysParams();
        global $_W;
        $uniacid = $_W['uniacid'];
        $fenxiao=$sysMs->fetchOne($uniacid,'dic_money');
        return $fenxiao['var'];
    }

}