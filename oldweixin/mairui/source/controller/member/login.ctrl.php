<?php
defined('IN_IA') or exit('Access Denied');
if(checksubmit()) {
	_login($_GPC['referer']);
}
cache_load('setting');
template('member/login');
