<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/12
 * Time: 15:24
 */

namespace common\models;


class Bank extends Base
{
    public static function tableName()
    {
        return '{{%bank}}';
    }

    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $list= parent::fetchListAll($where, $limit, $offset, 'order');
        return $list;
    }

    public function fetchCountNum($where = array())
    {
        return parent::fetchCountNum($where);
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
        if ( $this->save()){
            return [
                'state' => 'success',
                'msg' => '成功',
            ];
        } else {
            return [
                'state' => 'error',
                'msg' => '失败',
            ];

        }
    }

    #更新一条
    public function editOne($where,$params)
    {
        $docMd=self::findOne($where);
        if($docMd==NULL)
        {
            return [
                'state' => 'error',
                'msg' => '失败，找不到那一条数据',
            ];
        }
        foreach ($params as $key => $var)
        {
            $docMd->$key=$var;
        }
        if ( $docMd->save()){
            return [
                'state' => 'success',
                'msg' => '成功',
            ];
        } else {
            return [
                'state' => 'error',
                'msg' => '失败，修改不了',
            ];

        }
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

    #获取一条
    public function one($where){
        return self::find()
            ->where($where)
            ->asArray()
            ->one();
    }

    #通过id 查银行名
    public function bankname($id){
        $list= self::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return $list['bank_name'];
    }

}