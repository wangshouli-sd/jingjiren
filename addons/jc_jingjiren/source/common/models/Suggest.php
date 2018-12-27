<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/6
 * Time: 14:36
 */

namespace common\models;


class Suggest extends Base
{
    public static function tableName()
    {
        return '{{%suggest}}';
    }

    //获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        //return parent::fetchListAll($where, $limit, $offset, $order);

        $list= parent::fetchListAll($where, $limit, $offset, $order);
        $i=0;
        foreach ($list as $key =>$value){
            $time=$value['create_time'];
            $list[$i]['time']=date('Y/m/d', $time);
            $info=$this->fetchDetail(['id'=>$value['id']]);
            $list[$i]['imgurl']=$info['imgurl'];
            //真实姓名
            $memberMd=new Member();
            $member=$memberMd->fetchOne($value['send_id']);
            $list[$i]['truename']=$member['name'];

            $uploadMd=new Upload();
            $list[$i]['authorimg']=$member['image'];
            $i++;
        }
        return $list;
    }

    #获取详情
    public function fetchDetail($where)
    {
        $uploadMd=new Upload();
        $memberMd=new Member();
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
        $member=$memberMd->fetchOne($docMd['send_id']);
        $info['truename']=$member['name'];
        $info['imgurl']=$imgurl;
        return  $info;
    }
    //获取总数
    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
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

    #更新一条
    public function readOne($where)
    {
        $docMd=self::findOne($where);
        if($docMd==NULL)
        {
            return false;
        }
        $docMd->is_read='1';
        return $docMd->save();
    }
}