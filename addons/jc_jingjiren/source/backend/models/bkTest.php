<?php
namespace backend\models;

use common\models\Test;
class bkTest extends Test
{
    public function getList()
    {
        $origin=parent::getList();
        return $origin+1;
    }
}