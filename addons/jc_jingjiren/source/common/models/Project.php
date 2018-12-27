<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/5
 * Time: 16:38
 */

namespace common\models;

use phpDocumentor\Reflection\Types\Self_;

class Project extends Base
{
    static function tableName()
    {
        return "{{%project}}";
    }

    public function scenarios()
    {
        return [
            'create' => ['uniacid', 'order', 'is_open', 'category_id', 'name', 'subname', 'description', 'slide', 'img', 'description_img', 'vt_price', 'vt_price_percent', 'vt_price_already', 'vt_price_total', 'vt_project_count', 'tags', 'create_time', 'update_time'],
            'update' => ['uniacid', 'order', 'is_open', 'category_id', 'name', 'subname', 'description', 'slide', 'img', 'description_img', 'vt_price', 'vt_price_percent', 'vt_price_already', 'vt_price_total', 'vt_project_count', 'tags', 'update_time',],
            'del' => ['is_del', 'update_time'],
            'dic_cr' => ['table_id'],
            'dic_up' => ['table_id'],
        ];
    }

    //获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $img = new Upload();
        $taskMd = new Task();
        $list = self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        $i = 0;
        foreach ($list as $key => $value) {
            $img_id = $value['slide'];

            $slide_path = $img->getimg($img_id);
            $list[$i]['slide_path'] = $slide_path;

            $description_path = $img->getimg($value['description_img']);
            $list[$i]['description_path'] = $description_path;

            $img_path = $img->getimg($value['img']);
            $list[$i]['img_path'] = $img_path['0'];
            $vt_price = explode(',', $value['vt_price']);
            $list[$i]['min_price'] = $vt_price[0];
            $list[$i]['max_price'] = $vt_price[1];

            $list[$i]['tags'] = explode(',', $value['tags']);

            $categoryMd = new Category();
            $list[$i]['category'] = $categoryMd->fetchone($value['category_id']);
            $parm = [
                'project_id' => $value['id'],
                'state_progress' => '3'
            ];
            $taskcount = $taskMd->fetchCountNum($parm);
            $list[$i]['vt_price_total'] = $value['vt_price_total'] + $taskcount;

            $list[$i]['time'] = date('Y-m-d', $value['create_time']);
            $i++;
        }
        return $list;
    }

    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    //获取一条   详情页
    public function fetchOne($id)
    {
        $upload = new Upload();
        $list = $this::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $tags = explode(',', $list['tags']);
        $list['tags'] = $tags;

        $list['slide_path'] = $upload->info($list['slide']);

        $list['description_path'] = $upload->info($list['description_img']);

        $img = $upload->fetchOne(['id' => $list['img']]);
        $list['img_path'] = $img['path'];
        global $_W;
        if ($_W['script_name'] == '/app/index.php') {
            $list['img_path'] = $_W['siteroot'] . $img['path'];
        }

        $vt_price = explode(',', $list['vt_price']);
        $list['min_price'] = $vt_price[0];
        $list['max_price'] = $vt_price[1];

        $list['time'] = date('Y/m/d', $list['create_time']);
        return $list;
    }


    //新增 项目和项目流程
    public function add($values = array())
    {
        $processMd = new Process();
        $this->scenario = 'create';
        $this->attributes = $values;
        $this->save();
        $id = $this->id;
        $process = (array)$values['process'];
        return $processMd->add($process, $id, $values['uniacid']);

    }

    //编辑
    public function up($values = array(), $id = null)
    {
        $processMd = new Process();
        $up = self::findOne($id);
        $up->scenario = 'update';
        $up->attributes = $values;
        $up->save();
        //$id = $this->id;
        $process = (array)$values['process'];
        return $processMd->upprojectprocess($process, $id, $values['uniacid']);
    }

    //删除
    public function del($id = null, $values = array())
    {
        $task = new Task();
        $del = self::findOne($id);
        if (empty($del)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '没有找到需要删除的选项'
                ]];
        }
        $where = [
            'project_id' => $id
        ];
        $task_list = $task->fetchListAll($where);
        if (!empty($task_list)) {
            return [
                'status' => [
                    'state' => 'task',
                    'msg' => '有任务在进行，不能删除'
                ]];
        }
        $del->scenario = 'del';
        $del->attributes = $values;
        $del->save();
        return 0;
    }

    //批量删除
    public function delall($id)
    {
        $ids = explode(',', $id);
        foreach ($ids as $key) {
            $projectMd = new Project();
            $task = new Task();
            $task_info = $task->fetchOne(['project_id' => $ids, 'is_del' => 0]);
            if (!empty($task_info) and $task_info != null and $task_info != 0) {
                return [
                    'status' => [
                        'state' => 'task',
                        'msg' => '有任务在进行，不能删除'
                    ]];
            }
            $one = self::findOne($key);
            $values = [
                'is_del' => 1,
                'update_time' => time()
            ];
            $one->scenario = 'del';
            $one->attributes = $values;
            $one->save();
        }
        return [
            'status' => [
                'state' => 'success',
                'msg' => '删除成功'
            ],
        ];

    }

}
