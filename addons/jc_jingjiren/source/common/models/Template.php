<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/18
 * Time: 10:52
 */

namespace common\models;


use frontend\models\FrCode;

class Template extends Base
{
    #经纪人认证结果模板
    public function zhuce($openId,$formId,$user_onlyid,$user_name,$phone,$result,$why)
    {
        global $_W;
        $sysMd=new SysParams();
        $tempalte_id=$sysMd->fetchOneVar($_W['uniacid'],'templet_renzheng');
        // $tempalte_id = 'djkzeMTGGp-SuZj4_d4gFF0K6rkF2JfFDK1QRkM1gvs';
        $date_time = time();
        $data=array(
            'keyword1'  => array('value'=>$user_onlyid,'color'=>'#000000'),//        用户账号
            'keyword2'  => array('value'=>'真实身份认证','color'=>'#000000'),//认证身份
            'keyword3'  => array('value'=>$user_name,'color'=>'#000000'),//认证姓名
            'keyword4'  => array('value'=>$phone,'color'=>'#000000'),//认证手机号
            'keyword5'  => array('value'=>$result,'color'=>'#000000'),//认证结果
            'keyword6'  => array('value'=>$why,'color'=>'#000000'),//失败原因
        );
        $template = array(
            'touser' => $openId,
            'template_id' => $tempalte_id,
            'url' => 'pages/renwu/renwu-index/renwu-index',
            'form_id'=>$formId,
            'topcolor' =>'#7B68EE',
            'data' => $data,
        );
        $wxapp=new Wxapp();
        $row= $wxapp->getappinfo();
        $appid =$row['key'];
        $secret =$row['secret'];
        //获取 access_token
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
        $_SESSION['access_token'] = "";
        $_SESSION['expires_in'] = 0;
        if (!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in'])) {

            $frcode=new FrCode();
            $json = $frcode->httpRequest($access_token);
            $json = json_decode($json, true);
            $_SESSION['access_token'] = $json['access_token'];
            $_SESSION['expires_in'] = time() + 7200;
            $ACCESS_TOKEN = $json["access_token"];
        } else {

            $ACCESS_TOKEN = $_SESSION["access_token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$ACCESS_TOKEN;
        $template =  json_encode($template);
        $result = $frcode->httpRequest($url,$template,'POST');
        $formidMd=new Formid();
        $formidMd->delOne(['formid'=>$formId]);
        return $result;

    }

    #审核结果模板
    public function ShenHe($openId,$formId,$task_number,$check_conent,$result,$why,$start_time)
    {
        global $_W;
        $sysMd=new SysParams();
        $tempalte_id=$sysMd->fetchOneVar($_W['uniacid'],'templet_shenhe');
       // $tempalte_id = 'ZLev5-o0C7iwePYhBHhBCGAV0d24maQpfFwjLyvYfX8';

        $date_time = time();
        $data=array(
            'keyword1'  => array('value'=>$task_number,'color'=>'#000000'),//        订单编号
            'keyword2'  => array('value'=>$check_conent,'color'=>'#000000'),//审核内容
            'keyword3'  => array('value'=>$result,'color'=>'#000000'),//审核结果
            'keyword4'  => array('value'=>$why,'color'=>'#000000'),//拒绝理由
            'keyword5'  => array('value'=>$start_time,'color'=>'#000000'),//提交时间
            'keyword6'  => array('value'=>date('Y-m-d',$date_time),'color'=>'#000000'),//审核时间
        );
        $template = array(
            'touser' => $openId,
            'template_id' => $tempalte_id,
            'url' => 'pages/renwu/renwu-index/renwu-index',
            'form_id'=>$formId,
            'topcolor' =>'#7B68EE',
            'data' => $data,
        );
        $wxapp=new Wxapp();
        $row= $wxapp->getappinfo();
        $appid =$row['key'];
        $secret =$row['secret'];
        //获取 access_token
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
        $_SESSION['access_token'] = "";
        $_SESSION['expires_in'] = 0;
        if (!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in'])) {

            $frcode=new FrCode();
            $json = $frcode->httpRequest($access_token);
            $json = json_decode($json, true);
            $_SESSION['access_token'] = $json['access_token'];
            $_SESSION['expires_in'] = time() + 7200;
            $ACCESS_TOKEN = $json["access_token"];
        } else {

            $ACCESS_TOKEN = $_SESSION["access_token"];
        }
    $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$ACCESS_TOKEN;
    $template =  json_encode($template);
    $result = $frcode->httpRequest($url,$template,'POST');
    return $result;
  }

    #经纪人提现成功通知
    public function TXsuccess($openId,$formId,$user_name,$txdao,$zhanghao,$money,$daomoney,$remark)
    {
        global $_W;
        $sysMd=new SysParams();
        $tempalte_id=$sysMd->fetchOneVar($_W['uniacid'],'templet_TXsuccess');
        //$tempalte_id = 'OJp8W7vqdNMg_hvA9cfbpfaaRgXMTnKrQ_bOqXnPdBM';
        $date_time = time();
        $data=array(
            'keyword1'  => array('value'=>$user_name,'color'=>'#000000'),//        姓名
            'keyword2'  => array('value'=>$txdao,'color'=>'#000000'),//提现至
            'keyword3'  => array('value'=>$zhanghao,'color'=>'#000000'),//提现账号
            'keyword4'  => array('value'=>floatval($money).'元','color'=>'#000000'),//提现金额
            'keyword5'  => array('value'=>floatval($daomoney).'元','color'=>'#000000'),//到帐金额
            'keyword6'  => array('value'=>date('Y-m-d',$date_time),'color'=>'#000000'),//到帐时间
            'keyword7'  => array('value'=>$remark,'color'=>'#000000'),//备注
        );
        $template = array(
            'touser' => $openId,
            'template_id' => $tempalte_id,
            'url' => 'pages/renwu/renwu-index/renwu-index',
            'form_id'=>$formId,
            'topcolor' =>'#7B68EE',
            'data' => $data,
        );
        $wxapp=new Wxapp();
        $row= $wxapp->getappinfo();
        $appid =$row['key'];
        $secret =$row['secret'];
        //获取 access_token
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
        $_SESSION['access_token'] = "";
        $_SESSION['expires_in'] = 0;
        if (!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in'])) {

            $frcode=new FrCode();
            $json = $frcode->httpRequest($access_token);
            $json = json_decode($json, true);
            $_SESSION['access_token'] = $json['access_token'];
            $_SESSION['expires_in'] = time() + 7200;
            $ACCESS_TOKEN = $json["access_token"];
        } else {

            $ACCESS_TOKEN = $_SESSION["access_token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$ACCESS_TOKEN;
        $template =  json_encode($template);
        $result = $frcode->httpRequest($url,$template,'POST');
        return $result;

    }
    #经纪人提现失败通知
    public function TXfail($openId,$formId,$Tx_number,$user_name,$zhanghao,$money,$why)
    {
        global $_W;
        $sysMd=new SysParams();
        $tempalte_id=$sysMd->fetchOneVar($_W['uniacid'],'templet_TXfail');
       // $tempalte_id = 'RXykKtCkbAG7a0_o56AiE71RTa54o4pBEzNXTsliPc0';

        $date_time = time();
        $data=array(
            'keyword1'  => array('value'=>$Tx_number,'color'=>'#000000'),//        提现单号
            'keyword2'  => array('value'=>$user_name,'color'=>'#000000'),//姓名
            'keyword3'  => array('value'=>$zhanghao,'color'=>'#000000'),//提现账号
            'keyword4'  => array('value'=>floatval($money).'元','color'=>'#000000'),//提现金额
            'keyword5'  => array('value'=>'支付宝','color'=>'#000000'),//提现至
            'keyword6'  => array('value'=>$why,'color'=>'#000000'),//失败原因
        );
        $template = array(
            'touser' => $openId,
            'template_id' => $tempalte_id,
            'url' => 'pages/renwu/renwu-index/renwu-index',
            'form_id'=>$formId,
            'topcolor' =>'#7B68EE',
            'data' => $data,
        );
        $wxapp=new Wxapp();
        $row= $wxapp->getappinfo();
        $appid =$row['key'];
        $secret =$row['secret'];
        //获取 access_token
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;
        $_SESSION['access_token'] = "";
        $_SESSION['expires_in'] = 0;
        if (!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in'])) {

            $frcode=new FrCode();
            $json = $frcode->httpRequest($access_token);
            $json = json_decode($json, true);
            $_SESSION['access_token'] = $json['access_token'];
            $_SESSION['expires_in'] = time() + 7200;
            $ACCESS_TOKEN = $json["access_token"];
        } else {

            $ACCESS_TOKEN = $_SESSION["access_token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$ACCESS_TOKEN;
        $template =  json_encode($template);
        $result = $frcode->httpRequest($url,$template,'POST');
        return $result;

    }
}