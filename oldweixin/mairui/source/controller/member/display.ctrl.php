<?php 
defined('IN_IA') or exit('Access Denied');
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
if (isset($_GPC['status'])) {
	$where = " WHERE status = '".intval($_GPC['status'])."'";
}
$sql = 'SELECT * FROM ' . tablename('members') .$where . " LIMIT " . ($pindex - 1) * $psize .',' .$psize;
$members = pdo_fetchall($sql);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('members') . $where);
$pager = pagination($total, $pindex, $psize);

$founders = explode(',', $_W['config']['setting']['founder']);
foreach($members as &$m) {
	$m['founder'] = in_array($m['uid'], $founders);
}

template('member/display');
