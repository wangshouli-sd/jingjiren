<?php
/**
 * jc_jingjiren模块小程序接口定义
 *
 * @author 优创知云
 * @url
 */
defined('IN_IA') or exit('Access Denied');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require __DIR__ . '/source/vendor/autoload.php';
require __DIR__ . '/source/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/source/common/config/bootstrap.php';
require __DIR__ . '/source/frontend/config/bootstrap.php';

class Jc_jingjirenModuleWxapp extends WeModuleWxapp {
    public function doPageIndex(){
        $config = yii\helpers\ArrayHelper::merge(
            require __DIR__ . '/source/common/config/main.php',
            require __DIR__ . '/source/frontend/config/main.php'
        );
        (new yii\web\Application($config))->run();
    }
}