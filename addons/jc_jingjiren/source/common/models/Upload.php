<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

class Upload extends Base
{

    public static function tableName()
    {
        return "{{%upload}}";
    }

    #添加 单个
    public function uploadOne($file)
    {
        global $_W;
        $uniacid=$_W['uniacid'];
        if($file==NULL)
        {
            return false;
        }
        $dir_date = date('YmdH');
        $temp_path = __DIR__ . '/../../../../../data';
        $dir = 'jc_jingjiren/' . $dir_date;
        //创建文件夹
        $path = explode('/', $dir);
        foreach ($path as $temp) {
            $temp_path = $temp_path . '/' . $temp;
            #不存在
            if (!is_dir($temp_path)) {
                mkdir($temp_path);
            }
        }

        $origin_name=$file['name'];//timg.jpeg
        $origin_type=$file['type'];//image/jpeg
        $origin_tmp_name=$file['tmp_name'];//   /tmp/phpAL5bEK

        $origin_name=explode('.',$origin_name);

        $tuozhanming = $origin_name[count($origin_name)-1];
        unset($origin_name[count($origin_name)-1]);
        $basename=implode('.',$origin_name);
        $rend_name = md5(time() . rand(1, 999999) . rand(1, 999999));
        $file_name = $rend_name . '.' . $tuozhanming;
        $save_full_path = $temp_path . '/' . $file_name;

        #转移tmp文件
        if(!move_uploaded_file($origin_tmp_name,$save_full_path))
        {
            return false;
        }
        $this->uniacid = $uniacid;
        $this->name = $basename.'.'.$tuozhanming;
        $this->path = '/data/' . $dir . '/' . $file_name;
        $this->create_time = time();
        $this->update_time = time();
        $this->is_del ='0';
        if(!$this->save())
        {
            return false;
        }
        return $this->id;
    }
    #添加 单个
    public function uploadImg($file)
    {
        global $_W;
        $uniacid=$_W['uniacid'];
        if($file==NULL)
        {
            return false;
        }
        $dir_date = date('YmdH');
        $temp_path = __DIR__ . '/../../../../../data';
        $dir = 'jc_jingjiren/' . $dir_date;
        //创建文件夹
        $path = explode('/', $dir);
        foreach ($path as $temp) {
            $temp_path = $temp_path . '/' . $temp;
            #不存在
            if (!is_dir($temp_path)) {
                mkdir($temp_path);
            }
        }

        $origin_name=$file['name'];//timg.jpeg
        $origin_type=$file['type'];//image/jpeg
        $origin_tmp_name=$file['tmp_name'];//   /tmp/phpAL5bEK

        $origin_name=explode('.',$origin_name);

        $tuozhanming = $origin_name[count($origin_name)-1];
        unset($origin_name[count($origin_name)-1]);
        $basename=implode('.',$origin_name);
        $rend_name = md5(time() . rand(1, 999999) . rand(1, 999999));
        $file_name = $rend_name . '.' . $tuozhanming;
        $save_full_path = $temp_path . '/' . $file_name;

        #转移tmp文件
        if(!move_uploaded_file($origin_tmp_name,$save_full_path))
        {
            return false;
        }
        $this->uniacid = $uniacid;
        $this->name = $basename.'.'.$tuozhanming;
        $this->path = '/data/' . $dir . '/' . $file_name;
        $this->create_time = time();
        $this->update_time = time();
        $this->is_del ='0';
        if(!$this->save())
        {
            return false;
        }
        return ['id' => $this->id,'url' =>'https://'.$_SERVER['HTTP_HOST'].$this->path ];
    }
    #删除单个
    public function delOne($id)
    {
        $uploadMd=self::findOne($id);

        if($uploadMd==NULL)
        {
            return false;
        }

        $file_path= __DIR__ . '/../../../../..'.$uploadMd->path;
        $uploadMd->is_del='1';
        if(!$uploadMd->save())
        {
            return false;
        }

        #删除物理文件
        try
        {
            unlink($file_path);
        }
        catch (\Exception $e)
        {
        }

        return true;
    }


    #获取图片路径
   public function fetchOne($where = array())
   {
       $list= self::find()
           ->where($where)
           ->asArray()
           ->one();
       return $list;
   }

    #获取图片路径
    public function One($id=null)
    {
        $list= self::findone($id);
        return $list;
    }
    #根据ids获取路径列表
    public function getimg($ids){
        global $_W;
        $imgs_id=explode(',',$ids);
        $where=[
            'id'=>$imgs_id
        ];
        $list= self::find()
            ->where($where)
            ->asArray()
            ->all();
        foreach ($list as $key=>$value){

            if ($_W['script_name']=='/app/index.php'){
                $img_url[] = $_W['siteroot'].$value['path'];
            }else{
                $img_url[]=$value['path'];
            }
        }
        return $img_url;
    }

    #根据多个id获取图片信息
    public function info($ids){
        $imgs_id=explode(',',$ids);
        $where=[
            'id'=>$imgs_id
        ];
        $list= self::find()
            ->where($where)
            ->asArray()
            ->all();
        $i=0;
        foreach ($list as $key=>$value){
            global $_W;
            if ($_W['script_name']=='/app/index.php'){
            $list[$i]['path'] = $_W['siteroot'].$value['path'];}
            $img_url[$i][]=$value['path'];
            $i++;
        }
        return $list;
    }
}




