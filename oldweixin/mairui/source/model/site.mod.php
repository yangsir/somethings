<?php
defined('IN_IA') or exit('Access Denied');

function site_system_menus() {
	global $_W;
	return array(
		array('title'=>'首页', 'url'=> 'mobile.php?act=channel&name=index&weid='.$_W['weid']),
		array('title'=>'个人中心', 'url'=> 'mobile.php?act=channel&name=home&weid='.$_W['weid']),
	);
}
