--
-- 微信用户表 `mb_wxuser`
--
drop table IF EXISTS `mb_wxuser`;
CREATE TABLE IF NOT EXISTS `mb_wxuser` (
  `user_code` varchar(120) NOT NULL COMMENT '用户代码',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `headimgurl` varchar(255) not null comment '头像',
  `add_time` int(11) not null comment '添加时间',
  PRIMARY KEY (`user_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微信用户表' ;

--
-- 积分表 `mb_credit`
--
drop table IF EXISTS `mb_credit`;
CREATE TABLE IF NOT EXISTS `mb_credit` (
  `credit_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(120) NOT NULL COMMENT '用户代码',
  `credit` int(11) NOT NULL COMMENT '积分总额',
  `add_time` int(11) not null comment '添加时间',
  `update_time` int(11) not null comment '最后更新时间',
  key user_code(`user_code`),
  PRIMARY KEY (`credit_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='积分表' ;

--
-- 积分日志表 `mb_credit_log`
--
drop table IF EXISTS `mb_credit_log`;
CREATE TABLE IF NOT EXISTS `mb_credit_log` (
  `credit_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_code` varchar(120) NOT NULL COMMENT '用户代码',
  `credit` int(11) NOT NULL COMMENT '积分值',
  `change_type` int(11) NOT NULL COMMENT '变更类型：1加积分，2扣积分',
  `remarks` varchar(240) NULL COMMENT '备注',
  `to_user_code` varchar(120) not null comment '分享者用户代码',
  `add_time` int(11) NOT NULL,
  key user_code(`user_code`),
  PRIMARY KEY (`credit_log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='积分日志表' ;


--
-- 管理员表 `mb_admin`
--
drop table IF EXISTS `mb_admin`;
CREATE TABLE IF NOT EXISTS `mb_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(120) NOT NULL COMMENT '用户名',
  `pwd` varchar(120) NOT NULL COMMENT '密码',
  `store` varchar(120) not null comment '所属店铺',  
  `nickname` varchar(120) not null comment '姓名',
  `phone` varchar(120) not null comment '电话',
  `menu_ids` varchar(120) not null comment '菜单id',
  `issuper` int(1) not null default 0 comment '是否超级管理员',
  `add_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`),
  unique key (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表' ;
INSERT INTO `mb_admin` VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e','','','','',1, 1430058226, 1430058226);


--
-- 会员卡配置表 `mb_card_config`
--
drop table IF EXISTS `mb_card_config`;
CREATE TABLE `mb_card_config` (                                    
	`card_config_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键', 
    `bgurl` varchar(120) NOT NULL COMMENT '背景图地址',  
	`card_name` varchar(120) NOT NULL COMMENT '卡名',          
	`introduce` text DEFAULT NULL COMMENT '使用说明',  
	#`discount` tinyint(1) NOT NULL COMMENT '折扣',
	`discount` float(2,1) NOT NULL DEFAULT '10.0' COMMENT '折扣',
	`privilege` varchar(255) NOT NULL COMMENT '会员卡特权',
	`addr` varchar(120) DEFAULT NULL COMMENT '地址',  
	`tel` varchar(15) DEFAULT NULL COMMENT '电话',
	`site_url` varchar(120) DEFAULT NULL COMMENT '网址',   
	`add_time` int(11) NOT NULL,
	`update_time` int(11) NOT NULL,
	PRIMARY KEY (`card_config_id`)                                               
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员卡配置表' ;

INSERT INTO `mb_card_config` VALUES (1,'http://www.yanghehong.cn/meibao/Public/mobile/img/card_bg.png', '美包包会员卡', '向店员出示即可~',8,'8折优惠哦', '广东省深圳市河东路51号', '18617072406', 'http://www.yanghehong.cn', 0, 1430059726);

--
-- 会员卡表 `mb_card_member`
--
drop table IF EXISTS `mb_card_member`;
CREATE TABLE `mb_card_member` (                                    
	`card_member_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',  
	`user_code` varchar(120) NOT NULL COMMENT '用户代码',              
	`cart_no` varchar(30) NOT NULL COMMENT '卡号',      
    `card_value` int(11) NOT NULL DEFAULT '0' COMMENT '卡积分余额',                      
	`member_name` varchar(30) DEFAULT NULL,                             
	`member_tel` varchar(20) DEFAULT NULL,           
	`add_time` int(11) NOT NULL COMMENT '加入时间',                 
	PRIMARY KEY (`card_member_id`)                                               
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员卡表' ;

--
-- 会员卡积分日志表 `mb_card_member_clog`
--
drop table IF EXISTS `mb_card_member_clog`;
CREATE TABLE `mb_card_member_clog` (                                       
	`card_member_clog_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',  
	`card_member_id` int(11) NOT NULL COMMENT '卡ID',                       
	`user_code` varchar(120) NOT NULL COMMENT '用户代码',                
	`cvalue` int(11) NOT NULL COMMENT '消费积分',                        
	`cdeduction` int(11) NOT NULL COMMENT '抵扣金额',                    
	#`discount` tinyint(1) NOT NULL DEFAULT '10' COMMENT '折扣',
	`discount` float(2,1) NOT NULL DEFAULT '10.0' COMMENT '折扣',
	`operate_uid` tinyint(1) NOT NULL DEFAULT '1' COMMENT '操作用户ID',  
	`card_name` varchar(120) DEFAULT NULL,                                   
	`card_tel` varchar(120) DEFAULT NULL,                                    
    `productnumber` varchar(255) default null comment '商品货号',
	`add_time` int(11) NOT NULL COMMENT '加入时间',                      
	PRIMARY KEY (`card_member_clog_id`)                                      
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员卡积分日志表' ;



--
-- 用户兑奖联系表 `mb_award_member`
--
drop table IF EXISTS `mb_award_member`;
CREATE TABLE `mb_award_member` (                                    
	`award_member_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
	`user_code` varchar(120) NOT NULL COMMENT '用户代码',              
	`name` varchar(30) NOT NULL COMMENT '名称',              
    `phone` varchar(25) not null comment '电话号码',
    `address` varchar(255) not null comment '邮寄地址',
	`add_time` int(11) NOT NULL COMMENT '加入时间',                 
	PRIMARY KEY (`award_member_id`)                                               
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户兑换联系表' ;

#手袋中奖表
drop table IF EXISTS `mb_packet_member`;
CREATE TABLE `mb_packet_member` (
    `packet_member_id` int(11) not null auto_increment comment '主键',
	`user_code` varchar(120) NOT NULL COMMENT '用户代码',  
    `credit` int(11) not null comment '积分',
    `add_time` int(11) not null comment '创建时间',
    `isfill` int(1) not null default 0 comment '是否填写地址',
	primary KEY (`packet_member_id`),
	KEY (`user_code`),
	KEY (`add_time`)                                             
) ENGINE=MyISAM DEFAULT CHARSET=utf8 comment='手袋中奖列表';

--
-- 奖品表 `mb_award`
--
drop table IF EXISTS `mb_award`;
CREATE TABLE `mb_award` (                                    
	`award_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',  
    `card_id` varchar(255) not null comment '卡卷id',
    `card_name` varchar(2000) not null comment '卡卷名称',
    `descrption` varchar(3000) not null comment '描述',
    `money` int(11) not null comment '换购金额',
    `credit` int(11) not null comment '积分',
    `img` varchar(255) not null comment '图片',
    `add_time` int(11) not null comment '创建时间',
	`update_time` int(11) NOT NULL COMMENT '更新时间',
	PRIMARY KEY (`award_id`)                                               
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奖品表' ;
#alter table `mb_award` add column `descrption` varchar(3000) not null comment '描述';
INSERT INTO `mb_award` VALUES (1, '', '送手袋','描述1', 0, 10000,'',1430059726,1430059726);
INSERT INTO `mb_award` VALUES (2, 'pJNFAuJsAtgyTRgM7wA227L5MJEU', '109换购','描述2',109,3000,'',1430059726,1430059726);
INSERT INTO `mb_award` VALUES (3, 'pJNFAuBOUSlJiqP2dUQi4HYuGHRc', '269换购','描述3',269,5000,'',1430059726,1430059726);

--
-- 通知表 `mb_notice`
--
drop table IF EXISTS `mb_notice`;
CREATE TABLE `mb_notice` (                                    
	`notice_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',  
    `notice_title` varchar(255) not null comment '卡卷id',
    `notice_des` text not null comment '卡卷名称',
    `add_time` int(11) not null comment '创建时间',
	`end_time` int(11) DEFAULT '0' COMMENT '结束时间',
	PRIMARY KEY (`notice_id`)                                               
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='通知表' ;
INSERT INTO `mb_notice` VALUES (null, '看通知啦', '看通知啦~~~~看通知啦~~~~看通知啦~~~~看通知啦~~~~','1430059726',0);

--
-- 通知日志表 `mb_notice_log`
--
drop table IF EXISTS `mb_notice_log`;
CREATE TABLE `mb_notice_log` (                                    
	`notice_log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',  
    `notice_id` int(11) not null,
	`user_code` varchar(120) NOT NULL COMMENT '用户代码',  
    `add_time` int(11) not null comment '创建时间',
	PRIMARY KEY (`notice_log_id`)                                               
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='通知日志表' ;
INSERT INTO `mb_notice_log` VALUES (null, 1, 'oJNFAuFsKnEEOVj7wNU7kj321YTY','1430069726');
INSERT INTO `mb_notice_log` VALUES (null, 2, 'oJNFAuFsKnEEOVj7wNU7kj321YTY','1430079726');

--
-- 商家店铺表 `mb_business_shop`
--
drop table IF EXISTS `mb_business_shop`;
CREATE TABLE `mb_business_shop` (                                    
	`business_shop_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',  
    `shop_title` varchar(120) not null,
	`shop_tel` varchar(30) NOT NULL COMMENT '电话', 
	`shop_addr` varchar(120) NOT NULL COMMENT '地址', 
	`shop_jingdu` varchar(120) NOT NULL COMMENT '经度', 
	`shop_weidu` varchar(120) NOT NULL COMMENT '纬度', 
	`shop_info` varchar(360) NOT NULL COMMENT '简介',  
    `add_time` int(11) not null comment '创建时间',
	PRIMARY KEY (`business_shop_id`)                                               
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家店铺表' ;

#菜单管理表
drop table IF EXISTS `mb_menu`;
CREATE TABLE `mb_menu` (                                    
	`menu_id` int(11) NOT NULL auto_increment COMMENT '主键',  
    `name` varchar(255) not null comment '菜单名称',
    `menuurl` varchar(255) not null comment '菜单url', 
    `add_time` int(11) not null comment '创建时间',
    PRIMARY key(`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜单管理表' ;
#根目录
#insert into `mb_menu` values (null,'积分列表','/index.php/MBAdmin/Credit/lists',1431056406);
#insert into `mb_menu` values (null,'会员卡列表','/index.php/MBAdmin/Card/lists',1431056406);
#insert into `mb_menu` values (null,'会员卡配置','/index.php/MBAdmin/Card/config',1431056406);
#insert into `mb_menu` values (null,'消费记录','/index.php/MBAdmin/Card/cloglists',1431056406);
#insert into `mb_menu` values (null,'奖品列表','/index.php/MBAdmin/Ticket/lists',1431056406);
#insert into `mb_menu` values (null,'手袋获奖列表','/index.php/MBAdmin/Award/lists',1431056406);
#insert into `mb_menu` values (null,'通知列表','/index.php/MBAdmin/Notice/lists',1431056406);
#insert into `mb_menu` values (null,'商家列表','/index.php/MBAdmin/Business/lists',1431056406);
#insert into `mb_menu` values (null,'菜单列表','/index.php/MBAdmin/Menu/lists',1431056406);
#insert into `mb_menu` values (null,'角色列表','/index.php/MBAdmin/Role/lists',1431056406);
#INSERT INTO `mb_menu` VALUES (null, '游戏设置', '/index.php/MBAdmin/Gameset/config', 1431532954);
#非根目录
insert into `mb_menu` values (null,'积分列表','/meibao/index.php/MBAdmin/Credit/lists',1431056406);
insert into `mb_menu` values (null,'会员卡列表','/meibao/index.php/MBAdmin/Card/lists',1431056406);
insert into `mb_menu` values (null,'会员卡配置','/meibao/index.php/MBAdmin/Card/config',1431056406);
insert into `mb_menu` values (null,'消费记录','/meibao/index.php/MBAdmin/Card/cloglists',1431056406);
insert into `mb_menu` values (null,'奖品列表','/meibao/index.php/MBAdmin/Ticket/lists',1431056406);
insert into `mb_menu` values (null,'手袋获奖列表','/meibao/index.php/MBAdmin/Award/lists',1431056406);
insert into `mb_menu` values (null,'通知列表','/meibao/index.php/MBAdmin/Notice/lists',1431056406);
insert into `mb_menu` values (null,'商家列表','/meibao/index.php/MBAdmin/Business/lists',1431056406);
insert into `mb_menu` values (null,'菜单列表','/meibao/index.php/MBAdmin/Menu/lists',1431056406);
insert into `mb_menu` values (null,'角色列表','/meibao/index.php/MBAdmin/Role/lists',1431056406);
INSERT INTO `mb_menu` VALUES (null, '游戏设置', '/meibao/index.php/MBAdmin/Gameset/config', 1431532954);

#微信渠道二维码
drop table IF EXISTS `mb_wxcode`;
CREATE TABLE `mb_wxcode` (                                    
	`wxcode_id` int(11) NOT NULL auto_increment COMMENT '主键',  
    `name` varchar(1200) not null comment '店信息',
    `str` varchar(120) not null comment '渠道信息',
    `img` varchar(255) not null comment '二维码路径',
    `memnum` int(11) not null comment '扫码人数',
    `add_time` int(11) not null comment '创建时间',
    PRIMARY key(`wxcode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='渠道二维码' ;

#alter table mb_card_config modify `discount` float(2,1) NOT NULL DEFAULT '10.0' COMMENT '折扣';
#alter table mb_card_member_clog modify `discount` float(2,1) NOT NULL DEFAULT '10.0' COMMENT '折扣';
#alter table mb_card_member_clog add column `productnumber` varchar(255) default null comment '商品货号';

#游戏说明表
drop table IF EXISTS `mb_gameshow`;
CREATE TABLE `mb_gameshow` (                                    
	`gameshow_id` int(11) NOT NULL auto_increment COMMENT '主键',  
    `starttime` int(11) not null comment '开始时间',
    `endtime` int(11) not null comment '结束时间',
    `tip1` varchar(2000) not null comment '说明1',
    `tip2` varchar(2000) not null comment '说明2',
    `tip3` varchar(2000) not null comment '说明3',
    `tip4` varchar(2000) not null comment '说明4',
    PRIMARY key(`gameshow_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='游戏说明表' ;
insert into mb_gameshow values (null,'1431446400','1434124800','活动开始后每隔一天游戏积分在1万分以上且排行第一名的可免费领取美的真皮手袋一个，即27、29、1、3、5、7、9、11日开奖，兑换后积分会清零哦；','分享朋友圈获取双倍积分，一天只能一次噢；','找你的朋友帮忙玩，获取积分；','5000积分能以269购指定真皮手袋，3000积分能以109购买指定真皮银包。');

#设置开奖天表
drop table IF EXISTS `mb_setaward`;
CREATE TABLE `mb_setaward` (
	`setaward_id` int(11) NOT NULL auto_increment COMMENT '主键',  
    `cont` varchar(2000) not null comment '内容',
    PRIMARY key(`setaward_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='游戏开奖天表' ;
insert into mb_setaward values (null,'05-13,05-15,05-17,05-19,05-21,05-23,05-25,05-27,05-29');

--
-- 兑奖说明表 `mb_awardintro`
--
drop table IF EXISTS `mb_awardintro`;
CREATE TABLE `mb_awardintro` (
	`awardintro_id` int(11) NOT NULL auto_increment COMMENT '主键',  
    `content` text not null comment '内容',
    PRIMARY key(`awardintro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='兑奖说明' ;

INSERT INTO `mb_awardintro` (`awardintro_id`, `content`) VALUES
(1, '<br />\r\n<div style="padding:20px;">\r\n	<ul>\r\n		<strong>积分规则</strong> \r\n		<li>\r\n			成为储值卡会员可享受消费金额积分\r\n		</li>\r\n		<li>\r\n			积分可兑换礼品，具体规则如下\r\n		</li>\r\n		<li>\r\n			<br />\r\n		</li>\r\n	</ul>\r\n	<div>\r\n		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;凡持有此微会员卡的会员，到店购物后劲能用5000积分免费兑换价值199元真皮钱包一个，\r\n			每个会员只能兑换一次。<br />\r\n		<p>\r\n			注：只限活动期内兑换，且礼品数量有限，兑完即止！\r\n		</p>\r\n		<p>\r\n			<br />\r\n		</p>\r\n	</div>\r\n	<p>\r\n		<span style="color:red;">兑换礼品</span> \r\n	</p>\r\n	<ul>\r\n		<li>\r\n			在法律允许范围内，公司可以对本卡的使用规定作适当调整。\r\n		</li>\r\n	</ul>\r\n</div>');


#alter table mb_business_shop add column `shop_jingdu` varchar(120) NOT NULL COMMENT '经度';
#alter table mb_business_shop add column `shop_weidu` varchar(120) NOT NULL COMMENT '纬度';
#update mb_business_shop set shop_jingdu ='31.308707';
#update mb_business_shop set shop_weidu ='121.523804';

#alter table `mb_card_config` add column `privilege` varchar(255) NOT NULL COMMENT '会员卡特权';
