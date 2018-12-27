<?php
/**
 * Created by PhpStorm.
 * User: zmy
 * Date: 18-10-22
 * Time: 下午6:15
 */

namespace frontend\controllers;

use common\models\SysParams;
use common\models\Wxapp;
use frontend\models\FrCode;
use frontend\models\FrDistribution;
use frontend\models\FrMember;
use Yii;
use yii\debug\models\search\Base;

class FrGetopenidController extends BaseController
{
    //通过code获取openid
    public function actionIndex()
    {
        global $_W;
        $memberMd = new FrMember();
        $uniacid = $_W['uniacid'];

        $wxapp=new Wxapp();
        $row= $wxapp->getappinfo();
        $appid = $row['key'];
        $secret = $row['secret'];

        $request = Yii::$app->request;
        $code = $request->get('code');//小程序传来的code值
        $nick = $request->get('nick');//小程序传来的用户昵称
        $imgUrl = $request->get('avaurl');//小程序传来的用户头像地址
        $sex = $_GET['sex'];//小程序传来的用户性别
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $appid . "&secret=" . $secret . "&js_code=" . $code . "&grant_type=authorization_code";
        $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
        $json = json_decode($info);//对json数据解码
        $arr = get_object_vars($json);
        //return $info;
        $openid = $arr['openid'];
        $session_key = $arr['session_key'];
        $data = [
            'openid' => $openid,
            'access_token' => $session_key,
            'uniacid' => $uniacid,
            'image'=>$imgUrl,
            'sex'=>$sex,
        ];
        $memberinfo = $memberMd->One(['openid' => $openid]);
        if ($memberinfo == null and $openid!==null) {
            $onlyid = $memberMd->add($data);
        }

        return [
            'openid' => $openid,
            'session_key' => $session_key,
            'onlyid'=>$memberinfo['onlyid']
        ];
    }

    //小程序的二维码获取
    public function actionGetcode()
    {
        $memberMd=new FrMember();
        $frcodeMd=new FrCode();
        $request = Yii::$app->request;
        $openid = $request->get('openid');//小程序传来的code值
        $memberinfo = $memberMd->One(['openid' => $openid]);
        $onlyid=$memberinfo['onlyid'];
        if ($onlyid==null){
            return false;
        }
        $code=$frcodeMd->main($onlyid);
        return $code;
    }

    #获取父级id 插入到分销表中
    public function actionGetscene(){
        $fenxiaoMd=new FrDistribution();
        $memberMd=new FrMember();
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC;
        $parent=!isset($request_data['scene'])?$_GPC['__input']['scene']:$request_data['scene'];

        $openid=!isset($request_data['openid'])?$_GPC['__input']['openid']:$request_data['openid'];
        $id=$memberMd->memberid($openid);
        $info=$fenxiaoMd->one(['main_id'=>$id]);
        if ($info==null or $info['one_level']=='0'){
            if ($fenxiaoMd->addtwo($uniacid, $parent, $id)==true){
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '设置成功',
                    ]
                 ];
            }else{
                return [
                    'status' => [
                        'state' => "error",
                        'msg' => '新增失败'
                    ]
                ];
            }
        }
    }

}