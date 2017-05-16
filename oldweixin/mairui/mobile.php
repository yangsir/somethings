<?php
/**
 * 微站管理
*/
define('IN_MOBILE', true);
require 'source/bootstrap.inc.php';
include model('mobile');

session_start();
$actions = array('channel', 'module', 'auth', 'entry', 'cash');
if (in_array($_GPC['act'], $actions)) {
	$action = $_GPC['act'];
} else {
	$action = 'channel';
}

$controller = 'mobile';
require router($controller, $action);