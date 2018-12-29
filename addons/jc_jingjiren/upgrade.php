<?php

$sql="

ALTER TABLE ".tablename('jc_jingjiren_notice')." MODIFY column `content`  text DEFAULT ''";

@pdo_query($sql);
$sql="

ALTER TABLE ".tablename('jc_jingjiren_project')." MODIFY column `description`  text DEFAULT ''";

@pdo_query($sql);
$sql="

ALTER TABLE ".tablename('jc_jingjiren_sysparams')." MODIFY column `var`  text DEFAULT ''";

@pdo_query($sql);

$sql = "
CREATE TABLE If Not Exists `ims_jc_jingjiren_customer_remarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `customer_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8_bin NOT NULL,
  `remarks` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '客户备注',
  `status` int(11) DEFAULT '0' COMMENT '0是为操作过的客户，1是有订单的客户',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `customer_first` varchar(255) NOT NULL,
  `is_del` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC
";
pdo_query($sql);

$sql="
CREATE TABLE If Not Exists `ims_jc_jingjiren_banner` (
  `banner_id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '小程序id',
  `img_patch` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `params` int(10) DEFAULT NULL COMMENT '跳转的id',
  `to_patch` varchar(255) DEFAULT NULL COMMENT '跳转路径',
  `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8";
pdo_query($sql);
$sql="

 ALTER TABLE `ims_jc_jingjiren_project` ADD COLUMN `order` INT(11) DEFAULT 0 NOT NULL AFTER `is_del`; ";

@pdo_query($sql);

$sql="
 ALTER TABLE `ims_jc_jingjiren_upload` alter column is_del set default '0'; ";

@pdo_query($sql);
$sql = "
CREATE TABLE If Not Exists `ims_jc_jingjiren_member_formid` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id 主键',
  `member_id` int(11) NOT NULL DEFAULT '1' COMMENT '人的id',
  `formid` varchar(255) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_del` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=utf8
";
pdo_query($sql);


$sql = "
ALTER TABLE `ims_jc_jingjiren_bank` CHANGE `bank_name` `bank_name` VARCHAR(11) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '银行名称', CHANGE `order` `order` INT(11) DEFAULT 1 NOT NULL";
pdo_query($sql);

$sql = "
ALTER TABLE `ims_jc_jingjiren_customer_remarks` CHANGE `uniacid` `uniacid` INT(11) DEFAULT 1 NOT NULL, CHANGE `customer_phone` `customer_phone` VARCHAR(255) CHARSET utf8 COLLATE utf8_bin DEFAULT '1' NOT NULL, CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);

$sql = "
ALTER TABLE `ims_jc_jingjiren_member` CHANGE `openid` `openid` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci NULL, CHANGE `access_token` `access_token` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '' NULL, CHANGE `access_time` `access_time` INT(11) NULL COMMENT '授权码时效', CHANGE `name` `name` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '姓名', CHANGE `code` `code` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '验证码', CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_member_distribution` CHANGE `main_id` `main_id` INT(11) DEFAULT 0 NOT NULL COMMENT '需求发布者，跟member表id对应', CHANGE `level` `level` INT(2) DEFAULT 0 NOT NULL COMMENT '等级，1是一级分销。二是二级分销'
";
pdo_query($sql);

$sql = "
ALTER TABLE `ims_jc_jingjiren_member_getmoney` CHANGE `alipay` `alipay` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '支付宝号', CHANGE `bank` `bank` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '银行名', CHANGE `mobile` `mobile` INT(11) NULL COMMENT '手机号', CHANGE `remarks` `remarks` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '备注', CHANGE `is_del` `is_del` INT(11) DEFAULT 0 NOT NULL, CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_member_getmoney_log` CHANGE `money` `money` FLOAT DEFAULT 0 NOT NULL, CHANGE `remarks` `remarks` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL, CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_notice` CHANGE `look` `look` INT(123) DEFAULT 0 NOT NULL, CHANGE `create_time` `create_time` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL, CHANGE `update_time` `update_time` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL, CHANGE `is_del` `is_del` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_process` CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_project` CHANGE `subname` `subname` VARCHAR(344) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '副标题', CHANGE `table_id` `table_id` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL, CHANGE `vt_price_already` `vt_price_already` INT(255) DEFAULT 0 NOT NULL COMMENT '以发放金额'
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_san` CHANGE `url` `url` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '跳转的链接', CHANGE `order` `order` INT(255) DEFAULT 1 NOT NULL COMMENT '排序', CHANGE `img` `img` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '图片', CHANGE `create_time` `create_time` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL, CHANGE `update_time` `update_time` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT '0' NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_sysparams` CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_task` CHANGE `describe` `describe` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL, CHANGE `dics_id` `dics_id` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '自定义字段name', CHANGE `dics_data` `dics_data` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '自定义字段值', CHANGE `state_why` `state_why` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '审核理由，不通过才有', CHANGE `stop_remark` `stop_remark` VARCHAR(200) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '打断备注', CHANGE `contract_id` `contract_id` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '合同id', CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);

$sql = "
ALTER TABLE `ims_jc_jingjiren_tpmessage` CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `is_del` `is_del` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_upload` CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);
$sql = "
ALTER TABLE `ims_jc_jingjiren_wallet` CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);

$sql = "
ALTER TABLE `ims_jc_jingjiren_wallet_log` CHANGE `change_money` `change_money` FLOAT DEFAULT 0 NOT NULL COMMENT '变更金额', CHANGE `create_time` `create_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `update_time` `update_time` INT(11) DEFAULT 0 NOT NULL, CHANGE `is_del` `is_del` INT(11) DEFAULT 0 NOT NULL
";
pdo_query($sql);

$sql="
 ALTER TABLE `ims_jc_jingjiren_project` ADD COLUMN `order` INT(11) DEFAULT 0 NOT NULL AFTER `is_del`; ";
@pdo_query($sql);
