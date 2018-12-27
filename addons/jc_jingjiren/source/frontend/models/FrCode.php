<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/10
 * Time: 11:05
 */

namespace frontend\models;


use common\models\Base;
use common\models\SysParams;
use common\models\Wxapp;

class FrCode extends Base
{
    //把请求发送到微信服务器换取
    public function httpRequest($url, $data = '', $method = 'GET')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data != '') {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
        }

        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    //二维码
    public function main($onlyid)
    {
        global $_W;

        $wxapp=new Wxapp();
        $row= $wxapp->getappinfo();
        $APPID =$row['key'];
        $APPSECRET =$row['secret'];

        $ACCESS_TOKEN=$this->GetToken($APPID,$APPSECRET);
        //1. 不限制数量生成小程序码
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $ACCESS_TOKEN;
        //2. 限制数量生成小程序码
        //$url = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$ACCESS_TOKEN;
        //3. 限制数量生成小程序的二维码
        //$url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$ACCESS_TOKEN;
        header('content-type:image/gif');
        $data = array();
        $data['scene'] = $onlyid;//自定义信息，可以填写诸如识别用户身份的字段，注意用中文时的情况
        $data['page'] = "pages/renwu/renwu-index/renwu-index";//扫描后对应的path
        //$data['page'] = "pages/webview/index";//美少女
        $data['width'] = 800;//自定义的尺寸
        $data['auto_color'] = false;//是否自定义颜色
        $color = array(
            "r" => "221",
            "g" => "223",
            "b" => "123",
        );
        $data['line_color'] = $color;//自定义的颜色值
        $data = json_encode($data);
        $da = $this->get_http_array($url, $data);
        return json_encode($da);//直接在浏览器显示或者存储到服务器等其他操作
    }

    public function get_http_array($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   //没有这个会自动输出，不用print_r()也会在后面多个1
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $out = json_decode($output);
        return $out;
    }

    //保存头像到本地
    function touxiang($url,$onlyid){
        $temp_path = __DIR__ . '/../../../../../data';
        $save_dir = 'jc_jingjiren/touxiang/';
        //创建文件夹
        $path = explode('/', $save_dir);
        foreach ($path as $temp) {
            $temp_path = $temp_path . '/' . $temp;
            #不存在
            if (!is_dir($temp_path)) {
                mkdir($temp_path);
            }
        }

        $img = file_get_contents($url);

        $file=$temp_path.$onlyid.'.jpg';
        file_put_contents($file,$img);
        return 1;
    }

    #获取access_token
    public function GetToken($APPID,$APPSECRET){
        //获取 access_token
        $access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $APPID . "&secret=" . $APPSECRET;
        $_SESSION['access_token'] = "";
        $_SESSION['expires_in'] = 0;

        $ACCESS_TOKEN = "";
        if (!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in']) && time() > $_SESSION['expires_in'])) {

            $json = $this->httpRequest($access_token);
            $json = json_decode($json, true);
            // var_dump($json);
            $_SESSION['access_token'] = $json['access_token'];
            $_SESSION['expires_in'] = time() + 7200;
            $ACCESS_TOKEN = $json["access_token"];
        } else {

            $ACCESS_TOKEN = $_SESSION["access_token"];
        }
        return $ACCESS_TOKEN;
    }

}