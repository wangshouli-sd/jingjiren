<?php
/**
 * jc_jingjiren模块定义
 *
 * @author 优创知云
 * @url
 */
defined('IN_IA') or exit('Access Denied');
defined('DIST_PAHT') or define('DIST_PATH','../addons/jc_jingjiren');

class Jc_jingjirenModule extends WeModule {

	public function welcomeDisplay($menus = array()) {
	    global $_W,$_GPC;

        $dist_path='../addons/jc_jingjiren';
        include __DIR__.'/dist/index.html';
	}
}

