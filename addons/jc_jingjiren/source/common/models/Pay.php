<?php
/**
 * Created by PhpStorm.
 * User: zmy
 * Date: 18-10-24
 * Time: 下午3:04
 */

namespace common\models;

require  __DIR__.'/../../common/tools/alitools/pay/AopClient.php';
require  __DIR__.'/../../common/tools/alitools/pay/request/AlipayFundTransToaccountTransferRequest.php';
require __DIR__.'/../../common/tools/alitools/pay/request/AlipayFundTransOrderQueryRequest.php';
class Pay extends Base
{
    public $rsaPrivateKey = NULL;
    public $alipayrsaPublicKey = NULL;

    #修改支付宝打款参数
    public function editalipay($data){
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        if ($uniacid == null) {
            return false;
        }
        $sysparamMd = new SysParams();
        $sysparamMd->editOne($uniacid,'alipay_appid',$data['appid']);//appid
        $sysparamMd->editOne($uniacid,'alipay_rsaPrivateKey',$data['private']);//应用私钥
        $sysparamMd->editOne($uniacid,'alipay_alipayrsaPublicKey',$data['public']);//支付宝公钥
        $sysparamMd->editOne($uniacid,'alipay_signType',$data['singtype']);//签名方式RSA或者RSA2
        $sysparamMd->editOne($uniacid,'alipay_showname',$data['showname']);//付款放名字
        $sysparamMd->editOne($uniacid,'alipay_remark',$data['remark']);//打款备注
        return true;
    }

    #打款接口
    public function alipay($id)
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $sysparamMd = new SysParams();
        $getmoney=new Getmoney();
        $getmoneylogMd=new GetmoneyLog();

        #用户支付宝号，打款钱数
        $loginfo=$getmoneylogMd->one($id);
        $getmoneyinfo=$getmoney->fecthOne(['id'=>$loginfo['getmoney_id']]);
        $money=$loginfo['money'];//打款钱数
        $truename=$getmoneyinfo['truename'];//真实姓名
        $useralipay=$getmoneyinfo['alipay'];

        #后台设置的参数获取
        $appid=$sysparamMd->fetchOneVar($uniacid,'alipay_appid');//appid
        $rsaPrivateKey=$sysparamMd->fetchOneVar($uniacid,'alipay_rsaPrivateKey');//私钥
        $rsaPrivateKey=str_replace("\n",'',$rsaPrivateKey);//去私钥空格和换行
        $alipayrsaPublicKey=$sysparamMd->fetchOneVar($uniacid,'alipay_alipayrsaPublicKey');//秘钥
        $alipayrsaPublicKey=str_replace("\n",'',$alipayrsaPublicKey);//去秘钥空格和换行
        $sra=$sysparamMd->fetchOneVar($uniacid,'alipay_signType');//SRA
        $showname=$sysparamMd->fetchOneVar($uniacid,'alipay_showname');//显示的名字
        $remark=$sysparamMd->fetchOneVar($uniacid,'alipay_remark');//转账备注

        if ($appid==null or $rsaPrivateKey==null or $sra==null){
            return '0';
        }
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do'; ##实际环境
        //$aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';##沙箱环境
        $aop->appId = $appid;
        $aop->rsaPrivateKey = $rsaPrivateKey;
        $aop->alipayrsaPublicKey = $alipayrsaPublicKey;
        $aop->apiVersion = '1.0';
        $aop->signType = $sra;
        $aop->postCharset = 'UTF-8';
        $aop->format = 'json';
        $request = new \AlipayFundTransToaccountTransferRequest();
        $out_biz_no = md5(intval(time()) + rand(0, 100000));//订单号
        // payee_type       收款方账户类型。可取值：
        //1、ALIPAY_USERID：支付宝账号对应的支付宝唯一用户号。以2088开头的16位纯数字组成。
        //2、ALIPAY_LOGONID：支付宝登录号，支持邮箱和手机号格式。
        $bizContent = [
            'out_biz_no' => $out_biz_no,
            'payee_type' => 'ALIPAY_LOGONID',                             #ALIPAY_LOGONID
            'payee_account' => $useralipay,
            'amount' => $money,
            'payer_show_name' => $showname,
            'payee_real_name' => $truename,
            'remark' => $remark,
        ];
        $request->setBizContent(json_encode($bizContent));
        $result = $aop->execute($request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if (!empty($resultCode) && $resultCode == 10000) {
            $getmoneylogMd->state($id,['state'=>3]);
            return true;
        } else {
            return false;
        }

    }



}