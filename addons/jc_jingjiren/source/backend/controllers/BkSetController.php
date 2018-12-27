<?php
/**
 * Created by PhpStorm.
 * User: zmy
 * Date: 18-10-12
 * Time: 下午4:57
 */

namespace backend\controllers;


use backend\models\BkProject;
use backend\models\BkTask;
use common\models\Formid;
use common\models\Getmoney;
use common\models\GetmoneyLog;
use common\models\Member;
use common\models\Pay;
use common\models\San;
use common\models\SysParams;
use common\models\Template;
use common\models\Upload;
use function Qiniu\base64_urlSafeDecode;
use function Qiniu\base64_urlSafeEncode;
use Yii;

class BkSetController extends BaseController
{
    #添加模板消息
    public function actionSetTel(){
        global $_W, $_GPC;
        $_GPC=$_GPC['__input'];
        $shen= $_GPC['templet_shenhe'];
        $ren= $_GPC['templet_renzheng'];
        $txf= $_GPC['templet_TXfail'];
        $txsu= $_GPC['templet_TXsuccess'];
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $a= $sysparamsMd->editOne($uniacid, 'templet_shenhe', $shen);
        $b= $sysparamsMd->editOne($uniacid, 'templet_renzheng', $ren);
        $c= $sysparamsMd->editOne($uniacid, 'templet_TXfail', $txf);
        $d= $sysparamsMd->editOne($uniacid, 'templet_TXsuccess', $txsu);
        if ($a==true && $b==true && $c==true && $d==true) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '设置成功',
                ]
            ];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '设置失败',
                ]
            ];
        }
    }

    #展示模板的id
    public function actionGetTel(){
        $sysMd = new SysParams();
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $where=[
            'and',
            ['like','name','templet_'],
            ['=','uniacid',$uniacid],
            ['=','is_del','0']
        ];
        $list=$sysMd->fetchListAll($where);
        $shen=$sysMd->fetchOneVar($uniacid,'templet_shenhe');
        $ren=$sysMd->fetchOneVar($uniacid,'templet_renzheng');
        $txf=$sysMd->fetchOneVar($uniacid,'templet_TXfail');
        $txs=$sysMd->fetchOneVar($uniacid,'templet_TXsuccess');
        return [
            'templet_shenhe'=>$shen,
            'templet_renzheng'=>$ren,
            'templet_TXfail'=>$txf,
            'templet_TXsuccess'=>$txs,
        ];
    }
    # 后台设置图标和小程序名字
    public function actionSetlogo()
    {
        $sysMd = new SysParams();
        global $_W, $_GPC;

        $id = $_GPC['logo'];
        $title = $_GPC['title'];
        $uniacid = $_W['uniacid'];
        if (!empty($id) && !empty($title)) {
            $logos = $sysMd->editOne($uniacid, 'logo', $id);
            $titles = $sysMd->editOne($uniacid, 'title', $title);
            if ($logos == true and $titles == true) {
                return [
                    'status' => [
                        'state' => 'success',
                        'msg' => '上传成功',
                    ]
                ];
            } else {
                return [
                    'status' => [
                        'state' => 'error',
                        'msg' => '上传失败',
                    ]
                ];
            }
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => 'logo和标题不能为空',
                ]
            ];
        }

    }

    # 获取后台图标和向小程序名字
    public function actionGettitles()
    {
        global $_W;
        $uploadMd = new Upload();
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $logo = $info['logo'] = $sysparamsMd->fetchOne($uniacid, 'logo')['var'];
        $title = $info['title'] = $sysparamsMd->fetchOne($uniacid, 'title')['var'];
        $img = $uploadMd->fetchOne(['id' => $logo]);
        $logos = $img['path'];
        return [
            'logoid' => $logo,
            'logo' => 'https://' . $_SERVER['HTTP_HOST'] . $logos,
            'title' => $title
        ];

    }

    #上传用户协议
    public function actionSetxieyi()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC['__input'];
        $sysparamsMd = new SysParams();
        if ($sysparamsMd->editOne($uniacid, 'agreement', $request_data['xieyi'])) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '上传成功',
                ]
            ];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '上传失败',
                ]
            ];
        }
    }

    #展示用户协议
    public function actionShowxieyi()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $info = $sysparamsMd->fetchOne($uniacid, 'agreement');
        return [
            'xieyi' => $info['var']
        ];

    }

    #删除用户协议
    public function actionDelxieyi()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $id = $request->get('id');
        $sysparamsMd = new SysParams();
        $info = $sysparamsMd->fetchOne($uniacid, 'agreement');
        $ids = explode(',', $info['var']);

        foreach ($ids as $key => $value) {
            if ($value === $id)
                unset($ids[$key]);
        }
        $var = implode(',', $ids);
        $del = $sysparamsMd->editOne($uniacid, 'agreement', $var);
        if ($del == true) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '删除成功',
                ]
            ];
        }
    }

    #设置客服
    public function actionSetservice()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC['__input'];
        $sysparamsMd = new SysParams();
        $name = $sysparamsMd->editOne($uniacid, 'service_name', $request_data['name']);
        $qq = $sysparamsMd->editOne($uniacid, 'service_qq', $request_data['qq']);
        $phone = $sysparamsMd->editOne($uniacid, 'service_phone', $request_data['phone']);
        if ($name == true and $qq == true and $phone == true) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '上传成功',
                ]
            ];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '上传失败',
                ]
            ];
        }
    }

    #展示客服参数
    public function actionShowservice()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $info['name'] = $sysparamsMd->fetchOne($uniacid, 'service_name')['var'];
        $info['qq'] = $sysparamsMd->fetchOne($uniacid, 'service_qq')['var'];
        $info['phone'] = $sysparamsMd->fetchOne($uniacid, 'service_phone')['var'];
        return $info;
    }

    //设置论波图和自定义字段
    public function actionBanner()
    {
        $sysMd = new SysParams();
        global $_W, $_GPC;
        $values = $_GPC['__input'];
        $id = $values['img'];
        $money = $values['money'];
        $uniacid = $_W['uniacid'];
        if ($sysMd->editOne($uniacid, 'index_banner', $id) == true) {
            $sysMd->editOne($uniacid, 'dic_money', $money);
            if ($values['san'] !== null) {
                foreach ($values['san'] as $key => $var) {
                    $san = new San();
                    $data = (array)$var;
                    $san->editOne($data);
                }
            }
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '添加成功'
                ]];
        }
    }

    //展示首页论波图 中间广告 和自己定义的佣金
    public function actionIndex()
    {
        global $_W;
        $upload = new Upload();
        $sys = new SysParams();
        $sanMd = new San();
        $uniacid = $_W['uniacid'];

        $dic = $sys->fetchOne($uniacid, 'dic_money');
        $index = $sys->fetchOne($uniacid, 'index_banner');

        $path = $upload->info($index['var']);
        $where = [
            'is_del' => 0,
            'type' => 2,
            'uniacid' => $uniacid
        ];
        $list = $sanMd->fetchListAll($where, $limit = null, $offset = null, $order = 'id');//首页的那三块
        return [
            'path' => $path,
            'dic' => $dic['var'],
            'img' => $index['var'],
            'san' => $list
        ];
    }

    //首页最近业务
    public function actionIndextask()
    {
        $taskMd = new BkTask();
        $projectMd = new BkProject();
        global $_W;
        $uniacid = $_W['uniacid'];
        $request = Yii::$app->request;
        $size = $request->get('size');
        $page = $request->get('page');
        $where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
        ];
        #分页设置
        $page_size = $size == NULL ? '10' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $taskMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $taskMd->fetchListindex($where, $limit, $offset, $order = 'update_time DESC');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    #首页业务数量
    public function actionTaskcount()
    {
        $taskMd = new BkTask();
        global $_W;
        $uniacid = $_W['uniacid'];
        $dai_where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'state_check', 1],
        ];
        $dai = $taskMd->fetchCountNum($dai_where);

        $qia_where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'state_check', 2],
            ['=', 'is_stop', 0],
            ['=', 'state_progress', 1],
        ];
        $qia = $taskMd->fetchCountNum($qia_where);

        $fu_where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'state_check', 2],
            ['=', 'is_stop', 0],
            ['=', 'state_progress', 2],
        ];
        $fu = $taskMd->fetchCountNum($fu_where);

        $wan_where = [
            'and',
            ['=', 'is_del', 0],
            ['=', 'uniacid', $uniacid],
            ['=', 'state_check', 2],
            ['=', 'is_stop', 0],
            ['=', 'state_progress', 3],
        ];
        $wan = $taskMd->fetchCountNum($wan_where);

        return [
            'dai' => $dai,
            'qia' => $qia,
            'fu' => $fu,
            'wan' => $wan,
        ];
    }

    #设置短信接口参数alisms_keyid
    public function actionSetAlisms()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC['__input'];
        if ($request_data == null) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '设置失败',
                ]
            ];
        }
        $sysparamsMd = new SysParams();
        $keyid = $sysparamsMd->editOne($uniacid, 'alisms_keyid', $request_data['keyid']);
        $secret = $sysparamsMd->editOne($uniacid, 'alisms_secret', $request_data['secret']);
        $singname = $sysparamsMd->editOne($uniacid, 'alisms_singname', $request_data['singname']);//签名
        $templateid = $sysparamsMd->editOne($uniacid, 'alisms_templatecode', $request_data['templateid']);//模板id
        $code = $sysparamsMd->editOne($uniacid, 'alisms_code', $request_data['code']);//模板变量验证码
        if ($keyid == true and $secret == true and $singname == true and $templateid == true and $code == true) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '设置成功',
                ]
            ];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '设置失败',
                ]
            ];
        }
    }

    #展示短信接口参数alisms_keyid
    public function actionGetAlisms()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $keyid = $sysparamsMd->fetchOneVar($uniacid, 'alisms_keyid');
        $secret = $sysparamsMd->fetchOneVar($uniacid, 'alisms_secret');
        $singname = $sysparamsMd->fetchOneVar($uniacid, 'alisms_singname');//签名
        $templateid = $sysparamsMd->fetchOneVar($uniacid, 'alisms_templatecode');//模板id
        $code = $sysparamsMd->fetchOneVar($uniacid, 'alisms_code');//模板变量验证码
        return [
            'keyid' => $keyid,
            'secret' => $secret,
            'singname' => $singname,
            'code' => $code,
            'templateid' => $templateid,
        ];

    }

    #支付宝自动打款接口参数设置
    public function actionSetAlipay()
    {

        global $_GPC, $_W;
        $payMd = new Pay();
        $data = $_GPC['__input'];
        if ($data == null) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '没有传参数'
                ]];
        }
        if ($payMd->editalipay($data)) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '设置成功'
                ]];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '设置失败'
                ]];
        }

    }

    #展示支付宝打款接口参数
    public function actionGetAlipay()
    {
        $sysparamMd = new SysParams();
        global $_W;
        $uniacid = $_W['uniacid'];
        $appid = $sysparamMd->fetchOneVar($uniacid, 'alipay_appid');//appid
        $rsaPrivateKey = $sysparamMd->fetchOneVar($uniacid, 'alipay_rsaPrivateKey');//私钥匙
        $rsaPrivateKey = str_replace("\n", '', $rsaPrivateKey);
        $alipayrsaPublicKey = $sysparamMd->fetchOneVar($uniacid, 'alipay_alipayrsaPublicKey');//秘密钥匙
        $alipayrsaPublicKey = str_replace("\n", '', $alipayrsaPublicKey);
        $sra = $sysparamMd->fetchOneVar($uniacid, 'alipay_signType');//SRA
        $showname = $sysparamMd->fetchOneVar($uniacid, 'alipay_showname');//SRA
        $remark = $sysparamMd->fetchOneVar($uniacid, 'alipay_remark');//转账备注

        return [
            'appid' => $appid,
            'private' => $rsaPrivateKey,
            'public' => $alipayrsaPublicKey,
            'singtype' => $sra,
            'showname' => $showname,
            'remark' => $remark,
        ];
    }

    #支付宝打款
    public function actionPay()
    {
        global $_W;
        $FormidMd=new Formid();
        $TelMd=new Template();
        $payMd = new Pay();
        $getmoneylogMd=new GetmoneyLog();
        $sysMd=new SysParams();
        $remark=$sysMd->fetchOneVar($_W['uniacid'],'alipay_remark');
        $request = Yii::$app->request;
        $id = $request->get('id');

        $pay = $payMd->alipay($id);

        #
        $loginfo=$getmoneylogMd->one($id);
        $zhanghaoinfo=Getmoney::find()->where(['id'=>$loginfo['getmoney_id']])->asArray()->one();

        #模板消息
        $time=strtotime('-7 days');
        $where_form=[
            'and',
            ['=','member_id',$loginfo['member_id']],
            ['>','create_time',$time],
            ['=','is_del','0'],
        ];
        $memberinfo=Member::find()->where(['id'=>$loginfo['member_id']])->asArray()->one();
        $list_all=$FormidMd->fetchListAll($where_form, $limit = null, $offset = null, $order = ['create_time'=>SORT_ASC]);
        if ($pay == true) {
        $TelMd->TXsuccess($memberinfo['openid'],$list_all[0]['formid'],$zhanghaoinfo['truename'],'支付宝',$zhanghaoinfo['alipay'],$loginfo['money'],$loginfo['money'],$remark);
          $FormidMd->delOne(['formid'=>$list_all[0]['formid']]);
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '成功',
                ]
            ];
        } else if ($pay === '0') {
            $TelMd->TXfail($memberinfo['openid'],$list_all[0]['formid'],$loginfo['number'],$zhanghaoinfo['truename'],$zhanghaoinfo['alipay'],$loginfo['money'],'');
            $FormidMd->delOne(['formid'=>$list_all[0]['formid']]);
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '打款参数缺失',
                ]
            ];
        } else {
            $TelMd->TXfail($memberinfo['openid'],$list_all[0]['formid'],$loginfo['number'],$zhanghaoinfo['truename'],$zhanghaoinfo['alipay'],$loginfo['money'],'');
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '失败',
                ]
            ];
        }
    }

    #设置小程序apppid 和secret
    public function actionSet()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $request_data = $_GPC['__input'];
        if ($request_data == null) {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '设置失败',
                ]
            ];
        }
        $sysparamsMd = new SysParams();
        $appid = $sysparamsMd->editOne($uniacid, 'appid', $request_data['appid']);
        $secret = $sysparamsMd->editOne($uniacid, 'secret', $request_data['secret']);
        if ($appid == true and $secret == true) {
            return [
                'status' => [
                    'state' => 'success',
                    'msg' => '设置成功',
                ]
            ];
        } else {
            return [
                'status' => [
                    'state' => 'error',
                    'msg' => '设置失败',
                ]
            ];
        }
    }

    #展示小程序appid和secret
    public function actionGet()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $keyid = $sysparamsMd->fetchOneVar($uniacid, 'appid');
        $secret = $sysparamsMd->fetchOneVar($uniacid, 'secret');
        return [
            'appid' => $keyid,
            'secret' => $secret,
        ];
    }

}