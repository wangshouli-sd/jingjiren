<?php
namespace common\models;

use yii;
use common\models\SysParams;

class Sms extends Base
{
    public $alisms_keyid=NULL;
    public $alisms_secret=NULL;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->formatInfo();

    }

    #初始化参数
    public function formatInfo()
    {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        if ($uniacid==null)
        {
            return false;
        }

        $sysparamMd=new SysParams();
        $alisms_keyid=$sysparamMd->fetchOne($uniacid,'alisms_keyid');
        $alisms_secret=$sysparamMd->fetchOne($uniacid,'alisms_secret');

        $this->alisms_keyid=$alisms_keyid['var']!=''?$alisms_keyid['var']:NULL;
        $this->alisms_secret=$alisms_secret['var']!=''?$alisms_secret['var']:NULL;
        $this->save();
        return true;
    }

    #修改参数
    public function editInfo($alisms_keyid,$alisms_secret)
    {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        if ($uniacid==null)
        {
            return false;
        }

        $this->alisms_keyid=$alisms_keyid;
        $this->alisms_secret=$alisms_secret;

        $sysparamMd=new SysParams();
        $sysparamMd->editOne($uniacid,'alisms_keyid',$alisms_keyid);
        $sysparamMd->editOne($uniacid,'alisms_secret',$alisms_secret);

        return true;

    }

    #短信发送
    public function sendMsg($phoneNumber,$signName,$templateCode,array $templateContent)
    {
        if($this->alisms_keyid==NULL OR $this->alisms_secret==NULL)
        {
            return false;
        }

        $aliSender=new \common\tools\alitools\sms\AliSms($this->alisms_keyid,$this->alisms_secret);
        $response=$aliSender->sendMsg($phoneNumber,$signName,$templateCode,$templateContent);
        return $response;
    }
}
