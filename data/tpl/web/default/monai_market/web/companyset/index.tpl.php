<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>

<div class="wrapper">



	<section class="panel">

		<header class="panel-heading">

			基础设置

		</header>

		<hr>

        <form action="<?php  echo webUrl('companyset/index')?>" method="post" enctype="multipart/form-data" class="form-horizontal tasi-form">

			<div class="panel-body">

				<div class="monai-form">

					<div class="form-group">

						<label class="col-sm-2 control-label col-lg-2" >公司名称</label>

						<div class="col-lg-6">

								<input type="text" class="form-control" id="notice" name="name" value="<?php  echo $result['name'];?>">

						</div>

					</div>

					<div class="form-group">

						<label class="col-sm-2 control-label col-lg-2" >公司logo</label>

						<div class="col-lg-6">


							<?php  echo tpl_form_field_image2('logo',$result['logo']);?>
							<span class="help-block">像素：300*300</span>
						</div>

					</div>
					<div class="form-group">

						<label class="col-sm-2 control-label col-lg-2" >卖车页展示图</label>

						<div class="col-lg-6">


							<?php  echo tpl_form_field_image2('sale_logo',$result['sale_logo']);?>
							<span class="help-block">像素：750*375</span>
						</div>

					</div>

					<div class="form-group">

						<label class="col-sm-2 control-label col-lg-2" >公司电话</label>

						<div class="col-lg-6">

								 <input type="text" class="form-control" id="notice" name="phone" value="<?php  echo $result['phone'];?>">

								 <!-- <span class="help-block">如果费用为零，就是免费发布</span> -->

						</div>

					</div>

					<div class="form-group">

						<label class="col-sm-2 control-label col-lg-2" >发布买车信息需要的费用</label>

						<div class="col-lg-6">
								 <input type="text" class="form-control" id="notice" name="release_money" value="<?php  echo $result['release_money'];?>">
								 <span class="help-block">如果费用为零，就是免费发布</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >置顶所需费用</label>
						<div class="col-lg-6">
								 <input type="text" class="form-control" id="notice" name="top_money" value="<?php  echo $result['top_money'];?>">
								 <span class="help-block">如果费用为零，就是免费置顶</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >置顶周期</label>
						<div class="col-lg-6">
								 <input type="text" class="form-control" id="notice" name="top_cycle" value="<?php  echo $result['top_cycle'];?>">
								 <span class="help-block">填写数字即可，最好为整数，单位天。</span>
						</div>
					</div>
					<!-- <div class="form-group">

										<label class="col-sm-2 control-label col-lg-2" >是否开启审核</label>

										<div class="col-lg-10">

											<div class="input-group m-b-10 radio-custom radio-primary">

								 <input type="radio" name="audit_status"  <?php  if($result['audit_status'] == 1) { ?> checked <?php  } ?> value="1" id='kaiqi'><label for="kaiqi">开启</label>

								 <input type="radio" name="audit_status"  <?php  if($result['audit_status'] == 0) { ?> checked <?php  } ?> value="0"  id='guanbi'><label for="guanbi">关闭</label>

											</div>

										</div>

									</div> -->

					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >前台是否可以上传车辆信息</label>
						<div class="col-lg-10">
							<div class="input-group m-b-10 radio-custom radio-primary">
								 <input type="radio" name="upload"  <?php  if($result['upload'] == 1) { ?> checked <?php  } ?> value="1" id='kaiqi1'><label for="kaiqi1">开启</label>
								  <input type="radio" name="upload"  <?php  if($result['upload'] == 0) { ?> checked <?php  } ?> value="0"  id='guanbi1'><label for="guanbi1">关闭</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >首页是否开启推荐门店</label>
						<div class="col-lg-10">
							<div class="input-group m-b-10 radio-custom radio-primary">
								 <input type="radio" name="recom"  <?php  if($result['recom'] == 1) { ?> checked <?php  } ?> value="1" id='kaiqi11'><label for="kaiqi11">开启</label>
								  <input type="radio" name="recom"  <?php  if($result['recom'] == 0) { ?> checked <?php  } ?> value="0"  id='guanbi11'><label for="guanbi11">关闭</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >小程序端首页车辆列表样式</label>
						<div class="col-lg-10">
							<div class="input-group m-b-10 radio-custom radio-primary">
								 <input type="radio" name="plate_type"  <?php  if($result['plate_type'] == 1) { ?> checked <?php  } ?> value="1" id='kaiqi112'><label for="kaiqi112">显示汽车详情图</label>
								  <input type="radio" name="plate_type"  <?php  if($result['plate_type'] == 2) { ?> checked <?php  } ?> value="2"  id='guanbi112'><label for="guanbi112">不显示汽车详情图</label>
							</div>
						</div>
					</div>
					<!-- <div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >流量主设置</label>
						<div class="col-lg-10">
							<div class="input-group m-b-10 radio-custom radio-primary">
												 <input type="radio" name="flow_set"  <?php  if($result['flow_set'] == 2) { ?> checked <?php  } ?> value="2" class="flow_set" id='flow_set'><label for="flow_set">开启</label>
												  <input type="radio" name="flow_set"  <?php  if($result['flow_set'] == 1) { ?> checked <?php  } ?> value="1" class="flow_set" id='flow_set1'><label for="flow_set1">关闭</label>
							</div>
						</div>
					</div>
					<div class="form-group flow_id" <?php  if($result['flow_set'] == 1) { ?> style="display: none;" <?php  } ?>>
						<label class="col-sm-2 control-label col-lg-2" >流量主广告位ID</label>
						<div class="col-lg-6">
								 <input type="text" class="form-control" id="notice" name="flow_id" value="<?php  echo $result['flow_id'];?>">
								 <span class="help-block">填写数字即可，最好为整数。</span>
						</div>
					</div> -->
					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >交易提醒</label>
						<div class="col-lg-10">
							<div class="input-group m-b-10">
								 <textarea style="width: 100%; height: 150px; word-wrap: break-word; resize: horizontal;" name="remind"><?php  echo $result['remind'];?></textarea>
								 <span class="help-block">输入\n代表换行，在小程序里不理解web端的回车换行</span>
							</div>
						</div>
					</div>

					<div class="form-group">

						<label class="col-sm-2 control-label col-lg-2" >腾讯地图开放平台秘钥</label>

						<div class="col-lg-6">

							<input type="text" class="form-control" name="map_key" value="<?php  echo $result['map_key'];?>">

							 <span class="help-block">腾讯地图开放平台注册账号后申请秘钥，然后把秘钥填入此处，如果不填发布车辆时无法获得汽车所在地的省市区</span>

						</div>

					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2">首页地区筛选按钮是否开启</label>
						<div class="col-lg-10">
							<div class="input-group m-b-10 radio-custom radio-primary">
								<input type="radio" name="area_set"  <?php  if($result['area_set'] == 1) { ?> checked <?php  } ?> value="1" id='area_set1'><label for="area_set1">是</label>
								<input type="radio" name="area_set"  <?php  if($result['area_set'] == 2) { ?> checked <?php  } ?> value="2"  id='area_set2'><label for="area_set2">否</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label col-lg-2" >汽配订购功能是否开启</label>
						<div class="col-lg-10">
							<div class="input-group m-b-10 radio-custom radio-primary">
								<input type="radio" name="qipei_open"  <?php  if($result['qipei_open'] == 1) { ?> checked <?php  } ?> value="1" id='qipei_open1'><label for="qipei_open1">开启</label>
								<input type="radio" name="qipei_open"  <?php  if($result['qipei_open'] == 0) { ?> checked <?php  } ?> value="0"  id='qipei_guanbi11'><label for="qipei_guanbi11">关闭</label>
							</div>
						</div>
					</div>
					<div class="form-group"></div>

				</div>
			</div>
			<footer class="panel-footer">
				<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-dot-circle-o"></i> 保存</button>
				<button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-ban"></i> 撤销</button>
			</footer>
        </form>
	</section>
</div>    
<!-- <script type="text/javascript">
    	$(".flow_set").change(function(){
    
        	//alter(123);
    
            var b = $('input[name="flow_set"]:checked ').val();
    
            if (b==2) {
    
                $(".flow_id").css('display','');
    
            }else{
    
                $(".flow_id").css('display','none');
    
            }
    
        });
    </script>  -->    

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('footer', TEMPLATE_INCLUDEPATH)) : (include template('footer', TEMPLATE_INCLUDEPATH));?>