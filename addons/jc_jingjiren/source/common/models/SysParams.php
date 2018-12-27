<?php
namespace common\models;

use yii;

class SysParams extends Base
{
    public static function tableName()
    {
        return "{{%sysparams}}";
    }

    #获取信息
    public function fetchOne($uniacid,$name)
    {
        $info_where=[
            'uniacid'=>$uniacid,
            'name'=>$name,
            'is_del'=>'0'
        ];
        $info=self::findOne($info_where);
        if($info!=NULL)
        {
            return $info->toArray();
        }
        else
        {
            if(!$this->formatOne($uniacid,$name))
            {
                return false;
            }
            $info_where=[
                'uniacid'=>$uniacid,
                'name'=>$name,
                'is_del'=>'0'
            ];
            return self::findOne($info_where)->toArray();
        }
    }

    #修改信息
    public function editOne($uniacid,$name,$var)
    {
        $info=$this->fetchOne($uniacid,$name);
        $sysMd=self::findOne(['id'=>$info['id']]);
        $sysMd->var=$var;
        if($sysMd->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    #修改信息
    public function delOne($uniacid,$name)
    {
        $info=$this->fetchOne($uniacid,$name);
        $sysMd=self::findOne(['id'=>$info['id']]);
        $sysMd->is_del='1';
        if($sysMd->save())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    #初始化信息
    public function formatOne($uniacid,$name)
    {
        $check_where=[
            'uniacid'=>$uniacid,
            'name'=>$name,
        ];
        $check=self::findOne($check_where);
        if($check!=NULL)
        {
            return false;
        }
        $sysMd=new self();
        $sysMd->uniacid=$uniacid;
        $sysMd->name=$name;
        $sysMd->create_time=time();
        $sysMd->update_time=time();
        if($sysMd->save())
        {
            return $sysMd;
        }
        else
        {
            return false;
        }
    }

    /*返回列表*/
    public function fetchList($where=array(),$limit=null,$offset=null,$order=null)
    {
        return self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
    }

    #获取信息的值
    public function fetchOneVar($uniacid,$name)
    {
        $info_where=[
            'uniacid'=>$uniacid,
            'name'=>$name,
            'is_del'=>'0'
        ];
        $info=self::findOne($info_where);
        if($info!=NULL)
        {
            $info= $info->toArray();
            return $info['var'];
        }
        else
        {
            if(!$this->formatOne($uniacid,$name))
            {
                return false;
            }
            $info_where=[
                'uniacid'=>$uniacid,
                'name'=>$name,
                'is_del'=>'0'
            ];
            $info= self::findOne($info_where)->toArray();
            return $info['var'];
        }
    }

    #根据id获取信息
    public function one($id){
        $info_where=[
            'id'=>$id,
        ];
        $info= self::findOne($info_where)->toArray();
        return $info;
    }
}
