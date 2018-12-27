<?php defined('IN_IA') or exit('Access Denied');?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('header', TEMPLATE_INCLUDEPATH)) : (include template('header', TEMPLATE_INCLUDEPATH));?>
<!-- page head start-->

<!-- page head end-->
<!--body wrapper start-->
<div class="wrapper">
<?php  if($_W['isfounder']) { ?>
<div class="alert alert-warning">
    <a href="/app/index.php?c=entry&m=monai_market&do=login&i=<?php  echo $_W['uniacid'];?>" class="close" data-dismiss="alert">
        登录
    </a>
   <strong>独立登录地址：</strong> <?php  echo $_W['siteroot'];?>app/index.php?c=entry&m=monai_market&do=login&i=<?php  echo $_W['uniacid'];?>
</div>
<?php  } ?>

                <!--state overview start-->
                <div class="row state-overview">
                    <div class="col-lg-3 col-sm-6">
                        <section class="panel purple">
                            <div class="symbol">
                                <i class="fa fa-car"></i>
                            </div>
                            <div class="value white">
                                <h1 class="timer" data-from="0" data-to="320"
                                    data-speed="1000">
                                    <?php  echo $car_detail;?>
                                </h1>
                                <p>前台发布车辆统计</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <section class="panel ">
                            <div class="symbol purple-color">
                                <i class="fa fa-comment"></i>
                            </div>
                            <div class="value gray">
                                <h1 class="purple-color timer" data-from="0" data-to="123"
                                    data-speed="1000">
                                    <?php  echo $feedback;?>
                                </h1>
                                <p>当前未处理举报数</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <section class="panel green">
                            <div class="symbol ">
                                <i class="fa fa-cny"></i>
                            </div>
                            <div class="value white">
                                <h1 class="timer" data-from="0" data-to="432"
                                    data-speed="1000">                                 
                                  <?php  if($finance=='') { ?>
                                    0.00
                                  <?php  } else { ?>
                                    <?php  echo $finance;?>
                                  <?php  } ?>
                                </h1>
                                <p>累积收入</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <section class="panel">
                            <div class="symbol green-color">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="value gray">
                                <h1 class="green-color timer" data-from="0" data-to="2345"
                                    data-speed="3000">
                                    <?php  echo $member;?>
                                </h1>
                                <p>会员人数</p>
                            </div>
                        </section>
                    </div>
                </div>

</div>
<!--body wrapper end-->


</div>
<!-- body content end-->
</section>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('footer', TEMPLATE_INCLUDEPATH)) : (include template('footer', TEMPLATE_INCLUDEPATH));?>