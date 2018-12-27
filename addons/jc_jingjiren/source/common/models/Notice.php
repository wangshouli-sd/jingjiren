<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/6
 * Time: 9:39
 */

namespace common\models;

use common\models\Base;
class Notice extends Base
{
    public static function tableName()
    {
        return '{{%notice}}';
    }
    //获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $uploadMd=new Upload();
        $list= self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        $i=0;
        foreach ($list as $key =>$value){
            $time=$value['create_time'];
            $list[$i]['time']=date('Y/m/d', $time);
            $info=$this->fetchDetail(['id'=>$value['id']]);
            $list[$i]['imgurl']=$info['imgurl'];

            global $_W;
            if ($_W['script_name']=='/app/index.php'){
                $list[$i]['imgurl'] = $_W['siteroot'].$info['imgurl'][0];
            }

            $i++;
        }
        return $list;
    }

    //获取总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
    }

    #获取详情
    public function fetchDetail($where)
    {
        $uploadMd=new Upload();
        $docMd=self::findOne($where);
        $info=$docMd==NULL?NULL:$docMd->toArray();
        $imgs=explode(',',$info['img']);
        foreach ($imgs as $imgsid){
            $where=[
                'id'=>$imgsid
                ];
            $url=$uploadMd->fetchOne($where);
            $imgurl[]=$url['path'];
        }
        $info['imgurl']=$imgurl;
        return  $info;
    }

    #添加一条
    public function addOne($params)
    {
        $params['create_time']=time();
        $params['update_time']=time();
        foreach ($params as $key => $var)
        {
            $this->$key=$var;
        }
        return $this->save();
    }

    #更新一条
    public function editOne($where,$params)
    {
        $docMd=self::findOne($where);
        if($docMd==NULL)
        {
            return false;
        }
        foreach ($params as $key => $var)
        {
            $docMd->$key=$var;
        }
        return $docMd->save();
    }

    #删除一条(is_del 修改)
    public function delOne($where)
    {
        $docMd=self::findOne($where);
        if($docMd==NULL)
        {
            return false;
        }
        $docMd->is_del='1';
        return $docMd->save();
    }

}