<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/5
 * Time: 17:31
 */

namespace common\models;

use backend\models\BkProject;
use backend\models\BkTask;
use common\models\Base;
class Category extends Base
{
    public static function tableName()
    {
        return "{{%project_category}}";
    }
    //获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $list= parent::fetchListAll($where, $limit, $offset, $order);
        $i=0;
        foreach ($list as $key =>$value){
            $create_time=$value['create_time']==null?0:$value['create_time'];
            $list[$i]['time']=date('Y-m-d',$create_time);

            $projectMd=new BkProject();
            $project_ids=$projectMd->getids(['category_id'=>$value['id']]);
            $taskMd=new BkTask();
            $where=[
                'project_id'=>$project_ids,
                'state_progress'=>3
            ];
            $list[$i]['task_count']=$taskMd->fetchCountNum($where);
            if ($project_ids==null){
                $list[$i]['task_count']='0';
            }
            $i++;
        }
        return $list;
    }

    //返回分类名，只有名字
    public function fetchOne($id)
    {

        $list = $this::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return $list['title'];

    }


    //获取分类下的已完成任务钱数
    public function gettask($project_ids,$month_start,$month_end,$uniacid){
        $where=[
            'and',
            ['in','project_id',$project_ids],
            ['=','state_progress',3],
            ['between', 'update_time', $month_start, $month_end],
            ['=','uniacid',$uniacid]
        ];
        $taskMd=new BkTask();
        $tasklist=$taskMd->fetchListAll($where,$limit = null, $offset = null, $order = null);
        if ($tasklist==null){
            return 0;
        }
        foreach ($tasklist as $key =>$value){
            $price=$value['total_price'];//成交金额
            $main_percent=$value['back_percent'];//返回经纪人的佣金比例
            $main_price=$price*$main_percent/100;//经纪人的提成

            $wallMd=new WalletLog();
            $one_price=$wallMd->taskmoney($value['id'],'son');
            $two_price=$wallMd->taskmoney($value['id'],'grand_son');
            $main_priceeee=$wallMd->taskmoney($value['id'],'main');

            $task_price[]=$price-$one_price-$two_price-$main_price;
        }
        $sum=array_sum($task_price);
        return $sum;
    }

    #根据月份查出所有的
    public function monthmoney($id,$uniacid){
        $projectMd=new BkProject();
        $project_ids=$projectMd->getids(['category_id'=>$id]);

        #根据当前月份向前推八个月
        $enddate = date('Y-m');
        $m=(date('m')-7)>0?(date('m')-7):(date('m')-7+12);
        $Y=(date('m')-7)>0?date('Y'):date('Y')-1;
        $startdate = $Y."-".$m;
        $s = strtotime($startdate);
        $e = strtotime($enddate);
        $num = (date('Y',$e)-date('Y',$s)-1)*12+(12-date('m',$s)+1)+date('m',$e);
        $months = array();
        for($i=0; $i<$num; $i++){
            $d = mktime(0,0,0,date('m',$s)+$i,date('d',$s),date('Y',$s));
            $months[] = date('Y-m',$d);
        }
        foreach ($months as $key){
            $month_start[] = strtotime($key);//指定月份月初时间戳
            $month_end[] = mktime(23, 59, 59, date('m', strtotime($key))+1, 00);//指定月份月末时间戳
        }
        $money['0']=$this->gettask($project_ids,$month_start['0'],$month_end['0'],$uniacid);
        $money['1']=$this->gettask($project_ids,$month_start['1'],$month_end['1'],$uniacid);
        $money['2']=$this->gettask($project_ids,$month_start['2'],$month_end['2'],$uniacid);
        $money['3']=$this->gettask($project_ids,$month_start['3'],$month_end['3'],$uniacid);
        $money['4']=$this->gettask($project_ids,$month_start['4'],$month_end['4'],$uniacid);
        $money['5']=$this->gettask($project_ids,$month_start['5'],$month_end['5'],$uniacid);
        $money['6']=$this->gettask($project_ids,$month_start['6'],$month_end['6'],$uniacid);
        $money['7']=$this->gettask($project_ids,$month_start['7'],$month_end['7'],$uniacid);
        return $money;

    }


}