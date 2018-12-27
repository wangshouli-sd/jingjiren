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
CREATE TABLE `ims_jc_jingjiren_customer_remarks` (
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
CREATE TABLE `ims_jc_jingjiren_banner` (
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