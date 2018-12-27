<?php
/**
 * Created by PhpStorm.
 * User: wsl
 * Date: 2018/12/3
 * Time: 15:31
 */

namespace common\models;


class Banner extends Base
{
    // 数据表
    public static function tableName()
    {
        return "{{%banner}}";
    }

    //获取一条
    public function one($where = array())
    {
        return self::find()
            ->where($where)
            ->asArray()
            ->one();
    }

    #获取详情
    public function fetchDetail($where)
    {
        $uploadMd = new Upload();
        $docMd = self::findOne($where);
        $info = $docMd == NULL ? NULL : $docMd->toArray();
        $imgs = explode(',', $info['img']);
        foreach ($imgs as $imgsid) {
            $where = [
                'id' => $imgsid
            ];
            $url = $uploadMd->fetchOne($where);
            $imgurl[] = $url['img_path'];
        }
        $info['imgurl'] = $imgurl;
        return $info;
    }

    //获取列表
    public function fetchListAll($where = array(), $limit = null, $offset = null, $order = null)
    {
        $list = self::find()
            ->filterWhere($where)
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order)
            ->asArray()
            ->all();
        return $list;
    }


    #获取信息
    public function fetchOne($uniacid, $name)
    {
        $info_where = [
            'uniacid' => $uniacid,
            'name' => $name,
            'is_del' => '0'
        ];
        $info = self::findOne($info_where);
        if ($info != NULL) {
            return $info->toArray();
        } else {
            if (!$this->formatOne($uniacid, $name)) {
                return false;
            }
            $info_where = [
                'uniacid' => $uniacid,
                'name' => $name,
                'is_del' => '0'
            ];
            return self::findOne($info_where)->toArray();
        }
    }


}