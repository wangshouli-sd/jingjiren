<?php

namespace frontend\controllers;

use common\models\Formid;
use common\models\SysParams;
use common\models\Template;
use common\models\Upload;
//use common\tools\alitools\Sms\AliSms;
use frontend\models\FrMember;
use yii;
use yii\base\Module;

class BaseController extends \common\controllers\BaseController
{
    //添加图片
    public function actionIndex()
    {
        $upMd = new Upload();
        foreach ($_FILES as $file) {
            $id = $upMd->uploadOne($file);
        }
        if (empty($id)) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '上传失败'
                ]];
        }
        return $id;
    }

    //注册经纪人发送手机验证码接口
    public function actionSendcode()
    {
        $memberMd = new FrMember();
        $sysMd = new SysParams();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $request_data =$_GPC['__input'];
        $phone = $request_data['phone'];
        $openid = $request_data['openid'];
        $id = $memberMd->memberid($openid);
        if ($id==null){
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '发送失败',
                ]];
        }

        #初始化
        $accessKeyId = $sysMd->fetchOneVar($uniacid, 'alisms_keyid');
        $accessKeySecret = $sysMd->fetchOneVar($uniacid, 'alisms_secret');
        $smsMd = new \common\tools\alitools\Sms\AliSms($accessKeyId, $accessKeySecret);

        #短信发送
        $code = rand(pow(10, (6 - 1)), pow(10, 6) - 1);
        $phoneNumber = $phone;
        $signName = $sysMd->fetchOneVar($uniacid, 'alisms_singname');
        $templateCode = $sysMd->fetchOneVar($uniacid, 'alisms_templatecode');
        $alisms_code = $sysMd->fetchOneVar($uniacid, 'alisms_code');
        //setcookie('XDEBUG_SESSION', 'PHPSTORM');//断点
        $templateContent = [$alisms_code => $code];
        $response = $smsMd->sendMsg($phoneNumber, $signName, $templateCode, $templateContent);
        if ($response->Message = 'OK') {
            $data = [
                'code' => $code,
                'phone' => $phone,
                'access_time' => time()
            ];
            $savecode = $memberMd->editone($id, $data);
            if ($savecode == 1) {
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '发送成功',
                    ]];
            }
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '发送失败',
                ]];
        }

    }

    //指尖科技发送手机验证码接口
    public function actionSendcode2($phone)
    {
        #初始化
        $accessKeyId = 'kvSu0Ag7KTs7LHoN';
        $accessKeySecret ='alCPb9sf8RJ72yW9akQxwJt6jO9rKn';
        $smsMd = new \common\tools\alitools\Sms\AliSms($accessKeyId, $accessKeySecret);

        #短信发送
        $code = rand(pow(10, (6 - 1)), pow(10, 6) - 1);
        $phoneNumber = $phone;
        $signName = '注册验证';
        $templateCode ='SMS_139977332';
        $alisms_code = 'number';
        //setcookie('XDEBUG_SESSION', 'PHPSTORM');//断点
        $templateContent = [$alisms_code => $code];
        $response = $smsMd->sendMsg($phoneNumber, $signName, $templateCode, $templateContent);
        return $response->Message;

    }


    //验证验证码是否正确
    public function actionCheckcode()
    {
        $memberMd = new FrMember();
        global $_W, $_GPC;
        $request_data =$_GPC['__input'];
        $phone = $request_data['phone'];
        $openid = $request_data['openid'];
        $code = $request_data['code'];
        $memberinfo = $memberMd->One(['openid' => $openid]);

        $formid = $request_data['formid'];
        $formidMd=new Formid();
        if ($formidMd->addOne(['member_id'=>$memberinfo['id'],'formid'=>$formid])){

        $remain = time() - $memberinfo['access_time'];//时间差
        $remain = $remain % 3600;//分钟
        $mins = intval($remain / 60);//相差几分钟
        if ($code == $memberinfo['code'] and $phone == $memberinfo['phone'] and $mins <= 5) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '验证成功',
                ]];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '验证失败',
                ]];
        }
        }else {
            return [
                'status' => [
                    'state' => 'formid_empty',
                    'msg' => 'formid获取失败',
                ]];
        }
    }

    //提交身份证照片验证
    public function actionSendcheck()
    {
        $memberMd = new FrMember();
        global $_W, $_GPC;
        $request_data = $_GPC['__input'];
        $name = $request_data['name'];
        $openid = $request_data['openid'];
        $idcard = $request_data['idcard'];
        $img = $request_data['img'];
        $id = $memberMd->memberid($openid);

        $formid = $request_data['formid'];
        $formidMd=new Formid();
        $formidMd->addOne(['member_id'=>$id,'formid'=>$formid]);

        $dade = [
            'name' => $name,
            'idcard_pic' => $img,
            'idcard' => $idcard,
            'is_sender' => 1,
            'update_time' => time(),
            'is_del'=>0
        ];
        return $memberMd->edit($id, $dade);
    }

    //#展示用户协议
    public function actionShow()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $info = $sysparamsMd->fetchOne($uniacid, 'agreement');
        return $info['var'];
    }

    #展示客服参数
    public function actionKefu(){
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $info['name'] = $sysparamsMd->fetchOne($uniacid, 'service_name')['var'];
        $info['qq'] = $sysparamsMd->fetchOne($uniacid, 'service_qq')['var'];
        $info['phone'] = $sysparamsMd->fetchOne($uniacid, 'service_phone')['var'];
        return $info;
    }

    #发送模板消息
    public function actionTest($openid){
        $Tel=new Template();
        $Tel->SendTemplate($openid,'','','');
        return $Tel;
    }
}


