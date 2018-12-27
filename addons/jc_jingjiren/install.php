<?php

$sql = "
CREATE TABLE " . tablename('jc_jingjiren_bank') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `bank_name` varchar(11) NOT NULL COMMENT '银行名称',
  `is_open` int(11) NOT NULL DEFAULT '1' COMMENT '0是不启用，1是启用',
  `order` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_dic') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  `name` varchar(200) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自定义字段'

";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_dic_category') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_document') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL COMMENT '标题',
  `description` text NOT NULL COMMENT '描述',
  `img` varchar(255) NOT NULL,
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章管理'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_member') . " (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `onlyid` varchar(255) DEFAULT NULL COMMENT '唯一标志码',
  `openid` varchar(50) NOT NULL,
  `access_token` varchar(50) NOT NULL DEFAULT '',
  `access_time` int(11) NOT NULL COMMENT '授权码时效',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `nick_name` varchar(50) DEFAULT NULL COMMENT '网名',
  `phone` varchar(20) DEFAULT NULL COMMENT '电话',
  `code` varchar(255) NOT NULL COMMENT '验证码',
  `idcard` varchar(50) DEFAULT NULL COMMENT '身份证号',
  `idcard_pic` varchar(100) DEFAULT NULL COMMENT '身份证图片',
  `sex` int(11) DEFAULT NULL COMMENT '性别 1：男/2：女/0：保密',
  `image` varchar(255) DEFAULT NULL COMMENT '头像',
  `brithday` varchar(255) DEFAULT NULL COMMENT '出生年月日',
  `city` varchar(255) DEFAULT NULL COMMENT '所在城市',
  `description` text COMMENT '个人简介',
  `project_id` varchar(255) DEFAULT NULL COMMENT '收藏的项目',
  `is_sender` int(11) DEFAULT '3' COMMENT '需求发布者权限 3:未申请过1：审核中/2：审核通过/0：未通过',
  `sender_remark` varchar(255) DEFAULT NULL COMMENT '经纪人不通过理由',
  `is_manager` int(11) DEFAULT '0' COMMENT '洽谈员权限 1：审核中/2：审核通过/0：未通过',
  `is_executer` int(11) DEFAULT '0' COMMENT '执行者权限 1：审核中/2：审核通过/0：未通过',
  `blacklist` int(11) NOT NULL DEFAULT '1' COMMENT '黑名单：1不在，0是在黑名单',
  `jing_time` int(11) NOT NULL DEFAULT '0' COMMENT '成为经纪人时间',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `is_del` int(11) DEFAULT '0' COMMENT '0:否/1：是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_member_distribution') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `two_level` int(11) NOT NULL DEFAULT '0' COMMENT '二级分销',
  `one_level` int(11) NOT NULL DEFAULT '0' COMMENT '一级分销',
  `main_id` int(11) NOT NULL COMMENT '需求发布者，跟member表id对应',
  `level` int(2) NOT NULL COMMENT '等级，1是一级分销。二是二级分销',
  `is_del` int(2) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_member_getmoney') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `truename` varchar(255) NOT NULL COMMENT '真实姓名',
  `alipay` varchar(255) NOT NULL COMMENT '支付宝号',
  `bank_card` varchar(23) DEFAULT NULL COMMENT '银行卡号',
  `bank` varchar(255) NOT NULL COMMENT '银行名',
  `mobile` int(11) NOT NULL COMMENT '手机号',
  `remarks` varchar(255) NOT NULL COMMENT '备注',
  `is_del` int(11) NOT NULL  DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_member_getmoney_log') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` varchar(11) NOT NULL COMMENT '提现编号',
  `getmoney_id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `type` int(2) NOT NULL COMMENT '提现方式的id1是支付宝，2是银行卡，3是微信',
  `money` float NOT NULL,
  `state` int(2) NOT NULL DEFAULT '1' COMMENT '1是审核中，2是提现中，3是已完成，0是为通过的',
  `remarks` varchar(255) NOT NULL,
  `is_del` int(2) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_notice') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(11) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `look` int(123) NOT NULL,
  `is_open` varchar(2) NOT NULL DEFAULT '1' COMMENT '是否显示隐藏，1是显示，二是隐藏',
  `create_time` varchar(255) NOT NULL,
  `update_time` varchar(255) NOT NULL,
  `is_del` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_process') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0' COMMENT '项目ID',
  `task_id` int(11) NOT NULL DEFAULT '0',
  `process_name` varchar(11) NOT NULL COMMENT '流程名',
  `type` int(11) NOT NULL COMMENT '1：洽谈/2：服务、3：项目流程',
  `content_type` int(11) NOT NULL COMMENT '1:无关金钱/2：涉及金钱',
  `price` int(11) DEFAULT '0' COMMENT '比例',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '1' COMMENT '1;未开始2：进行中3：已完成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='流程'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_project') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '1',
  `order` int(11) DEFAULT '0' COMMENT '项目排序',
  `name` varchar(11) NOT NULL COMMENT '项目名称',
  `subname` varchar(344) NOT NULL COMMENT '副标题',
  `category_id` int(11) NOT NULL COMMENT '类目id',
  `table_id` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slide` varchar(255) NOT NULL COMMENT '轮播图',
  `vt_price` varchar(255) NOT NULL DEFAULT '0' COMMENT '预计可的佣金 最小值，最大值（是个范围）',
  `img` varchar(255) DEFAULT NULL COMMENT '项目缩略图',
  `description_img` varchar(255) DEFAULT NULL COMMENT '项目详情图片',
  `vt_price_percent` varchar(255) NOT NULL COMMENT '佣金比例',
  `vt_price_already` int(255) NOT NULL COMMENT '以发放金额',
  `vt_price_total` int(255) NOT NULL DEFAULT '0' COMMENT '已成交单',
  `vt_project_count` int(255) NOT NULL DEFAULT '0' COMMENT '申请人数',
  `tags` varchar(255) NOT NULL DEFAULT '0' COMMENT '标签',
  `is_open` int(1) NOT NULL DEFAULT '1' COMMENT '1是启用，0是禁用',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `is_del` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='项目'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_project_category') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(25) NOT NULL COMMENT '项目分类名称',
  `description` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `create_time` varchar(24) NOT NULL DEFAULT '0',
  `is_open` varchar(2) NOT NULL DEFAULT '1' COMMENT '1是启用，0是禁用',
  `is_index` varchar(2) DEFAULT '1' COMMENT '1:首页推荐，0：不推荐',
  `update_time` varchar(24) NOT NULL DEFAULT '0',
  `is_del` int(1) NOT NULL DEFAULT '0' COMMENT '0是未删除。1是已删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_project_table') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '字段名',
  `order` int(22) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` varchar(255) NOT NULL DEFAULT '0',
  `update_time` varchar(255) NOT NULL DEFAULT '0',
  `is_del` int(2) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_san') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '名称',
  `title` varchar(255) NOT NULL COMMENT '名称下边的那段话',
  `url` varchar(255) NOT NULL COMMENT '跳转的链接',
  `order` int(255) NOT NULL COMMENT '排序',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `is_index` int(2) NOT NULL DEFAULT '1' COMMENT '0是不展示首页，1是在首页展示',
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '1是首页最顶端的banner图，2是中间三小块的',
  `create_time` varchar(255) NOT NULL,
  `update_time` varchar(255) NOT NULL,
  `is_del` int(2) NOT NULL DEFAULT '0' COMMENT '0是不删除，1是删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_suggest') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `send_id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `problem` text NOT NULL COMMENT '问题描述',
  `img` varchar(255) NOT NULL COMMENT '图片',
  `contact` varchar(255) NOT NULL COMMENT '联系方式，微信或手机号',
  `is_read` int(2) NOT NULL DEFAULT '0' COMMENT '是否读1是已读，0是未读',
  `create_time` varchar(255) NOT NULL,
  `update_time` varchar(255) NOT NULL,
  `is_del` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_sysparams') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `var` text NOT NULL COMMENT 'json值',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统参数'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_task') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_number` varchar(255) NOT NULL COMMENT '订单编号',
  `uniacid` int(11) NOT NULL,
  `project_id` int(11) NOT NULL COMMENT '项目ID',
  `userphone` varchar(11) NOT NULL COMMENT '联系方式',
  `username` varchar(11) NOT NULL COMMENT '联系人',
  `describe` varchar(255) NOT NULL,
  `dics_id` text NOT NULL COMMENT '自定义字段name',
  `dics_data` text NOT NULL COMMENT '自定义字段值',
  `state_check` int(11) NOT NULL DEFAULT '1' COMMENT '审核状态 1：审核中/2：审核通过/0：未通过',
  `state_why` varchar(255) NOT NULL COMMENT '审核理由，不通过才有',
  `state_progress` int(11) NOT NULL DEFAULT '0' COMMENT '流程进度 1：洽谈中/2：服务中/3：已完成/4:代传合同',
  `send_id` int(11) NOT NULL DEFAULT '0' COMMENT '需求提供者 默认 管理员为0',
  `is_bind_manager` int(11) NOT NULL DEFAULT '0',
  `manager_id` int(11) NOT NULL DEFAULT '0' COMMENT '洽谈人员 默认 管理员为0',
  `is_bind_executer` int(11) NOT NULL DEFAULT '0',
  `execute_id` int(11) NOT NULL DEFAULT '0' COMMENT '执行人员 默认 管理员为0',
  `is_stop` int(11) NOT NULL DEFAULT '0' COMMENT '是否打断整个任务',
  `stop_id` int(11) NOT NULL DEFAULT '0' COMMENT '打断者 0为管理员',
  `stop_remark` varchar(200) NOT NULL COMMENT '打断备注',
  `stop_state` int(11) NOT NULL DEFAULT '0' COMMENT '打断审核状态 1：审核中/2：审核通过/0：未通过',
  `total_price` int(11) NOT NULL DEFAULT '0' COMMENT '总价',
  `back_percent` int(11) NOT NULL DEFAULT '0' COMMENT '返佣比例',
  `contract_id` varchar(255) NOT NULL COMMENT '合同id',
  `finish_time` varchar(255) NOT NULL DEFAULT '0' COMMENT '签约时间',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务/订单'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_tpmessage') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='模板消息'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_upload') . " (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(200) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件'
";
pdo_query($sql);

$sql = "
CREATE TABLE " . tablename('jc_jingjiren_wallet') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `money` float NOT NULL DEFAULT '0' COMMENT '余额',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='钱包'
";
pdo_query($sql);
$sql = "
CREATE TABLE " . tablename('jc_jingjiren_wallet_log') . " (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL COMMENT '钱包ID',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务id',
  `process_id` int(255) DEFAULT NULL,
  `name` varchar(200) NOT NULL COMMENT '名称',
  `change_money` float NOT NULL COMMENT '变更金额',
  `type` int(11) NOT NULL COMMENT '类型 1：增加/2：减少',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_del` int(11) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

";
pdo_query($sql);

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


