<?php
$config = array(
	//'配置项'=>'配置值'
	'HOST'    => '127.0.0.1',
	'DB_TYPE' => 'mysql',
	'DB_HOST' => 'localhost',
	'DB_NAME' => 'meibao',
	'DB_USER' => 'root',
	'DB_PWD'  => '', 
    'DB_PORT'	 =>	3306,
	'DB_PREFIX'	 =>	'mb_',
	'DB_CHARSET' =>	'utf8',
	
	//'APP_GROUP_LIST' => array('Weixin','MBAdmin'), // 允许模块
	'APP_GROUP_LIST' => 'Weixin,MBAdmin', // 允许模块
	'DEFAULT_GROUP'  => 'Weixin', // 默认模块
	//'TMPL_ACE'       => '/Public/Ace',
	'TMPL_ACE'       => '/Public/Ace',
	'TMPL_MOBILE'       => '/Public/mobile',
);

$wxconfig = require_once('wxconfig.php');
return array_merge($config,$wxconfig);
