<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/30
 * Time: 10:30
 */

namespace backend\models;

use common\models\Table;

class BkTable extends Table
{

    public function scenarios()
    {
        return [
            'create' => ['name', 'project_id', 'order', 'create_time', 'update_time'],
            'update' => ['name', 'project_id', 'order', 'update_time', 'is_del'],
            'del' => ['is_del', 'update_time'],
        ];
    }

    //列表展示已经设置的字段。
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        return parent::fetchListAll($where, $limit, $offset, $order);
    }

    //自己设置的字段总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    //获取一条
    public function One($id = null)
    {
        return self::find()
            ->where(['id' => $id])
            ->one();
    }

    //新增  先添加到table表，在 加到 project表
    public function add($values = array(), $project_id = null)
    {
        $this->scenario = 'create';
        $this->attributes = $values;
        $this->save();
        $dic_id = $this->attributes['id'];
        //从task表取数据
        $task = new BkProject();
        $list = $task->fetchOne($project_id);
        if ($list['table_id'] == 0) {
            $values = [
                'table_id' => $dic_id
            ];
            $list->scenario = 'dic_cr';
            $list->attributes = $values;
            $list->save();
        } else if ($list['table_id'] !== 0) {
            $table_id = $list['table_id'] . ',' . $dic_id;
            $values = [
                'table_id' => $table_id
            ];
            $list->scenario = 'dic_up';
            $list->attributes = $values;
            $list->save();
        }
        return [
            'status' => [
                'state' => 'success',
                'msg' => '添加成功'
            ]];

    }

    //编辑
    public function up($values = array(), $id = null)
    {
        $up = $this->One($id);
        $up->scenario = 'update';
        $up->attributes = $values;
        $up->save();
        return [
            'status' => [
                'state' => 'success',
                'msg' => '编辑成功'
            ]];


    }

    //删除
    public function del($id = null, $values = array())
    {
        $task = new BkTask();
        $del = $this->One($id);
        if (empty($del)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '没有找到需要删除的选项'
                ]];
        }
        $where = [
            'project_id' => $del['id']
        ];
        $task_list = $task->fetchListAll($where);
        if (!empty($task_list)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '有任务在进行，不能删除'
                ]];
        }
        $del->scenario = 'del';
        $del->attributes = $values;
        $del->save();
        return [
            'status' => [
                'state' => 'error',
                'msg' => '删除成功，之前已完成的任务里不会删除'
            ]];
    }

}