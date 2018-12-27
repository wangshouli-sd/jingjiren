<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2018/8/30
 * Time: 10:17
 */

namespace frontend\models;

use common\models\Table;

class FrTable extends Table
{
    public static function tableName()
    {
        return "{{%project_table}}";
    }


    //获取一条
    public function One($id=null){
    return self::findOne($id);
}
    //获取一条 数组类型的
    public function fetchOne($where=array()){
        return self::find()
            ->where($where)
            ->asArray()
            ->one();
    }

}