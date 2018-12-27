<?php defined('IN_IA') or exit('Access Denied');?>



<body class="sticky-header" style="background-color:#32323a; ">



<section>

    <!-- sidebar left start-->

    <div class="sidebar-left">

        <!--responsive view logo start-->

        <div class="logo dark-logo-bg visible-xs-* visible-sm-*">

            <a href="">

                <img src="<?php  echo tomedia($headarr['logo'])?>" alt="" width="40px" style="margin-left:20px">

                <!--<span class="brand-name">莫奈</span>-->

            </a>

        </div>

        <!--responsive view logo end-->

        <div class="sidebar-left-info">

            <!--sidebar nav start-->

            <ul class="nav nav-pills nav-stacked side-navigation">



                <?php  if(is_array($menu['menu'])) { foreach($menu['menu'] as $v) { ?>

                <li class="<?php  if($v['active']) { ?>nav-active<?php  } ?>">

                <a href="<?php  echo $v['url'];?>">

                    <i class="<?php  echo $v['icon'];?> "></i> <span><?php  echo $v['text'];?></span>

                </a>

                </li>

                

                <?php  } } ?>

            </ul>

            

            <!--sidebar nav end-->

        </div>

    </div>

    <!-- sidebar left end-->

<?php  if($menu['submenu']) { ?>
    <style type="text/css">
               
               .wrapper{
                width: 93%;
                margin-left: 100px;
               }
               .second_nav{
                width: 100px;
                height: 1200px;
                overflow: hidden;
                background: #fff;
                position: fixed;
                top: 63px;
                left: 130px;
               }
               .second_nav_modle{
                width: 100px;
                line-height: 30px;
                overflow: hidden;
                text-align: center;
                color: #1985ac;
                line-height: 50px;
                cursor: pointer;
                overflow-x: hidden;
                font-size: 14px;
                font-weight: 400;
               }
               .second_nav_modle:hover{
                background: #F5F5F5;
               }
               .second_nav_modle_active{
                color: #00aeff;
                background: #edf6ff;
               }
               .second_nav_modle_top{
                width: 70%;
                text-align: center;
                line-height: 100px;
                color: #333;
                font-size: 14px;
                font-weight: 400;
               }
               .second_nav_modle_top_img_close{
                cursor: pointer;
                display: block;
                float: right;
                line-height: 34px;
                font-size: 10px;
                text-align: center;
                background: #F5F5F5;
                color: #C0C0C0;
                border-right: 1px solid #F5F5F5;
                width: 17px;
                margin-top: 33px;
                height: 34px;
               }
               .second_nav_modle_top_img_open{
                position: absolute;
                cursor: pointer;
                display: block;
                float: right;
                line-height: 34px;
                font-size: 10px;
                text-align: center;
                background: #fff;
                z-index: 10;
                color: #C0C0C0;
                border-right: 1px solid #F5F5F5;
                width: 17px;
                margin-top: 33px;
                height: 34px;
               }
                .left_close {
                    -webkit-animation: left_close 0.5s;
                    width: 0px;
                }

                @-webkit-keyframes left_close {
                    0% {
                        width: 100px;
                    }

                    
                    100% {
                         width: 0px;
                    }

                }
                .left_open {
                    -webkit-animation: left_open 0.5s;
                    width: 100px;
                }

                @-webkit-keyframes left_open {
                    0% {
                        width: 0px;
                    }

                    
                    100% {
                         width: 100px;
                    }

                }
                .right_close {
                    -webkit-animation: right_close 0.5s;
                    width: 100%;
                margin-left: 0px;
                }

                @-webkit-keyframes right_close {
                    0% {
                        width: 93%;
                margin-left: 100px;
                    }

                    
                    100% {
                         width: 100%;
                margin-left: 0px;
                    }

                }
                .right_open {
                    -webkit-animation: right_open 0.5s;
                    width: 93%;
                margin-left: 100px;
                }

                @-webkit-keyframes right_open {
                    0% {
                        width: 100%;
                margin-left: 0px;
                        
                    }

                    
                    100% {
                         width: 93%;
                margin-left: 100px;
                    }

                }


    </style>

<?php  } ?>
<!-- <div style="visibility: hidden">

<style type="text/css">

   .wrapper{
    width: 100%;
    margin-left: 0px;
   }

</style>

</div> -->

    <!-- body content start-->

    <div class="body-content" style="min-height: 1200px;">
    <?php  if($menu['submenu']) { ?>
    <div class="second_nav_modle_top_img_open">》</div>
    <div class="second_nav">
    <div class="second_nav_modle_top_img_close">《</div>
    <div class="second_nav_modle_top"><?php  echo $menu['submenu']['subtitle'];?></div>
    <?php  if(is_array($menu['submenu']['items'])) { foreach($menu['submenu']['items'] as $va) { ?>
        <a href="<?php  echo $va['url'];?>">
            <div class="second_nav_modle <?php  if($va['active']) { ?> second_nav_modle_active<?php  } ?> "><?php  echo $va['title'];?></div>
        </a>
    <?php  } } ?>
    </div>
    <?php  } ?>

                    
<script type="text/javascript">
$(".second_nav_modle_top_img_open").hide()
$(".second_nav_modle_top_img_close").click(function(){
    $(".second_nav").removeClass("left_open");
  $(".wrapper").removeClass("right_open");
  $(".second_nav").addClass("left_close");
  $(".wrapper").addClass("right_close");
  $(".second_nav_modle_top_img_open").show()
});
$(".second_nav_modle_top_img_open").click(function(){
    $(".second_nav").removeClass("left_close");
  $(".wrapper").removeClass("right_close");
  $(".second_nav").addClass("left_open");
  $(".wrapper").addClass("right_open");
  $(".second_nav_modle_top_img_open").hide()
});

</script>    

        <!-- header section start-->

        <div class="header-section">
        
            <!--mega menu start-->



            <!--mega menu end-->

            <!--toggle button start-->

            <!--style="color:red;"-->

           <a class="toggle-btn" href="/web/index.php?c=site&a=entry&m=monai_market&do=web&version_id=<?php  echo $_GPC['version_id'];?>"><i class="fa fa-home fa-lg" ></i></a>

            <!--toggle button end-->

            <div  class="notification-wrap">

                <!--left notification start-->

                

                <!--left notification end-->

                <div class="right-notification">

                    <ul class="notification-menu">



                        <li>

                            <a href="javascript:;" class="btn btn-default dropdown-toggle" data-toggle="dropdown">

                                <img src="<?php  echo MODEL_LOCAL?>static/images/wx.png" alt=""><?php  echo $_W['account']['name'];?>

                                <span class=" fa fa-angle-down"></span>

                            </a>

                            <ul  class="dropdown-menu dropdown-usermenu purple pull-right" style="padding: 0px;">

                                <!-- <li style="width: 10px"><a href="<?php  echo webUrl('user/role/info')?>">  个人信息</a></li> -->

                               <!-- <li>

                                    <a href="javascript:;">

                                        <span class="badge bg-danger pull-right">4</span>

                                        <span>设置</span>

                                    </a>

                                </li>

                                <li>

                                    <a href="javascript:;">

                                        <span class="label bg-info pull-right">5</span>

                                        帮助

                                    </a>

                                </li>-->

                                <li>

                                    <a href="<?php  echo webUrl('user/role/index')?>">

                                        角色管理

                                    </a>

                                </li>

                                <li>

                                    <a href="<?php  echo webUrl('user/user/index')?>">

                                        操作员管理

                                    </a>

                                </li>

                                <?php  if($_W['isfounder']) { ?>

                                <li ><a href="/index.php?c=user&a=logout&"><i class="fa fa-sign-out fa-5x pull-right"></i>退出系统</a></li>

                                <?php  } ?>

                            </ul>

                        </li>

                        <?php  if($_W['isfounder']) { ?>

                            <li >

                                <a href="/web/index.php?c=wxapp&a=version&do=home&version_id=<?php  echo $_GPC['version_id'];?>" class="btn btn-default dropdown-toggle" >

                                    <span class=" fa fa-power-off fa-lg"></span>

                                </a>

                            </li>

                        <?php  } else { ?>

                        <li class="logout">

                            <a href="#" class="btn btn-default dropdown-toggle" >

                                <span class=" fa fa-power-off fa-lg"></span>

                            </a>

                        </li>

                        <?php  } ?>



                    </ul>

                </div>

                <!--right notification end-->

            </div>



        </div>

        <!-- header section end-->

