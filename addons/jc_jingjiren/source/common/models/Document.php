<?php
namespace common\models;

class Document extends \common\models\Base
{
    public static function tableName()
    {
        return "{{%document}}";
    }

    #获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        return parent::fetchListAll($where, $limit, $offset, $order);
    }

    #获取详情
    public function fetchDetail($where)
    {
        $docMd=self::findOne($where);
        $info=$docMd==NULL?NULL:$docMd->toArray();
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
