<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>

<div class="wrapper">

  <div class="row">

    <div class="col-sm-12">

      <section class="panel">

      

        <header style="height: 50px;" class="panel-heading ">


        </header>

      <div class="monai-table">

          <?php  if($list) { ?>

        <table class="table convert-data-table data-table dataTable no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">

          <thead>

            <tr role="row">

            <th>公告内容</th>

            <th>创建时间</th>

            <th><i class="fa fa-cogs"></i>&nbsp;操作</th>

            </tr>

          </thead>

          <tbody>

          

          <?php  if(is_array($list)) { foreach($list as $index => $item) { ?>

            <tr>


                <td><div class="type-parent"><?php  echo $item['content'];?></div></td>

                <td><div class="type-parent"><?php  echo date("Y-m-d",$item['create_time']); ?></div>

                </td>

                <td>

                    <a href="#" title="编辑" class="btn btn-sm btn-primary bianji" data-id="<?php  echo $item['id'];?>"><i class="fa fa-pencil"></i></a>
                    <a href="javascript:vord(0)" title="确认删除？" class="shanchu btn btn-sm btn-danger"  data-id="<?php  echo $item['id'];?>"><i class="fa fa-trash-o"></i></a>

                </td>

            </tr>

        <?php  } } ?>

            </tbody>

        </table>

          <?php  } else { ?>

          <div class="monai-table-nodata">暂无公告信息</div>

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
            title: '公告',
            maxmin: false,
            shadeClose: true, //点击遮罩关闭层
            area : ['1300px' , '500px'],
            content: '/web/index.php?c=site&a=entry&m=monai_market&do=web&r=notice.index.notice_edit&id=' + id
        });
    });
    $('.shanchu').on('click', function(e){
        var id = $(this).attr('data-id');
        layer.confirm('确定删除？', {
          btn: ['确定','取消'] //按钮
        }, function(){
           $.post("<?php  echo webUrl('notice/index/notice_del')?>",{"id":id},function(result){
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