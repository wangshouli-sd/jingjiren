<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/9/11
 * Time: 16:57
 */

namespace frontend\controllers;

use backend\models\BkTask;
use common\models\Bank;
use common\models\Formid;
use common\models\GetmoneyLog;
use common\models\SysParams;
use common\models\Upload;
use common\models\WalletLog;
use frontend\models\FrCode;
use frontend\models\FrGetmoney;
use frontend\models\FrMember;
use yii;

class FrGetmoneyController extends BaseController
{
    #确认提现页面，个人的账号信息等获取展示
    public function actionInfo()
    {
        global $_W;
        $moneyMd = new FrGetmoney();
        $memberMd = new FrMember();
        $sys=new SysParams();
        $bankMd=new Bank();
        $request = yii::$app->request;
        $uniacid = $_W['uniacid'];
        $openid = $request->get('openid');
        #提现方式信息
        $alipay=$sys->fetchOneVar($uniacid,'getmoney_alipay')==null?'0':$sys->fetchOneVar($uniacid,'getmoney_alipay');
        $bank=$sys->fetchOneVar($uniacid,'getmoney_bank')==null?'0':$sys->fetchOneVar($uniacid,'getmoney_bank');
        $wechat=$sys->fetchOneVar($uniacid,'getmoney_wechat')==null?'0':$sys->fetchOneVar($uniacid,'getmoney_wechat');
        if ($bank=='1'){
            $bankname=$bankMd->fetchListAll(['is_del' => 0,'is_open'=>1]);
        }

        $id = $memberMd->memberid($openid);
        $where = [
            'member_id' => $id,
            'uniacid' => $uniacid
        ];
        $info=$moneyMd->fecthOne($where);
        return [
            'info'=>$info,
            'zhifubao'=>$alipay,
            'wechat'=>$wechat,
            'bank'=>$bank,
            'bankname'=>$bankname,

        ];
    }

    #提交提现申请 type：1是支付宝，2是银行卡，3是微信钱包
    public function actionIndex()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $getmoneyMd = new FrGetmoney();
        $request_data=$_GPC['__input'];

        $type = isset($request_data['type']) ? $request_data['type'] : '';
        $truename = isset($request_data['truename']) ? $request_data['truename'] : '';#真实姓名

        $openid = isset($request_data['openid']) ? $request_data['openid'] : '';
        $member = new FrMember();
        $member_id = $member->memberid($openid);

        if ($request_data['formid']!=null) {
            $formid = $request_data['formid'];
            $formidMd = new Formid();
            $formidMd->addOne(['member_id' => $member_id, 'formid' => $formid]);
        }

        $alipay = isset($request_data['alipay']) ? $request_data['alipay'] : '';#支付宝账号
        $bank_card = isset($request_data['bank_card']) ? $request_data['bank_card'] : '';#银行卡号
        $bank = isset($request_data['bank']) ? $request_data['bank'] : '';#银行id
        $money = isset($request_data['money']) ? $request_data['money'] : '';#提现钱数
        $data = [
            'uniacid' => $uniacid,
            'member_id' => $member_id,
            'truename' => $truename,
            'alipay' => $alipay,
            'bank_card' => $bank_card,
            'bank' => $bank,
        ];
        return $getmoneyMd->alipay($member_id, $data, $money, $type);

    }

    #提现进度页
    public function actionState()
    {
        global  $_W;
        $moneylogMd = new GetmoneyLog();
        $memberMd = new FrMember();
        $request = yii::$app->request;
        $uniacid = $_W['uniacid'];
        $openid = $request->get('openid');
        $id = $memberMd->memberid($openid);
        if ($id==null){
            return '0';
        }
        $where = [
            'member_id' => $id,
            'uniacid' => $uniacid,
            'state'=>['1','2']
        ];
         $info=$moneylogMd->fetchListAll($where, $limit = null, $offset = null, $order = ['create_time'=>SORT_DESC]);
         if ($info==null){
             return '0';
         }
        $info['0']['check_time']=date('Y-m-d H:i:s', $info['0']['update_time']);
        return $info['0'];
    }

    #个人提现列表
    public function actionMylist()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $log = new GetmoneyLog();
        $memberMd=new FrMember();
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $openid = $request->get('openid');
        $member_id =$memberMd->memberid($openid)==null?'0':$memberMd->memberid($openid);

        $number = $request->get('number');#提现编号
        $type = $request->get('type');#提现方式id

        $where = [
            'and',
            ['=', 'uniacid', $uniacid],
            ['=', 'type', $type],
            ['=', 'is_del', 0],
            ['like', 'number', $number],
            ['=', 'member_id', $member_id],

        ];
        #分页设置
        $page_size = $size == NULL ? '2' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $log->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];
        $list = $log->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    #个人账户收支明细  获得的佣金
    public function actionDetailMoney()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $walletMd = new WalletLog();
        $taskMd = new BkTask();
        $memberMd=new FrMember();
        $request = Yii::$app->request;
        $page = $request->get('page');
        $size = $request->get('size');
        $openid = $request->get('openid');
        $member_id =$memberMd->memberid($openid)==null?'0':$memberMd->memberid($openid);

        $task_number = $request->get('number');#订单编号
        $task_where = [
            'and',
            ['like', 'task_number', $task_number],
            ['=', 'is_del', 0]
        ];
        $task = $taskMd->fetchListAll($task_where);
        foreach ($task as $key => $value) {
            $task_ids[] = $value['id'];
        }
        $types = $request->get('type');#收入类型
        $type=($types=='grand')?'grand_son':$types;
        $where = [
            'uniacid' => $uniacid,
            'member_id' => $member_id,
            'type' => 1,
            'is_del' => 0,
            'name' => $type,
            'task_id' => $task_ids
        ];
        #分页设置
        $page_size = $size == NULL ? '20' : $size;
        $page_index = $page == NULL ? 1 : $page;
        $data_total = $walletMd->fetchCountNum($where);

        $pageMd = new \common\tools\PageJson($data_total, $base_url = '', $page_index, $page_size);
        $page_limit = $pageMd->getLimit();
        $limit = $page_limit[1];
        $offset = $page_limit[0];

        $list = $walletMd->fetchListAll($where, $limit, $offset, $order = 'create_time DESC');
        $page = $pageMd->getJsonArray();
        return [
            'list' => $list,
            'page' => $page
        ];
    }

    #分销海报参数
    public function actionPoster()
    {
        global $_W;
        $uniacid = $_W['uniacid'];
        $sysparamsMd = new SysParams();
        $uploadMd = new Upload();
        $memberMd = new FrMember();
        $frcodeMd=new FrCode();
        $request = Yii::$app->request;
        $openid = $request->get('openid');
        $member = $memberMd->One(['openid' => $openid])==null?'0':$memberMd->One(['openid' => $openid]);
        $postername = $sysparamsMd->fetchOne($uniacid, 'poster_name');
        $postertap = $sysparamsMd->fetchOne($uniacid, 'poster_tap');
        $posterbackimg = $sysparamsMd->fetchOne($uniacid, 'poster_backimg');
        $backpath = $uploadMd->fetchOne(['id' => $posterbackimg['var']]);

        $onlyid=$member['onlyid'];
        #把头像保存到本地
        $temp_path = __DIR__ . '/../../../../../data';
        $save_dir = 'jc_jingjiren/touxiang/'.$onlyid.'jpg';
        #创建文件夹
        $path = explode('/', $save_dir);
        foreach ($path as $temp) {
            $temp_path = $temp_path . '/' . $temp;
            #不存在
            if (!is_dir($temp_path)) {
             if ($frcodeMd->touxiang($member['image'],$member['onlyid'])==1){
                 $img_url='/data/jc_jingjiren/touxiang/'.$onlyid.'.jpg';
             }
            }else {
                $img_url='/data/jc_jingjiren/touxiang/'.$onlyid.'.jpg';
                #删除以前的头像，替换
                file_exists($img_url);
                $frcodeMd->touxiang($member['image'],$member['onlyid']);
                $img_url='/data/jc_jingjiren/touxiang/'.$onlyid.'.jpg';
            }
        }
        return [
            'name' => $postername['var'],
            'tap' => $postertap['var'],
            'backimg' => $posterbackimg['var'],
            'files' => $_W['siteroot'].$backpath['path'],
            'onlyid' => $member['onlyid'],
            'img'=>$_W['siteroot'].$img_url,
            'username'=>$member['nick_name']
        ];
    }
}
