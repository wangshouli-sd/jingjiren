<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/24
 * Time: 16:21
 */

namespace backend\models;
use common\models\Base;
use common\models\Process;

class BkProcess extends Process
{
    //获取所有项目流程状态
    public function fetchlist($where,$order)
    {
        $list = $this::find()
            ->where($where)
            ->orderBy($order)
            ->asArray()
            ->all();
        $i=0;
        foreach ($list as $key =>$value){
            $list[$i]['finish_time']=($value['state']==3)?date('Y/m/d H:i', $value['update_time']):'';
            $i++;
        }
        if (!empty($list)) {
            return $list;
        }else {
         return   ['status'=>[
                'state'=>'error',
                'msg'=>'获取不到值'
            ] ];
        }
    }

    //获取一条   流程
   public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
   {
       return parent::fetchListAll($where, $limit, $offset, $order);
   }

}