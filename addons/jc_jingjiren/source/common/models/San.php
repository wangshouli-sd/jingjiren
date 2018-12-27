<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/5
 * Time: 17:46
 */

namespace common\models;

use backend\models\BkCategory;
use common\models\Base;
class San extends Base
{
    public static function tableName()
    {
        return '{{%san}}';
    }

    public function  scenarios()
    {
        return [
            'create' => ['id','uniacid','name','title','img','is_index','type','url','create_time','update_time'],
            'update' => ['uniacid','name','title','img','is_index','type','url','update_time'],
            'del'    =>['is_del','update_time'],
            'banner'    =>['order','type','img','is_index','url','create_time','update_time'],
            'upbanner'    =>['order','img','is_index','url','update_time'],
        ];
    }

    #修改信息
    public function editOne($info)
    {
        global  $_W;
        $sysMd=self::findOne(['id'=>$info['id']]);
        $sysMd->scenario = 'update';
        $sysMd->id=$info['id'];
        $sysMd->name=$info['name'];
        $sysMd->title=$info['title'];
        $sysMd->img=$info['img'];
        $sysMd->url=$info['url'];
        $sysMd->update_time=time();
        if($sysMd->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    #新增
    public function add($info)
    { global  $_W;
        $uniacid=$_W['uniacid'];
        $sysMd=new San();
        $sysMd->scenario = 'create';
        $sysMd->id=$info['id'];
        $sysMd->name=$info['name'];
        $sysMd->title=$info['title'];
        $sysMd->img=$info['img'];
        $sysMd->url=$info['url'];
        $sysMd->type=2;
        $sysMd->uniacid=$uniacid;
        $sysMd->create_time=time();
        $sysMd->update_time=time();
        if($sysMd->save())
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    //获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $list= parent::fetchListAll($where, $limit, $offset, $order);
        $i=0;
        foreach ($list as $key =>$value){
            $uploadMd=new Upload();
            $cateMd=new BkCategory();
            $info=$uploadMd->fetchOne(['id'=>$value['img']]);
            $list[$i]['path']=$info['path'];
            global $_W;
            if ($_W['script_name']=='/app/index.php'){
                $list[$i]['path'] = $_W['siteroot'].$info['path'];
            }

            $catainfo=$cateMd->fetchOnename($value['url']);
            $list[$i]['category']=$catainfo;
            $list[$i]['img_name']=$info['name'];
            $i++;
        }
        return $list;
    }

    //获取一条
    public function one($where = array()){
        return self::find()
            ->where($where)
            ->asArray()
            ->one();
    }

    //del
    public function del($id){
        global  $_W;
        $sysMd=self::findOne(['id'=>$id]);
        $sysMd->scenario = 'del';
        $sysMd->is_del=1;
        $sysMd->update_time=time();
        if($sysMd->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
