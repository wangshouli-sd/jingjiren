<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/11
 * Time: 13:48
 */

namespace frontend\controllers;

use common\models\Distribution;
use common\models\GetmoneyLog;
use common\models\Wallet;
use common\models\WalletLog;
use frontend\models\FrCode;
use frontend\models\FrMember;
use frontend\models\FrTask;
use frontend\models\FrWallertlog;
use yii;
class FrWallertController extends BaseController
{
    //可提佣金页面
    public function actionIndex()
    {
        global $_W;
        $walletMd=new Wallet();
        $memberMd=new FrMember();
        $walletlogMd=new FrWallertlog();
        $parentMd=new Distribution();
        $getmoneyMd=new GetmoneyLog();
        $taskMd=new FrTask();
        $request=yii::$app->request;
        $openid=$request->get('openid');
        $uniacid = $_W['uniacid'];
        $merber=$memberMd->One(['openid'=>$openid]);
        $member_id=$merber['id'];
        $onlyid=$merber['onlyid'];
        $money=$walletMd->fetchInfo($member_id);
        $yu_money=$money['money'];//账户余额
        $moneylog=$walletlogMd->fetchmoney($member_id,$uniacid);//累计佣金

        $parent_list=$parentMd->one(['main_id'=>$member_id]);
        $parent_id=$parent_list['one_level'];
        $parent=$memberMd->One(['id'=>$parent_id]);//邀请人

        $getmoney=$getmoneyMd->fecthone($member_id,2);//提现中的
        //待确认的是进行中项目的分销所的钱
        $getmoney_dai=$taskMd->getmoney($member_id);

        $level=$walletlogMd->leval($member_id,$uniacid,['son','grand_son']);//所有分销或得到佣金
        $main=$walletlogMd->leval($member_id,$uniacid,['main']);//直接获得的佣金

        return [
            'yu_money'=>$yu_money,
            'all_money'=>$moneylog,
            'onlyid'=>$onlyid,
            'parent'=>$parent['name'],
            'daiqueren'=>$getmoney_dai,
            'tixianzhong'=>$getmoney['money'],
            'fenxiao'=>$level,
            'zhifan'=>$main,
        ];
    }

}