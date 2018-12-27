<?php
namespace common\controllers;

use yii;
use yii\web\Controller;
use yii\base\Module;

class BaseController extends Controller
{
    /*Csrf取消激活*/
    public $enableCsrfValidation = false;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $headers = Yii::$app->response->headers;
        $headers->set('Access-Control-Allow-Origin','*');
        $headers->set('Access-Control-Allow-Methods','POST, GET');
        $headers->set('Access-Control-Allow-Headers','X-PINGOTHER, Content-Type');
        $headers->set('Access-Control-Allow-Credentials','true');
    }
}


#/web/index.php?c=site&a=entry&m=jc_jingjiren&do=index&r=

#/app/index.php?i=1&j=2&c=entry&m=jc_jingjiren&do=index&r=

#/app/index.php?i=1&c=entry&a=wxapp&do=index&m=jc_jingjiren&r=  前台小程序接口入口

