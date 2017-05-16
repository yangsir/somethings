<?php 
/**
 * 设置中心
 */
define('IN_SYS', true);
require 'source/bootstrap.inc.php';
checklogin();

$actions = array('profile', 'module', 'designer', 'common', 'updatecache', 'register', 'copyright', 'template', 'style');
$action = in_array($_GPC['act'], $actions) ? $_GPC['act'] : 'module';

$controller = 'setting';
require router($controller, $action);

