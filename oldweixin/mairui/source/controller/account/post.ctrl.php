<?php
defined('IN_IA') or exit('Access Denied');

$id = intval($_GPC['id']);
if ($_GPC['id'] == 'current') {
	$id = $_W['weid'];
}
if (!checkpermission('wechats', $id)) {
	message('公众号不存在或是您没有权限操作！');
}
if (checksubmit('submit')) {
	if (empty($_GPC['name']) && empty($_GPC['wxusername']) && empty($_GPC['wxpassword'])) {
		message('抱歉，请填写公众号名称！');
	}
	if (!$_W['isfounder'] && empty($id)) {
		$maxaccount = pdo_fetchcolumn("SELECT maxaccount FROM ".tablename('members_group')." WHERE id = :id", array(':id' => $_W['member']['groupid']));
		if (!empty($maxaccount)) {
			$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('wechats')." WHERE uid = :uid", array(':uid' => $_W['uid']));
			if (!empty($total) && $total >= $maxaccount) {
				message('抱歉，您已拥有最大限制数量的公众号！');
			}
		}
	}
	$data = array(
		'type' => intval($_GPC['type']),
		'uid' => $_W['uid'],
		'name' => $_GPC['name'],
		'account' => $_GPC['account'],
		'original' => $_GPC['original'],
		'token' => $_GPC['wetoken'],
		'key' => $_GPC['key'],
		'secret' => $_GPC['secret'],
		'signature' => '',
		'country' => '',
		'province' => '',
		'city' => '',
		'username' => '',
		'password' => '',
		'welcome' => '',
		'default' => '',
		'lastupdate' => '0',
		'default_period' => '0',
		'styleid' => 1,
	);
	if (!empty($_GPC['islogin']) && !empty($_GPC['wxusername']) && !empty($_GPC['wxpassword'])) {
		if ($_GPC['type'] == 1) {
			$loginstatus = account_weixin_login($_GPC['wxusername'], md5($_GPC['wxpassword']), $_GPC['verify']);
		} elseif ($_GPC['type'] == 2) {
			$loginstatus = account_yixin_login($_GPC['wxusername'], md5($_GPC['wxpassword']), $_GPC['verify']);
		}
		if ($loginstatus) {
			$data['username'] = $_GPC['wxusername'];
			$data['password'] = md5($_GPC['wxpassword']);
			$data['lastupdate'] = 0;
		}
	}
	if (!empty($_FILES['qrcode']['tmp_name'])) {
		$_W['uploadsetting'] = array();
		$_W['uploadsetting']['image']['folder'] = '';
		$_W['uploadsetting']['image']['extentions'] = array('jpg');
		$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
		$upload = file_upload($_FILES['qrcode'], 'image', "qrcode_{$id}");
	}
	if (!empty($_FILES['headimg']['tmp_name'])) {
		$_W['uploadsetting'] = array();
		$_W['uploadsetting']['image']['folder'] = '';
		$_W['uploadsetting']['image']['extentions'] = array('jpg');
		$_W['uploadsetting']['image']['limit'] = $_W['config']['upload']['image']['limit'];
		$upload = file_upload($_FILES['headimg'], 'image', "headimg_{$id}");
	}
	if (!empty($id)) {
		$update = array(
			'name' => $data['name'],
			'account' => $data['account'],
			'original' => $data['original'],
			'token' => $data['token'],
			'key' => $data['key'],
			'secret' => $data['secret'],
		);
		if (!empty($data['password'])) {
			$update['username'] = $data['username'];
			$update['password'] = $data['password'];
			$update['lastupdate'] = $data['lastupdate'];
		}
		pdo_update('wechats', $update, array('weid' => $id));
	} else {
		$data['hash'] = random(5);
		$data['token'] = random(32);
		if (pdo_insert('wechats', $data)) {
			$id = pdo_insertid();
		}
	}
	message('更新公众号成功！', create_url('account/post', array('id' => $id)));
}

$wechat = array();
if (!empty($id)) {
	$wechat = pdo_fetch("SELECT * FROM ".tablename('wechats')." WHERE weid = '$id'");
}
if(!empty($wechat['username']) && (empty($wechat['lastupdate']) || TIMESTAMP - $wechat['lastupdate'] > 86400 * 7)) {
	if ($wechat['type'] == 1) {
		$loginstatus = account_weixin_login($wechat['username'], $wechat['password']);
		$basicinfo = account_weixin_basic();
	} elseif ($wechat['type'] == 2) {
		$loginstatus = account_yixin_login($wechat['username'], $wechat['password']);
		$basicinfo = account_yixin_basic($wechat['username']);
	}
	if (!empty($basicinfo['name'])) {
		$update = array(
			'name' => $basicinfo['name'],
			'account' => $basicinfo['account'],
			'original' => $basicinfo['original'],
			'signature' => $basicinfo['signature'],
			'country' => $basicinfo['country'],
			'province' => $basicinfo['province'],
			'city' => $basicinfo['city'],
			'lastupdate' => TIMESTAMP,
		);
		if (!empty($basicinfo['key'])) {
			$update['key'] = $basicinfo['key'];
			$update['secret'] = $basicinfo['secret'];
		}
		pdo_update('wechats', $update, array('weid' => $id));
		$wechat['name'] = $basicinfo['name'];
		$wechat['account'] = $basicinfo['account'];
		$wechat['original'] = $basicinfo['original'];
		$wechat['signature'] = $basicinfo['signature'];
		$wechat['country'] = $basicinfo['country'];
		$wechat['province'] = $basicinfo['province'];
		$wechat['city'] = $basicinfo['city'];
		$wechat['key'] = $basicinfo['key'];
		$wechat['secret'] = $basicinfo['secret'];
	}
}
template('account/post');
