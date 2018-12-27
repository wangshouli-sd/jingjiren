<?php
namespace common\tools\alitools\Sms;

ini_set("display_errors", "on");

require_once __DIR__ . '/api_sdk/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use common\models\SysParams;

// 加载区域结点配置
Config::load();

class AliSms
{

    #各种参数设为属性
    public $accessKeyId;
    public $accessKeySecret;

    public $region = "cn-hangzhou";
    public $endPointName = "cn-hangzhou";

    public $acsClient=NULL;

    #__construct初始化属性
    public function __construct($accessKeyId,$accessKeySecret)
    {
        $this->accessKeyId=$accessKeyId;
        $this->accessKeySecret=$accessKeySecret;
    }
    #取得AcsClient
    public function getAcsClient() {
        //产品名称:云通信短信服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = $this->accessKeyId; // AccessKeyId
        $accessKeySecret = $this->accessKeySecret; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if($this->acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            $this->acsClient = new DefaultAcsClient($profile);
        }
        return $this->acsClient;
    }

    #短信发送
    public function sendMsg($phoneNumber,$signName,$templateCode,array $templateContent)
    {
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phoneNumber);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($signName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($templateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode($templateContent, JSON_UNESCAPED_UNICODE));

        // 可选，设置流水号
//        $request->setOutId("123456");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
//        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        try
        {
            $acsResponse = $this->getAcsClient()->getAcsResponse($request);
        }
        catch (Exception $e)
        {
            return false;
        }

        if($acsResponse->Code!="OK")
        {
            return false;
        }
        return $acsResponse;
    }
}

