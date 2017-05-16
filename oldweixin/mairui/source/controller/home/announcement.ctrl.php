<?php
defined('IN_IA') or exit('Access Denied');

if($_W['isfounder']) {
	cache_load('upgrade');
	if (!empty($_W['cache']['upgrade'])) {
		$upgrade = iunserializer($_W['cache']['upgrade']);
	}
	if(empty($_W['cache']['upgrade']) ||  TIMESTAMP - $upgrade['lastupdate'] >= 3600 * 24) {
		require model('setting');
		$upgrade = setting_upgrade();
	}
	if(empty($upgrade['message'])) {
		exit();
	}
	
}
