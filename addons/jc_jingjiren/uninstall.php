<?php
pdo_query("
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_bank') . " ;
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_dic') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_dic_category') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_document') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_member') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_member_distribution') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_member_getmoney') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_member_getmoney_log') . " ;
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_notice') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_process') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_project') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_project_category') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_project_table') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_san') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_suggest') . " ;
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_sysparams') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_task') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_tpmessage') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_upload') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_wallet') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_wallet_log') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_customer_remarks') . ";
DROP TABLE IF EXISTS " . tablename('jc_jingjiren_banner') . ";
");