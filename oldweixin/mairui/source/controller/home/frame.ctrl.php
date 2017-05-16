<?php
defined('IN_IA') or exit('Access Denied');
checklogin();
$do = !empty($_GPC['do']) && in_array($_GPC['do'], array('profile', 'global')) ? $_GPC['do'] : 'profile';
if(empty($_W['account']) || empty($do)) {
	$do = 'global';
}

if($_GPC['iframe']) {
	$iframe='?'.str_replace('&amp;', '&', $_GPC['iframe']);
} else {
	$iframe='?act=welcome&do=' . $do;
}

$wechats = account_search();
$types = array();
$types['business'] = '主要业务';
$types['customer'] = '客户关系';
$types['activity'] = '营销及活动';
$types['services'] = '常用服务及工具';
$types['other'] = '其他';

$mset = array();
if($do == 'profile') {
	$mset['basic'] = array('title' => '基本设置');
	foreach($types as $k => $v) {
		$mset[$k] = array('title' => $v, 'menus' => array());
	}
	$ms = array();
	if (!empty($_W['account']['modules'])) {
		$bindings = pdo_fetchall('SELECT * FROM ' . tablename('modules_bindings')." ORDER BY eid ASC");
		foreach($_W['account']['modules'] as $m) {
			$row = array('name' => strtolower($m['name']));
			$mg = $_W['modules'][$row['name']];
			if(in_array($row['name'], array('basic', 'news', 'music', 'userapi'))) {
				continue;
			}
			if(!empty($bindings)) {
				foreach($bindings as $entry) {
					if(strtolower($entry['module']) == $row['name']) {
						$m[$entry['entry']][] = array_elements(array('eid', 'call', 'title', 'do', 'direct', 'state'), $entry);
					}
				}
			}

			$row['title'] = $mg['title'];
			$row['type'] = $mg['type'];
			$row['type'] = in_array($row['type'], array_keys($types)) ? $row['type'] : 'other';
			if(!empty($m['cover']) && is_array($m['cover'])) {
				foreach($m['cover'] as $opt) {
					if(!empty($opt['call'])) {
						$site = WeUtility::createModuleSite($row['name']);
						if(method_exists($site, $opt['call'])) {
							$ret = $site->$opt['call']();
							if(is_array($ret)) {
								foreach($ret as $et) {
									$row['items'][] = array($et['title'], $et['url']);
								}
							}
						}
					} else {
						$row['items'][] = array(
							$opt['title'],
							create_url("rule/cover", array('eid' => $opt['eid']))
						);
					}
				}
			}
			if(!empty($m['rule']) || $mg['isrulefields']) {
				$row['items'][] = array(
					'关键字触发列表',
					create_url('rule/display', array('module' => $row['name'])),
					'childItems' => array('添加', create_url('rule/post', array('module' => $row['name']))),
				);
			}
			if(!empty($m['home']) || !empty($m['profile']) || !empty($m['shortcut'])) {
				$row['items'][] = array(
					'微站导航设置',
					create_url('site/nav', array('name' => $row['name'])),
				);
			}
			if(!empty($m['menu']) && is_array($m['menu'])) {
				foreach($m['menu'] as $opt) {
					if(!empty($opt['call'])) {
						$site = WeUtility::createModuleSite($row['name']);
						if(method_exists($site, $opt['call'])) {
							$ret = $site->$opt['call']();
							if(is_array($ret)) {
								foreach($ret as $et) {
									$row['items'][] = array($et['title'], $et['url']);
								}
							}
						}
					} else {
						$row['items'][] = array(
							$opt['title'],
							create_url("site/entry", array('eid' => $opt['eid']))
						);
					}
				}
			}
			if($mg['settings']) {
				$row['items'][] = array('参数设置', create_url('member/module/setting', array('mid' => $mg['mid'])));
			}
			$ms[strtolower($m['name'])] = $row;
		}
	}
	foreach($ms as $m) {
		if($m['items']) {
			$mset[$m['type']]['menus'][] = array('title' => $m['title'], 'items' => $m['items']);
		}
	}

	$menus = array();
	$menus[] = array(
		'title' => '自动回复',
		'items' => array(
			array(
				'文字回复',
				create_url('rule/display', array('module' => 'basic')),
				'childItems' => array('添加', create_url('rule/post', array('module' => 'basic'))),
			),
			array(
				'图文回复',
				create_url('rule/display', array('module' => 'news')),
				'childItems' => array('添加', create_url('rule/post', array('module' => 'news'))),
			),
			array(
				'音乐回复',
				create_url('rule/display', array('module' => 'music')),
				'childItems' => array('添加', create_url('rule/post', array('module' => 'music'))),
			),
			array(
				'自定义接口回复',
				create_url('rule/display', array('module' => 'userapi')),
				'childItems' => array('添加', create_url('rule/post', array('module' => 'userapi'))),
			),
			array('常用服务接入', create_url('site/module/switch', array('name' => 'userapi'))),
			array('自定义菜单', create_url('menu')),
			array('特殊回复', create_url('rule/system')),
		)
	);
	$menus[] = array(
		'title' => '基础功能',
		'items' => array(
			//array('二维码推广', create_url('member/barcode')),
			array('模块设置', create_url('member/module')),
			array('当前公众号设置', create_url('account/post', array('id' => 'current'))),
			//array('支付参数', create_url('account/payment', array('id' => 'current'))),
		)
	);
	$sMenus = array(
		'title' => '微站功能',
		'items' => array(
			array('网站风格设置', create_url('site/style')),
			array('微站导航图标', create_url('site/nav')),
			array('快捷菜单风格设置', create_url('site/shortcut')),
			array('微站访问入口', create_url('site/cover')),
		)
	);
	$menus[] = $sMenus;

	$mset['basic']['menus'] = $menus;
}
if($do == 'global') {
	$menus = array();
	$menus[] = array('title' => array('公众号管理', create_url('account/display')));
	if (!empty($_W['isfounder'])) {
		$menus[] = array('title' => array('常用服务管理', create_url('site/module/manage', array('name' => 'userapi'))));

	}
	if (!empty($_W['isfounder'])) {
		$module = array(
			'title' => '模块管理',
			'items' => array()
		);
		$module['items'][] = array('模块列表', create_url('setting/module'));
		$module['items'][] = array('模块设计', create_url('setting/designer'));
		$menus[] = $module;

		$template = array(
			'title' => '模板风格管理',
			'items' => array(),
		);
		$template['items'][] = array('微站风格管理', create_url('setting/template'));
		$template['items'][] = array('站点风格管理', create_url('setting/style'));
		$menus[] = $template;

		$user = array(
			'title' => '多用户管理',
			'items' => array()
		);
		$user['items'][] = array('资料设置', create_url('member/fields'));
		$user['items'][] = array('注册设置', create_url('setting/register'));
		$user['items'][] = array('用户管理', create_url('member/display'), 'childItems' => array('添加', create_url('member/create')));
		$user['items'][] = array('用户组管理', create_url('member/group'), 'childItems' => array('添加', create_url('member/group/post')));
		$menus[] = $user;

		
	}
	$system = array(
		'title' => '系统管理',
		'items' => array(
			array('账户管理', create_url('setting/profile')),
		)
	);
	if (!empty($_W['isfounder'])) {
		$system['items'][] = array('其它设置', create_url('setting/common'));
	}
	$system['items'][] = array('更新缓存', create_url('setting/updatecache'));
	$menus[] = $system;
	$mset[] = array(
		'title' => '全局参数设定',
		'menus' => $menus
	);
}
template('home/frame');
