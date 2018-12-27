<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/28
 * Time: 14:12
 */

namespace backend\models;

use common\models\Category;
use yii\db\ActiveRecord;
class BkCategory extends Category
{
    public function scenarios()
    {
        return [
            'create'  => ['uniacid','title','is_index','is_open','create_time','update_time','description'],
            'update'  => ['title','is_index','is_open','update_time','description'],
            'del'     =>['is_del'],
        ];
    }

    //获取一条
    public function fetchone($id=null)
    {
        return self::findOne($id);
    }
    //返回分类名，只有名字
    public function fetchOnename($id)
    {

        $list = $this::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return $list['title'];

    }

    //新增
    public function add($values=array()){
        $this->scenario = 'create';
        $this->attributes = $values;
        $this->save();
        return [
            'status'=>[
                'state'=>'success',
                'msg'=>'新增成功'
            ] ];

    }
    //编辑
    public function up($id=null,$values=array()){
        $update = $this->fetchOne($id);
        if (empty($update)){
            return [
                'status'=>[
                    'state'=>'success',
                    'msg'=>'没有找到选择的项'
                ] ];
        }
        $update->scenario = 'update';
        $update->attributes = $values;
        $update->save();
        return [
            'status'=>[
                'state'=>'success',
                'msg'=>'编辑成功'
            ] ];

    }
    #删除功能
    public function del($id=null,$values=array()){
        $del = $this->fetchOne($id);
        if (empty($del)){
            return [
                'status'=>[
                    'state'=>'error',
                    'msg'=>'没有选择要删除的'
                ] ];
        }
        $project=new BkProject();
        $where=[
            'category_id'=>$del['id'],
        ];
        $project_list=$project->fetchListAll($where);
        if (!empty($project_list)){
            return [
                'status'=>[
                    'state'=>'project',
                    'msg'=>'分类下还有项目，不能删除'
                ] ];

        }
        $del->scenario = 'del';
        $del->attributes = $values;
        $del->save();
        return [
            'status'=>[
                'state'=>'success',
                'msg'=>'删除成功'
            ] ];

    }
}