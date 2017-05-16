<?php
defined('IN_IA') or exit('Access Denied');
$founder = explode(',', $_W['config']['setting']['founder']);

$pindex = max(1, intval($_GPC['page']));
$psize = 10;

$type = intval($_GPC['type']);
$condition = empty($type) ? '' : " AND type = '{$type}'";
$condition .= $_W['isfounder'] ? '' : " AND uid = '{$_W['uid']}'";

$sql = "SELECT * FROM " . tablename('wechats') . " WHERE 1 $condition ORDER BY `weid` DESC LIMIT ".($pindex - 1) * $psize.','.$psize;
$list = pdo_fetchall($sql, array(), 'weid');
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wechats') . " WHERE  1 $condition");
$pager = pagination($total, $pindex, $psize);

if (!empty($list)) {
	foreach ($list as $row) {
		$uids[$row['uid']] = $row['uid'];
	}
	$users = pdo_fetchall("SELECT uid, username FROM ".tablename('members')." WHERE uid IN (".implode(',', $uids).")", array(), 'uid');
}
template('account/display');