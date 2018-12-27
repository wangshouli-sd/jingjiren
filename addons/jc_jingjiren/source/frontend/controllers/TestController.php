<?php
namespace frontend\controllers;

use common\models\Test;
use common\models\Wxapp;
use frontend\models\FrProcess;
use yii;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $openid='3434';
        global $_W,$_GPC;
     $test=new Test();
     // return $test->getimage($url='https://wx.qlogo.cn/mmopen/vi_32/48eaCVUquJocEgGic0fa2jFmibo6bmgnSozmlJbrdD6OhnzP33iakMmtKvMp9CEibryiavHX7tEVGpZvjNibgPOjW0Zg/132',$openid.'.jpg',0);
      //  $headimgurl,'./Uploads/Picture/uid2/headimg/',$openid.'.jpg',1

    }
    public function actionY(){
        $url='https://wx.qlogo.cn/mmopen/vi_32/48eaCVUquJocEgGic0fa2jFmibo6bmgnSozmlJbrdD6OhnzP33iakMmtKvMp9CEibryiavHX7tEVGpZvjNibgPOjW0Zg/132';
        $test=new Test();
       // return $test->tou($url,$onlyid);
    }

    public function actionTime()
    {
        $a = time();

        $b = strtotime("-0 year -3 month -0 day");

        echo  $a . 'aaaaaaaaaaaaaaa';

        echo $b;
    }

}
