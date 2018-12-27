<?php
/**
 * Created by PhpStorm.
 * User: zmy
 * Date: 18-10-12
 * Time: 下午4:57
 */

namespace backend\controllers;

use common\models\SysParams;
use Yii;

class BkBannerController extends BaseController
{

    # banner 列表
    public function actionBannerList()
    {
        global $_GPC, $_W;
        $query =  (new \yii\db\Query());
        $result = $query->select(['b.*','u.path','p.name as title'])->from("{{%banner}} as b")
            ->leftJoin("{{%upload}} as u", "b.img_patch = u.id")
            ->leftJoin("{{%project}} as p", "p.id = b.params")
            ->where(['b.uniacid'=>$_W['uniacid']])
            ->all();
        foreach ($result as $k => $v){
            $result[$k]['path'] = 'http://'.$_SERVER['HTTP_HOST'].$v['path'];
        }
        return $result;
    }

    /**
     * 添加轮播
     */
    public function actionBannerAdd()
    {
        global $_GPC, $_W;
        $result = Yii::$app->db->createCommand()->insert("{{%banner}}", [
            'uniacid'=>$_W['uniacid'],
            'img_patch' => $_GPC['__input']['img_patch'],
            'params' => $_GPC['__input']['params'],
            'to_patch' => $_GPC['__input']['to_patch'],
            'create_time' => $_SERVER['REQUEST_TIME'],
            'is_del' =>'0',
        ]);
        if($result->execute()){
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '添加成功'
                ]
            ];
        }else{
           return [
                'status' => [
                    'state' => 'error',
                    'msg' => '添加失败',
                ]
            ];
        }
    }

    /**
     * 编辑轮播
     */
    public function actionBannerEdit()
    {
        global $_GPC, $_W;
        $result = Yii::$app->db->createCommand()->update("{{%banner}}", [
            'uniacid'=>$_W['uniacid'],
            'img_patch' => $_GPC['__input']['img_patch'],
            'params' => $_GPC['__input']['params'],
            'to_patch' => $_GPC['__input']['to_patch'],
            'create_time' => $_SERVER['REQUEST_TIME'],
        ],['banner_id' =>$_GPC['__input']['banner_id'] ]);
        if($result->execute()){
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '修改成功'
                ]
            ];
        }else{
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '修改成功'
                ]
            ];
        }
    }
    /**
     * 删除轮播
     */
    public function actionBannerDelete()
    {
        global $_GPC, $_W;
        $result = Yii::$app->db->createCommand()->delete("{{%banner}}",['banner_id' =>$_GPC['banner_id'] ]);
        if($result->execute()){
            return [
                'status' => [
                    'state' => "success",
                    'msg' => '删除成功'
                ]
            ];
        }else{
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '删除失败',
                ]
            ];
        }
    }

}