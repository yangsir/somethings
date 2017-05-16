<?php
defined('IN_IA') or exit('Access Denied');
$current['designer'] = ' class="current"';
checkaccount();

$atype = '';
$gateway = array();
if($_W['account']['type'] == '1') {
	$atype = 'weixin';
	$gateway['get'] = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s";
	$gateway['create'] = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s";
	$gateway['delete'] = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s";
}
if($_W['account']['type'] == '2') {
	$atype = 'yixin';
	$gateway['get'] = "https://api.yixin.im/cgi-bin/menu/get?access_token=%s";
	$gateway['create'] = "https://api.yixin.im/cgi-bin/menu/create?access_token=%s";
	$gateway['delete'] = "https://api.yixin.im/cgi-bin/menu/delete?access_token=%s";
}
$account_token = "account_{$atype}_token";
$account_code = "account_weixin_code";

$menusetcookie = 'menuset-' . $_W['weid'];
if($_W['ispost']) {
	if($_GPC['do'] == 'remove') {
		$token = $account_token($_W['account']);
		$url = sprintf($gateway['delete'], $token);
		$content = ihttp_get($url);
		if(empty($content)) {
			message('接口调用失败，请重试！');
		}
		$dat = $content['content'];
		$result = @json_decode($dat, true);
		if($result['errcode'] == '0') {
			isetcookie($menusetcookie, '', -500);
			message('已经成功删除菜单，请重新创建。', create_url('menu'));
		} else {
			message("公众平台返回接口错误. <br />错误代码为: {$menus['errcode']} <br />错误信息为: {$menus['errmsg']} <br />错误描述为: " . $account_code($menus['errcode']));
		}
	}
	if($_GPC['do'] == 'refresh') {
		isetcookie($menusetcookie, '', -500);
		message('已清空缓存，将重新从公众平台接口获取菜单信息。', 'refresh');
	}
	require model('rule');
	$mDat = $_GPC['do'];
	$mDat = htmlspecialchars_decode($mDat);
	$menus = json_decode($mDat, true);
	if(!is_array($menus)) {
		message('操作非法.');
	}
	foreach($menus as &$m) {
		$m['name'] = urlencode($m['name']);
		if(is_array($m['sub_button'])) {
			foreach($m['sub_button'] as &$s) {
				$s['name'] = urlencode($s['name']);
			}
		}
	}
	$ms = array();
	$ms['button'] = $menus;
	$dat = json_encode($ms,JSON_UNESCAPED_UNICODE);
	$dat = urldecode($dat);
	$token = $account_token($_W['account']);
	$url = sprintf($gateway['create'], $token);
	$content = ihttp_post($url, $dat);
	$dat = $content['content'];
	$result = @json_decode($dat, true);
	if($result['errcode'] == '0') {
		isetcookie($menusetcookie, '', -500);
		message('已经成功创建菜单. ', create_url('menu'));
	} else {
		message("公众平台返回接口错误. <br />错误代码为: {$result['errcode']} <br />错误信息为: {$result['errmsg']} <br />错误描述为: " . $account_code($result['errcode']));
	}
}
$dat = $_GPC[$menusetcookie];
$dat = htmlspecialchars_decode($dat);
$menus = @json_decode($dat, true);
if(empty($menus) || !is_array($menus)) {
	$token = $account_token($_W['account']);
	$url = sprintf($gateway['get'], $token);
	$content = ihttp_get($url);
	if(empty($content)) {
		message('获取菜单数据失败，请重试！');
	}
	$dat = $content['content'];
	$menus = @json_decode($dat, true);
}
if(empty($menus) || !is_array($menus)) {
	message('获取菜单数据失败，请重试！');
}

if($menus['errcode'] && !in_array($menus['errcode'], array(46003))) {
	message("公众平台返回接口错误. <br />错误代码为: {$menus['errcode']} <br />错误信息为: {$menus['errmsg']} <br />错误描述为: " . $account_code($menus['errcode']));
}
if(is_string($menus['menu'])) {
	$menus['menu'] = @json_decode($menus['menu'], true);
}
if(!is_array($menus['menu'])) {
	$menus['menu'] = array();
}
isetcookie($menusetcookie, $dat, 86400);

if(is_array($menus['menu']['button'])) {
	foreach($menus['menu']['button'] as &$m) {
		if($m['key']) {
			$m['forward'] = $m['key'];
		}
		if(is_array($m['sub_button'])) {
			foreach($m['sub_button'] as &$s) {
				$s['forward'] = $s['key'];
			}
		}
	}
}
template('menu/designer');
