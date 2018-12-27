<?php
namespace backend\models;
class Page
{
    /*分页大小*/
    public $page_size;
    /*页数*/
    public $page_index;
    /*总数据*/
    public $data_total;
    /*总页数*/
    public $page_total;

    /*左右长度*/
    public $length=2;
    /*基本链接*/
    public $base_url;

    /*基本模板*/
    public $base_temp='{{url}}';
    /*上一页*/
    public $before='{{url}}';
    /*下一页*/
    public $next='{{url}}';
    /*当前页*/
    public $current='{{url}}';
    /*左页数*/
    public $left=array();
    /*右页数*/
    public $right=array();
    /*左省略*/
    public $left_point='';
    /*右省略*/
    public $right_point='';
    /*总外壳*/
    public $box='
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                {{content}}
                </ul>
            </div>
    ';


    /*初始化*/
    public function __construct($data_total,$base_url,$page_index=null,$page_size=null)
    {
        $this->data_total=$data_total;
        $this->base_url=$base_url;
        $this->page_index=is_null($page_index)?1:$page_index;
        $this->page_size=is_null($page_size)?2:$page_size;
        $this->create();
    }

    /*创建执行*/
    public function create()
    {
        $this->setPageTotal();
        $this->setBefore();
        $this->setNext();
        $this->setCurrent();
        $this->setLeft();
        $this->setRight();
        $this->setLeftPoint();
        $this->setRightPoint();
    }

    /*计算设置总页数*/
    public function setPageTotal()
    {
        $data_total=intval($this->data_total);
        $page_size=intval($this->page_size);
        $page_total=1;
        if($data_total < $page_size)
        {
            $page_total=1;
        }
        else
        {
            $page_total=intval($data_total/$page_size);
            $page_total=$data_total%$page_size>0?$page_total+1:$page_total;
        }
        $this->page_total=$page_total;
    }

    /*设置 上一页*/
    public function setBefore()
    {
        $page_total=intval($this->page_total);
        $page_index=$this->page_index;
        if(($page_index-1)>0)
        {
            $before_index=$page_index-1;
        }
        else
        {
            $before_index=1;
        }
        $url=$this->base_url.'page='.$before_index;
        $this->before=str_replace('{{page}}}','上一页',$this->before);
        $this->before=str_replace('{{url}}',$url,$this->before);
    }

    /*设置 下一页*/
    public function setNext()
    {
        $page_total=intval($this->page_total);
        $page_index=$this->page_index;
        if(($page_index+1)<$page_total)
        {
            $next_index=$page_index+1;
        }
        else
        {
            $next_index=$page_total;
        }
        $url=$this->base_url.'page='.$next_index;
        $this->next=str_replace('{{page}}}','下一页',$this->next);
        $this->next=str_replace('{{url}}',$url,$this->next);
    }

    /*设置 当前页*/
    public function setCurrent()
    {
        $page_index=$this->page_index;
        $url=$this->base_url.'page='.$page_index;
        $this->current=str_replace('{{page}}}',$page_index,$this->current);
        $this->current=str_replace('{{url}}',$url,$this->current);
    }

    /*设置 左页数*/
    public function setLeft()
    {
        $page_index=$this->page_index;
        $page_list=array();
        $length=intval($this->length);
        for($i=$page_index-$length;$i<$page_index;$i++)
        {
            if($i<=0)
            {
                continue;
            }
            $page_list[]=$i;
        }
        $list=array();
        foreach ($page_list as $temp)
        {
            $temp_url=$this->base_url.'page='.$temp;
            $temp_left=$this->base_temp;
            $temp_left=str_replace('{{page}}}',$temp,$temp_left);
            $temp_left=str_replace('{{url}}',$temp_url,$temp_left);
            $list[]=$temp_left;
        }
        $this->left=$list;
    }

    /*设置 右页数*/
    public function setRight()
    {
        $page_index=intval($this->page_index);
        $page_total=intval($this->page_total);
        $page_list=array();
        $length=intval($this->length);
        for($i=$page_index+1;$i<$page_index+$length+1;$i++)
        {
            if($i>$page_total)
            {
                continue;
            }
            $page_list[]=$i;
        }
        $list=array();
        foreach ($page_list as $temp)
        {
            $temp_url=$this->base_url.'page='.$temp;
            $temp_right=$this->base_temp;
            $temp_right=str_replace('{{page}}}',$temp,$temp_right);
            $temp_right=str_replace('{{url}}',$temp_url,$temp_right);
            $list[]=$temp_right;
        }
        $this->right=$list;
    }

    /*设置 左省略*/
    public function setLeftPoint()
    {
        $page_index=intval($this->page_index);
        $length=intval($this->length);
        if($page_index-$length>1)
        {
            $base_temp=$this->base_temp;
            $base_temp=str_replace('{{page}}}','...',$base_temp);
            $base_temp=str_replace('{{url}}','#',$base_temp);
            $this->left_point=$base_temp;
        }
    }

    /*设置 右省略*/
    public function setRightPoint()
    {
        $page_index=intval($this->page_index);
        $page_total=intval($this->page_total);
        $length=intval($this->length);
        if($page_index+$length<$page_total)
        {
            $base_temp=$this->base_temp;
            $base_temp=str_replace('{{page}}}','...',$base_temp);
            $base_temp=str_replace('{{url}}','#',$base_temp);
            $this->right_point=$base_temp;
        }
    }

    /*获取 总分页*/
    public function getPageBlock()
    {
        $content='';
        $content=$content.$this->before."\n";
        $content=$content.$this->left_point."\n";
        foreach ($this->left as $temp)
        {
            $content=$content.$temp."\n";
        }
        $content=$content.$this->current."\n";
        foreach ($this->right as $temp)
        {
            $content=$content.$temp."\n";
        }
        $content=$content.$this->right_point."\n";
        $content=$content.$this->next."\n";
        $page_block=str_replace('{{content}}',$content,$this->box);
        return $page_block;
    }

    /*获取 LIMIT参数 D:\PhpProject\gouyisi\addons\ewei_shopv2\core\myclass\Page.php*/
    public function getLimit()
    {
        $page_index=intval($this->page_index);
        $page_size=intval($this->page_size);
        if($page_index==1)
        {
            $offset=0;
        }
        else
        {
            $offset=0+$page_size*($page_index-1);
        }
        return array($offset,$page_size);
    }



}
