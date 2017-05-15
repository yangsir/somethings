create database weidemo;

#老师的列表
create table role(
`id` int(11) not null primary key auto_increment,
`name` varchar(255) not null,
`img` varchar(255) not null,
`descrpimg` varchar(255) not null,
`weixinimg` varchar(255) not null,
`addtime` int(11) not null,
`modifytime` int(11) not null,
`status` int(1) not null,
`bgcolor` varchar(25) not null
)ENGINE=myisam DEFAULT CHARSET=utf8;

INSERT INTO `role` VALUES (1, '刘云芝', 'uploads/2015-03-23/550fd7e10546a31396.jpg', 'uploads/2015-03-24/5510dbeee97fe96871.png', 'uploads/2015-03-23/550fd7e1061b484470.jpg', 1426943567, 1427170677, 0, '#fffadc');
INSERT INTO `role` VALUES (2, '梁丽红', 'uploads/2015-03-23/550fd7d0d4c5b11415.jpg', 'uploads/2015-03-24/5510dbe622a3638310.png', 'uploads/2015-03-23/550fd7d0d697568830.jpg', 1426943567, 1427170662, 0, '#fffadc');
INSERT INTO `role` VALUES (3, '莫秋花', 'uploads/2015-03-23/550fd7bb24f2968361.jpg', 'uploads/2015-03-24/5510dbdf0bae662182.png', 'uploads/2015-03-23/550fd7bb25fed90829.jpg', 1426943567, 1427170657, 0, '#fffadc');
INSERT INTO `role` VALUES (4, '李丽丹', 'uploads/2015-03-23/550fd7a4d951944221.jpg', 'uploads/2015-03-24/5510dbc63abfe19661.png', 'uploads/2015-03-23/550fd7a4da3c492281.jpg', 1426943567, 1427170646, 0, '#fffadc');
INSERT INTO `role` VALUES (5, '黄银春', 'uploads/2015-03-23/550fd7944073931066.jpg', 'uploads/2015-03-24/5510dbbd0953769071.png', 'uploads/2015-03-23/550fd794413c050725.jpg', 1426943567, 1427170669, 0, '#fffadc');
INSERT INTO `role` VALUES (6, '揭宝玲', 'uploads/2015-03-23/550fd78885b6984056.jpg', 'uploads/2015-03-24/5510dbb22bf3c98327.png', 'uploads/2015-03-23/550fd78886d9547833.jpg', 1426943567, 1427170627, 0, '#fffadc');
INSERT INTO `role` VALUES (7, '罗静', 'uploads/2015-03-23/550fd77e20e9919246.jpg', 'uploads/2015-03-24/5510dbaa7a98761662.png', 'uploads/2015-03-23/550fd77e25f1750740.jpg', 1426943567, 1427170619, 0, '#fffadc');
INSERT INTO `role` VALUES (8, '文城南', 'uploads/2015-03-23/550fd7702638883866.jpg', 'uploads/2015-03-24/5510db988459a88229.png', 'uploads/2015-03-23/ewm.jpg', 1426943567, 1427170611, 0, '#fffadc');
INSERT INTO `role` VALUES (9, '谢焕平', 'uploads/2015-03-23/550fd7656969176041.jpg', 'uploads/2015-03-24/5510dba2bbf3261740.png', 'uploads/2015-03-23/550fd7656a48747137.jpg', 1426943567, 1427170603, 0, '#fffadc');
INSERT INTO `role` VALUES (10, '金哲', 'uploads/2015-03-23/550fd75347a2541672.jpg', 'uploads/2015-03-24/5510db8e0074c60367.png', 'uploads/2015-03-23/550fd75348c6d77565.jpg', 1426948234, 1427170596, 0, '#fffadc');
INSERT INTO `role` VALUES (11, '罗斯浩', 'uploads/2015-03-23/550fd74222d0d11103.jpg', 'uploads/2015-03-24/5510db851231974723.png', 'uploads/2015-03-23/550fd74223c8921809.jpg', 1426948234, 1427170587, 0, '#fffadc');
INSERT INTO `role` VALUES (12, '陈泳顺', 'uploads/2015-03-23/550fd7248507933282.jpg', 'uploads/2015-03-24/5510db7b5b9f061406.png', 'uploads/2015-03-23/550fd72f8add272379.jpg', 1427082452, 1427170582, 0, '#fffadc');
INSERT INTO `role` VALUES (13, '张雪清', 'uploads/2015-03-23/550fd717d386f52402.jpg', 'uploads/2015-03-24/5510db6f529b185737.png', 'uploads/2015-03-23/550fd717d4c6e81752.jpg', 1427091561, 1427170538, 0, '#fffadc');
INSERT INTO `role` VALUES (14, '刘艳', 'uploads/2015-03-23/550fd709595ef94149.jpg', 'uploads/2015-03-24/5510db65901c027050.png', 'uploads/2015-03-23/550fd7095a4e272728.jpg', 1427091564, 1427170533, 0, '#fffadc');
INSERT INTO `role` VALUES (15, '李明沛', 'uploads/2015-03-23/550fd6fb2511a73187.jpg', 'uploads/2015-03-24/5510db5cd164460246.png', 'uploads/2015-03-23/550fd6fb25ede48902.jpg', 1427091618, 1427170526, 0, '#fffadc');

#用户表
create table lookuser(
    `lookuser_id` int(11) not null primary key auto_increment,
    `openid` varchar(255) not null,
    `nickname` varchar(255) not null,
    `sex` int(1) not null,
    `language` varchar(255) not null,
    `province` varchar(255) not null,
    `country` varchar(255) not null,
    `headimgurl` varchar(255) not null,
    index openid (`openid`)
)ENGINE=myisam DEFAULT CHARSET=utf8;

#用户查看老师
create table record(
    `record_id` int(11) not null primary key auto_increment,
    `openid` varchar(255) not null,
    `role_id` int(11) not null,
    index openid(`openid`),
    index role_id(`role_id`)
)ENGINE=myisam DEFAULT CHARSET=utf8;

##用户最后一次记录
#create table lastrecord(
#    `openid` varchar(255) not null,
#    `role_id` int(11) not null,
#    UNIQUE openrole (`openid`,`role_id`)
#)ENGINE=myisam DEFAULT CHARSET=utf8;
