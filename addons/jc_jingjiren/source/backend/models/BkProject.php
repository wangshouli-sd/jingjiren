<?php

namespace backend\models;

use common\models\Base;
use common\models\Process;
use common\models\Project;

class BkProject extends Project
{
    public function getids($where)
    {
        $list = $this->fetchListAll($where);
        foreach ($list as $key => $value) {
            $ids[] = $value['id'];
        }
        return $ids;
    }

    //新增 项目和项目流程
    public function add($values = array())
    {
        $processMd = new Process();
        $this->scenario = 'create';
        $this->attributes = $values;
        $this->save();
        $id = $this->id;
        $process = (array)$values['process'];
        $list = $processMd->add($process, $id, $values['uniacid']);
        return $list;

    }

}