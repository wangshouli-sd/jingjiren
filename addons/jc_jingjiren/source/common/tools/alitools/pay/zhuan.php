<?php

###############################################################################################################33
require __DIR__.'/aop/AopClient.php';
require __DIR__.'/aop/request/AlipayFundTransToaccountTransferRequest.php';
try {
    $aop = new AopClient ();
//$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do'; ##实际环境
    $aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do'; ##沙箱环境
    $aop->appId = '2016091900546113';
    $aop->rsaPrivateKey = $rsaPrivateKey;
    $aop->alipayrsaPublicKey = $alipayrsaPublicKey;
    $aop->apiVersion = '1.0';
    $aop->signType = 'RSA2';
    $aop->postCharset = 'utf-8';
    $aop->format = 'json';
    $request = new AlipayFundTransToaccountTransferRequest ();
    $out_biz_no = md5(intval(time()) + rand(0, 100000));
    $bizContent = [
        'out_biz_no' => $out_biz_no,                           #3142321423432
        'payee_type' => 'ALIPAY_LOGONID',                             #ALIPAY_LOGONID
        'payee_account' => 'jyayhp4458@sandbox.com',                  #abc@sina.com
        'amount' => '70',                                            #12.23
        'payer_show_name' => '转账测试',                               #上海交通卡退款
        'payee_real_name' => '沙箱环境',                               #张三
        'remark' => '转账备注测试',                                     #转账备注
    ];
    $request->setBizContent(json_encode($bizContent));

    $result = $aop->execute($request);


    $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
    $resultCode = $result->$responseNode->code;
}
catch (Exception $e)
{
    exit('失败');
}
if(!empty($resultCode)&&$resultCode == 10000){
    echo "成功"."\n";
    echo $out_biz_no;
} else {
    echo "失败";
}
