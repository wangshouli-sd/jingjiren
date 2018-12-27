<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>

<div class="wrapper">



	<section class="panel">

		<header class="panel-heading">

			推广设置

		</header>

		<hr>

        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal tasi-form">

		<div class="panel-body">

			<div class="monai-form">

				<div class="form-group">

                    <label class="col-sm-2 control-label col-lg-2" >推广码封面</label>

                    <div class="col-lg-6">

                        <?php  echo tpl_form_field_image2('image_patch',$result['image_patch']);?>
                        <span class="help-block">像素：750*375</span>

                    </div>

                </div>

                <div class="form-group">

					<label class="col-sm-2 control-label col-lg-2" >佣金比例</label>

					<div class="col-lg-6">

							 <input type="text" class="form-control" id="notice" name="scale" value="<?php  echo $result['scale'];?>">

							 <span class="help-block">填1~100的数字，代表百分比</span>

					</div>

				</div>
                <div class="form-group">
					<label class="col-sm-2 control-label col-lg-2" >是否开启推广</label>
					<div class="col-lg-10">
						<div class="input-group m-b-10 radio-custom radio-primary">
                             <input type="radio" name="status"  <?php  if($result['status'] == 1) { ?> checked <?php  } ?> value="1" id='kaiqi1'><label for="kaiqi1">开启</label>
                              <input type="radio" name="status"  <?php  if($result['status'] == 0) { ?> checked <?php  } ?> value="0"  id='guanbi1'><label for="guanbi1">关闭</label>
						</div>
					</div>
				</div>
				<div>
			</div>
		</div>
		</div>
        <footer class="panel-footer">
            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-dot-circle-o"></i> 保存</button>
            <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> 撤销</button>
        </footer>
        </form>
	</section>
</div>            

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('footer', TEMPLATE_INCLUDEPATH)) : (include template('footer', TEMPLATE_INCLUDEPATH));?>