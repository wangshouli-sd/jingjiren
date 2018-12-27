<?php
namespace common\models;

use backend\models\BkProcess;
use frontend\models\FrProject;
use yii;

class WalletLog extends Base
{
    public static function tableName()
    {
        return "{{%wallet_log}}";
    }

    #添加 余额变动 记录
    public function addOne($member_id,$task_id,$id,$change_money,$type,$name)
    {
        $member_info=Member::findOne(['id'=>$member_id]);
        if($member_info==NULL)
        {
            return false;
        }

        $wallet_info=Wallet::findOne(['member_id'=>$member_id]);
        if($wallet_info==NULL)
        {
            return false;
        }

        $this->uniacid=$member_info->uniacid;
        $this->member_id=$member_id;
        $this->task_id=$task_id;
        $this->process_id=$id;
        $this->wallet_id=$wallet_info->id;
        $this->name=$name;
        $this->change_money=$change_money;
        $this->type=$type;
        $this->create_time=time();
        $this->update_time=time();

        if($this->save())
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    #列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $taskMd=new Task();
        $memberMd=new Member();
        $list= self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        $i=0;
        foreach ($list as $key =>$value){
            $task=$taskMd->fetchOne(['id'=>$value['task_id']]);
            $list[$i]['task_number']=$task['task_number'];//订单号
            
            $list[$i]['allmoney']=$task['total_price'];//签约总额
            $list[$i]['time']=date('Y/m/d H:i',$value['create_time']);
            $send_id=$task['send_id'];
            $member_info=$memberMd->fetchOne($send_id);
            $list[$i]['jingname']=$member_info['name'];
            $list[$i]['name']=$value['name']=='grand_son'?'grand':$value['name'];

            $projectMd=new FrProject();
            $projectinfo=$projectMd->fetchOne($task['project_id']);
            $list[$i]['task_name']=$projectinfo['name'];
            $list[$i]['img_path']=$projectinfo['img_path'];

            $processMd=new BkProcess();
            $info=$processMd->fetchOne(['id'=>$value['process_id']]);
            $list[$i]['processname']=$info['process_name'];
            $i++;
        }
        return $list;

    }

    #查询任务下的收入总额 任务id  收入方式
    public function taskmoney($id,$name){
        $where=[
            'task_id'=>$id,
            'type'=>'1',
            'name'=>$name
        ];
        $list= self::find()
            ->filterWhere($where)
            ->asArray()
            ->all();
        foreach ($list as $key =>$value){
            $price[]=$value['change_money'];
        }
        $money=($price!=null)?array_sum($price):'0';
          return $money;
    }
}
