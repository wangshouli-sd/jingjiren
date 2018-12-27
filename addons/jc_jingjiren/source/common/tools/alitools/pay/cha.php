<?php

###############################################################################################################33
require __DIR__.'/aop/AopClient.php';
require __DIR__.'/aop/request/AlipayFundTransToaccountTransferRequest.php';
require __DIR__.'/aop/request/AlipayFundTransOrderQueryRequest.php';

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

$request = new AlipayFundTransOrderQueryRequest ();
$num=$_GET['num'];
$bizContent=[
    'out_biz_no'=>$num,
//    'order_id'=>'',
];
$request->setBizContent(json_encode($bizContent));
$result = $aop->execute ( $request);

$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
$resultCode = $result->$responseNode->code;
if(!empty($resultCode)&&$resultCode == 10000){
    #订单已支付成功
echo "成功";
} else {
    #订单未支付成功
echo "失败";
}
