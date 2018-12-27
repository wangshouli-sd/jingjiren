<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>

<div class="wrapper">

  <div class="row">

    <div class="col-sm-12">

      <section class="panel">

      <header style="height: 50px;" class="panel-heading ">


        </header>

      <div class="monai-table">

        <?php  if($list) { ?>

        <table class="table table-striped">

          <thead>

            <tr role="row">

              <th> 缩略图 </th>

              <th> 关联产品名称 </th>

              <th> 关联类型 </th>



              <th> <i class="fa fa-cogs"></i>&nbsp;操作 </th>

            </tr>

          </thead>

          <tbody>

          

          <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>

          <tr role="row" class="odd">

            <td><div class="type-parent"><img src="<?php  echo tomedia($item['img_patch']);?>" height="60" /></div></td>

            <td><div class="type-parent"><?php  echo $item['car_title'];?></div></td>

            <td>
              <div class="type-parent">
                <?php  if($item['type']==1) { ?>
                    <span style="margin-top: 10px;line-height: 15px;" class="badge bg-info">&nbsp;跳转车辆&nbsp;</span>
                <?php  } else if($item['type']==4) { ?>
                    <span style="margin-top: 10px;line-height: 15px;" class="badge bg-important">&nbsp;推广中心&nbsp;</span>
                <?php  } else if($item['type']==5) { ?>
                    <span style="margin-top: 10px;line-height: 15px;" class="badge bg-primary">&nbsp;店铺认证&nbsp;</span>
                <?php  } else if($item['type']==6) { ?>
                    <span style="margin-top: 10px;line-height: 15px;" class="badge bg-primary">&nbsp;无跳转&nbsp;</span>
                <?php  } ?>
              </div>
            </td>



            <td>

              <a href="#" title="编辑" class="btn btn-sm btn-primary bianji" data-id="<?php  echo $item['id'];?>"><i class="fa fa-pencil"></i></a>



              <!-- <a href="<?php  echo webUrl('slide/index/slide_edit',array('id'=>$item['id']))?>" title="编辑" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>编辑</a> -->



              <a href="javascript:vord(0)" data-ajax="" title="删除" data-id="<?php  echo $item['id'];?>" class="shanchu btn btn-sm btn-danger" ><i class="fa fa-trash-o"></i></a>



              <!-- <a href="javascript:vord(0)" data-ajax="<?php  echo webUrl('slide/index/slide_del',array('id'=>$item['id']))?>" title="确认删除？" class="btn btn-sm btn-default btn-ajax"><i class="fa fa-remove"></i>删除</a> -->

              </td>

          </tr>

          <?php  } } ?>

            </tbody>

          

        </table>

        <?php  } else { ?>

        <div class="monai-table-nodata">暂无轮播信息</div>

        <?php  } ?>

        </div>

        <div style="padding-top: 10px;float: right; margin-right:15px"> <?php  echo $pager;?> </div>

        <div style="width:100%; height:20px; clear:both"> </div>

      </section>

    </div>

    </form>

  </div>

</div>

<!--/.row--> 

<script>

    function selectBox(){

        var checkboxis = document.getElementsByName("xuanze[]");  

        for (var i=0; i<checkboxis.length; i++){ 

            checkboxis[i].checked = true; 

        }  

    }

    function fanselectBox(){

        var checkboxis = document.getElementsByName("xuanze[]");  

        for (var i=0; i<checkboxis.length; i++){ 

            checkboxis[i].checked = !checkboxis[i].checked;  

        }  

    } 

</script> 
<script type="text/javascript">
    $('.bianji').on('click', function(e){
        var id = $(this).attr('data-id')
        layer.open({
            type: 2,
            title: '轮播',
            maxmin: false,
            shadeClose: true, //点击遮罩关闭层
            area : ['1300px' , '800px'],
            content: '/web/index.php?c=site&a=entry&m=monai_market&do=web&r=slide.index.slide_edit&id=' + id
        });
    });
    $('.shanchu').on('click', function(e){
        var id = $(this).attr('data-id');
        layer.confirm('确定删除？', {
          btn: ['确定','取消'] //按钮
        }, function(){
           $.post("<?php  echo webUrl('slide/index/slide_del')?>",{"id":id},function(result){
                if (result==1) {
                    layer.msg('删除成功', {icon: 1});
                    setTimeout(function(){
                        window.location.reload();
                    },1000)
                }else
                {
                    layer.msg('删除失败', {icon: 2});
                    setTimeout(function(){
                        window.location.reload();
                    },1000)
                }
           });
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('footer', TEMPLATE_INCLUDEPATH)) : (include template('footer', TEMPLATE_INCLUDEPATH));?>