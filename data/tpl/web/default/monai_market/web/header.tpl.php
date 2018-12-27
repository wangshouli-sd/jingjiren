<?php defined('IN_IA') or exit('Access Denied');?><?php  global $_GPC,$_W ?>

<?php  $menu= Validation::Instance()->getMenu(true);?>

<?php  $headarr= Validation::Instance()->headarr();?>

<?php  //echo "<pre>";print_r($menu);die; ?>

<!doctype html>

<html lang="zh-cn">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="Mosaddek">

    <meta name="keyword" content="slick, flat, dashboard, bootstrap, admin, template, theme, responsive, fluid, retina">

    <!--    <link rel="icon" type="image/png" href="<?php  if(!empty($_W['setting']['copyright']['icon'])) { ?><?php  echo $_W['attachurl'];?><?php  echo $_W['setting']['copyright']['icon'];?><?php  } else { ?>./resource/images/favicon.ico<?php  } ?>">

     -->



    <title><?php  echo $headarr['name'];?></title>

    <!--right slidebar-->



    <link href="<?php  echo MODEL_LOCAL?>static/bootstrap/css/slidebars.css" rel="stylesheet">



    <!--switchery-->

    <link href="<?php  echo MODEL_LOCAL?>static/bootstrap/js/switchery/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />



    <!--common style-->





    <link href="<?php  echo MODEL_LOCAL?>static/bootstrap/css/style.css" rel="stylesheet">

    <link href="<?php  echo MODEL_LOCAL?>static/bootstrap/css/style-responsive.css" rel="stylesheet">

    <link rel="stylesheet" href="./resource/css/common.css?v=20180203">

    <link href="<?php  echo MODEL_LOCAL?>static/bootstrap/css/common-reset.css" rel="stylesheet">



    <link rel="icon" type="image/png" href="<?php  if(!empty($_W['setting']['copyright']['icon'])) { ?><?php  echo $_W['attachurl'];?><?php  echo $_W['setting']['copyright']['icon'];?><?php  } else { ?>./resource/images/favicon.ico<?php  } ?>">
    
<script type="text/javascript">



    window.sysinfo = {

    <?php  if(!empty($_W['uniacid'])) { ?>'uniacid': '<?php  echo $_W['uniacid'];?>', <?php  } ?>

    <?php  if(!empty($_W['acid'])) { ?>'acid': '<?php  echo $_W['acid'];?>', <?php  } ?>

    <?php  if(!empty($_W['openid'])) { ?>'openid': '<?php  echo $_W['openid'];?>', <?php  } ?>

    <?php  if(!empty($_W['uid'])) { ?>'uid': '<?php  echo $_W['uid'];?>', <?php  } ?>

    'isfounder': <?php  if(!empty($_W['isfounder'])) { ?>1<?php  } else { ?>0<?php  } ?>,

        'siteroot': '<?php  echo $_W['siteroot'];?>',

            'siteurl': '<?php  echo $_W['siteurl'];?>',

            'attachurl': '<?php  echo $_W['attachurl'];?>',

            'attachurl_local': '<?php  echo $_W['attachurl_local'];?>',

            'attachurl_remote': '<?php  echo $_W['attachurl_remote'];?>',

            'module' : {'url' : '<?php  if(defined('MODULE_URL')) { ?><?php echo MODULE_URL;?><?php  } ?>', 'name' : '<?php  if(defined('IN_MODULE')) { ?><?php echo IN_MODULE;?><?php  } ?>'},

        'cookie' : {'pre': '<?php  echo $_W['config']['cookie']['pre'];?>'},

        'account' : <?php  echo json_encode($_W['account'])?>,

    };

</script>

<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/jquery-2.1.1.min.js"></script>
<!--Nice  检查兼容Scroll-->

<!--<script src="js/jquery-migrate.js"></script>-->

<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/bootstrap.min.js"></script>

<script src="<?php  echo MODEL_LOCAL?>static/plugin/layer-v3.1.1/layer.js"></script>



<script src="<?php  echo MODEL_LOCAL?>static/plugin/Validform_v5.3.2/Validform_v5.3.2_min.js"></script>

<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/switchery/switchery.min.js"></script>

<script src="./resource/js/app/util.js"></script>

<script type="text/javascript" src="./resource/js/app/common.min.js?v=20170802"></script>

<script src="./resource/js/require.js"></script>

<script src="./resource/js/app/config.js"></script>



<!--Nice  检查cssScroll-->

<!--<script src="js/modernizr.min.js"></script>-->



<!--Nice  滚动条Scroll-->

<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/jquery.nicescroll.js" type="text/javascript"></script>



<!--right 侧边栏隐藏slidebar-->

<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/slidebars.min.js"></script>



<!-- 开关按钮switchery-->



<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/switchery/switchery-init.js"></script>



<!--Sparkline 现状图Chart-->

<!--<script src="js/sparkline/jquery.sparkline.js"></script>

<script src="js/sparkline/sparkline-init.js"></script>-->

<!--common scripts for all pages-->



<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/scripts.js"></script>

</head>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('menu', TEMPLATE_INCLUDEPATH)) : (include template('menu', TEMPLATE_INCLUDEPATH));?>

