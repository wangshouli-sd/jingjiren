<?php
/**
 * Created by PhpStorm.
 * User: jiechenhulian
 * Date: 2018/8/24
 * Time: 17:41
 */

namespace frontend\models;

class FrMember extends \common\models\Member
{
    #更新一条
    public function editOne($where, $params)
    {
        $docMd = self::findOne($where);
        if ($docMd == NULL) {
            return false;
        }
        $params['create_time'] = time();
        foreach ($params as $key => $var) {
            $docMd->scenario = 'update';
            $docMd->$key = $var;
        }
        return $docMd->save();
    }

//    上传身份证照片
    public function upload()
    {
        return true;
    }

    //获取一条  按照条件 用户信息
    public function One($where = array())
    {
        $one = $this::find()
            ->filterWhere($where)
            ->asArray()
            ->one();
        return $one;
    }

    // 判断该用户否收藏该项目
    public function like($openid = null, $project_id = null)
    { #'id' => $id,, $id = null
        $where = [

            'openid' => $openid,
        ];
        $list = $this->One($where);
        if ($list['project_id'] == null or $list['project_id'] == '') {
            $project_ids = [];
        } else {
            $project_ids = explode(',', $list['project_id']);
        }
        if (in_array($project_id, $project_ids)) {
            return [
                'state' => 1,
                'project_ids' => $project_ids
            ]; // 已收藏
        } else if (!in_array($project_id, $project_ids)) {
            return [
                'state' => 0,
                'project_ids' => $project_ids
            ]; // 未收藏
        }
    }

    //    我的收藏 若收藏，则取消收藏    若未收藏，则收藏该项目
    public function likes($openid, $project_id)
    {
        $state = $this->like($openid, $project_id);
        //执行取消收藏
        if ($state['state'] == 1) {
            $likesMd = $state['project_id'];
            foreach ($likesMd as $key => $value) {
                if ($project_id == $value) unset($likesMd[$key]);
            }
            return $likesMd;
        }
        // 执行收藏
        if ($state['state'] == 0) {
            $likesMd = $state['project_id'];

            $likes = array_push($likesMd, $project_id);
            return $likes;
        }
    }

    // 获取客户的首字母
    public function getFirstChar($str)
    {
        if (empty($str)) {
            return '';
        }

        $fir = $fchar = ord($str[0]);
        if ($fchar >= ord('A') && $fchar <= ord('z')) {
            return strtoupper($str[0]);
        }

        $s1 = @iconv('UTF-8', 'gb2312', $str);
        $s2 = @iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        if (!isset($s[0]) || !isset($s[1])) {
            return '';
        }

        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;

        if (is_numeric($str)) {
            return $str;
        }

        if (($asc >= -20319 && $asc <= -20284) || $fir == 'A') {
            return 'A';
        }
        if (($asc >= -20283 && $asc <= -19776) || $fir == 'B') {
            return 'B';
        }
        if (($asc >= -19775 && $asc <= -19219) || $fir == 'C') {
            return 'C';
        }
        if (($asc >= -19218 && $asc <= -18711) || $fir == 'D') {
            return 'D';
        }
        if (($asc >= -18710 && $asc <= -18527) || $fir == 'E') {
            return 'E';
        }
        if (($asc >= -18526 && $asc <= -18240) || $fir == 'F') {
            return 'F';
        }
        if (($asc >= -18239 && $asc <= -17923) || $fir == 'G') {
            return 'G';
        }
        if (($asc >= -17922 && $asc <= -17418) || $fir == 'H') {
            return 'H';
        }
        if (($asc >= -17417 && $asc <= -16475) || $fir == 'J') {
            return 'J';
        }
        if (($asc >= -16474 && $asc <= -16213) || $fir == 'K') {
            return 'K';
        }
        if (($asc >= -16212 && $asc <= -15641) || $fir == 'L') {
            return 'L';
        }
        if (($asc >= -15640 && $asc <= -15166) || $fir == 'M') {
            return 'M';
        }
        if (($asc >= -15165 && $asc <= -14923) || $fir == 'N') {
            return 'N';
        }
        if (($asc >= -14922 && $asc <= -14915) || $fir == 'O') {
            return 'O';
        }
        if (($asc >= -14914 && $asc <= -14631) || $fir == 'P') {
            return 'P';
        }
        if (($asc >= -14630 && $asc <= -14150) || $fir == 'Q') {
            return 'Q';
        }
        if (($asc >= -14149 && $asc <= -14091) || $fir == 'R') {
            return 'R';
        }
        if (($asc >= -14090 && $asc <= -13319) || $fir == 'S') {
            return 'S';
        }
        if (($asc >= -13318 && $asc <= -12839) || $fir == 'T') {
            return 'T';
        }
        if (($asc >= -12838 && $asc <= -12557) || $fir == 'W') {
            return 'W';
        }
        if (($asc >= -12556 && $asc <= -11848) || $fir == 'X') {
            return 'X';
        }
        if (($asc >= -11847 && $asc <= -11056) || $fir == 'Y') {
            return 'Y';
        }
        if (($asc >= -11055 && $asc <= -10247) || $fir == 'Z') {
            return 'Z';
        }

        return '';
    }


}
