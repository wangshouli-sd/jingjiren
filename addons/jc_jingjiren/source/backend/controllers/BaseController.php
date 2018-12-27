<?php
namespace backend\controllers;

use common\models\Pay;
use yii;
use yii\base\Module;
use common\models\Upload;
class BaseController extends \common\controllers\BaseController
{

    //添加首页图片
    public function actionAddimg()
    {
        $upMd = new Upload();
        foreach ($_FILES as $file) {
            $id = $upMd->uploadOne($file);
        }
        if (empty($id)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '上传失败'
                ]];
        }
        return ['id'=>$id];
    }
   // 编辑器添加图片
    public function actionAddpic()
    {
        $upMd = new Upload();
        foreach ($_FILES as $file) {
            $result = $upMd->uploadImg($file);
        }
        if (empty($result)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '上传失败'
                ]];
        }
        return $result;
    }
}



#/web/index.php?c=site&a=entry&m=jc_jingjiren&do=index&r=

#/app/index.php?i=1&j=2&c=entry&m=jc_jingjiren&do=index&r=

#/app/index.php?i=11&c=entry&a=wxapp&do=index&m=jc_jingjiren&sign=f04b5e2487d0fc9f6139f05bcf8b824c
