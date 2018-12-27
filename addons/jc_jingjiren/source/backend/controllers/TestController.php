<?php
namespace backend\controllers;

use yii;
use common\tools\alitools\sms\AliSms;

class TestController extends BaseController
{
    public function actionIndex()
    {
        return 'web test';
    }

    public function actions()
    {

    }

    public function actionTt()
    {
        return 'yyy';
    }

//    public function actionWw()
//    {
//        return '123';
//    }
}
