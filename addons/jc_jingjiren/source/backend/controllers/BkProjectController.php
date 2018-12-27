<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 18:14
 */

namespace backend\controllers;

use backend\models\BkProcess;
use backend\models\BkProject;
use common\models\Project;
use yii;

class BkProjectController extends BaseController
{

    #获取项目列表
    public function actionIndex()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $all = new BkProject();
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $name = $request->get('name');//项目名
        //$psize = $request->get('size');//编号 待定
        $cate = $request->get('category');//分类
        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['like', 'name', $name],
            ['=', 'category_id', $cate]
        ];
        $page_size = $size == NULL ? '20' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $all->fetchCountNum($where);
        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $all->fetchListAll($where, $limit, $offset, $order = ['order' => SORT_DESC, 'create_time' => SORT_DESC]);
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page,
        ];
    }

    #启用禁用项目
    public function actionOpenproject()
    {
        $projectMd = new BkProject();
        $request = Yii::$app->request;
        $id = $request->get('id');
        $state = $request->get('state');
        $project = $projectMd::findOne(['id' => $id]);
        $project->scenario = 'update';
        $project->is_open = $state;
        $project->save();
        return [
            'status' => [
                'state' => 'success',
                'msg' => '编辑成功'
            ]];

    }

    #获取一条项目 详情
    public function actionOne()
    {
        $project = new BkProject();
        $processMd = new BkProcess();
        $request = Yii::$app->request;
        $id = $request->get('id');
        $where = ['project_id' => $id, 'type' => 3, 'is_del' => 0];
        $list = $project->fetchOne($id);
        $list['process'] = $processMd->fetchlist($where, 'order');
        return $list;
    }

    #删除一个项目
    public function actionDel()
    {
        global $_W;
        $request = Yii::$app->request;
        $id = $request->get('id');
        $values = [
            'is_del' => 1,
        ];
        $project = new BkProject();
        return $project->del($id, $values);
    }

    #批量删除项目
    public function actionDelall()
    {
        global $_W;
        $request = Yii::$app->request;
        $id = $request->get('id');
        $project = new BkProject();
        return $project->delall($id);

    }

    #新建编辑项目
    public function actionCreateup()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $values = $_GPC['__input']['data'];
        $id = $values['id'];
        $now = strtotime('now');
        $project = new BkProject();
        $values['update_time'] = $now;
        $values['uniacid'] = $uniacid;
        $values['vt_price'] = $values['min_price'] . ',' . $values['max_price'];//预计佣金为最小值合并最大值
        $values['tags'] = implode(',', $values['tags']);

//        $one=Project::find()->where(['order'=>$values['order'],'uniacid'=>$uniacid])->asArray()->one();
//        if ($one!=null){
//            return [
//                'status' => [
//                    'state' => 'order',
//                    'msg' => '排序冲突'
//                ]];
//        }else{
        #编辑
        if (!empty($id)) {
            return $project->up($values, $id);
        } else if (empty($id)) {
            $values['create_time'] = $now;
            return $project->add($values);
        }
        //  }
    }

    # 新建专题项目并且显示 暂定
    public function actionZhuanti()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];

        $values = $_GPC['__input']['data'];
        $id = $values['id'];
        $now = strtotime('now');
        $project = new BkProject();
        $values['update_time'] = $now;
        $values['uniacid'] = $uniacid;
        $values['name'] = $_GPC['name'];//项目名字
        $values['subname'] = $_GPC['subname'];//副标题

        #编辑
        if (!empty($id)) {
            return $project->up($values, $id);
        } else if (empty($id)) {
            $values['create_time'] = $now;
            $list = $project->add($values);
            return $list;
        }
    }

}
