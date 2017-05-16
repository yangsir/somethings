<?php
/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */
defined('IN_IA') or exit('Access Denied');

function cloud_upgrade_client_define() {
	return array(
		'/cloud.php',
		'/source/model/cloud.mod.php',
		'/source/controller/cloud/upgrade.ctrl.php',
		'/source/controller/cloud/dock.ctrl.php',
		'/themes/web/default/cloud/upgrade.html'
	);
}

function cloud_upgrade() {
	global $_W;
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['family'] = IMS_FAMILY;
	$pars['version'] = IMS_VERSION;
	$pars['release'] = IMS_RELEASE_DATE;
	$pars['key'] = $_W['setting']['site']['key'];
	$pars['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
	$clients = cloud_upgrade_client_define();
	$string = '';
	foreach($clients as $cli) {
		$string .= md5_file(IA_ROOT . $cli);
	}
	$pars['client'] = md5($string);

	$dat = ihttp_post('http://addons.we7.cc/gateway.php', $pars);
	$ret = array();
	if($dat && $dat['content']) {
		$content = $dat['content'];
		$obj = @simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
		if(!empty($obj)) {
			$ret['family'] = strval($obj->family);
			$ret['version'] = strval($obj->version);
			$ret['release'] = strval($obj->release);
			$ret['announcement'] = strval($obj->announcement);
			$ret['error'] = strval($obj->error);
			if(empty($ret['error'])) {
				$string = $content . $_W['setting']['site']['token'];
				if($dat['headers']['hash'] != md5($string)) {
					message('数据校验错误, 请检查您的站点注册信息中的AppKey和Token是否与本地一致. 如果确认无误依然提示本错误, 则你的网络可能被攻击. 请检查并保证安全后重试.');
				}
			}
			if($obj->scripts) {
				$ret['scripts'] = array();
				foreach($obj->scripts->script as $script) {
					$attr = $script->attributes();
					$v = strval($attr['version']);
					$r = strval($attr['release']);
					$s = strval($script);
					if($s) {
						$s = base64_decode($s);
					}
					$ret['scripts'][] = array(
						'version' => $v,
						'release' => $r,
						'script' => $s,
					);
				}
			}
			if($obj->attachments) {
				$ret['attachments'] = array();
				foreach($obj->attachments->file as $file) {
					$attr = $file->attributes();
					$path = strval($attr['path']);
					$sum = strval($attr['checksum']);
					$entry = IA_ROOT . $path;
					if(!is_file($entry) || md5_file($entry) != $sum) {
						$ret['attachments'][] = $path;
					}
				}
			}
		}
	}
	if(!empty($ret)) {
		cloud_upgrade_check($ret);
		if($ret['family'] == 'x' && IMS_FAMILY == 'v') {
			require_once model('setting');
			setting_upgrade_version('x', IMS_VERSION, IMS_RELEASE_DATE);
			message('您已经购买了商业授权版本, 系统将转换为商业版, 并重新运行自动更新程序.', 'refresh');
		}
		$ret['message'] = '';
		$ret['upgrade'] = false;
		if($ret['attachments'] || $ret['scripts']) {
			$ret['message'] = $ret['announcement'];
			if(!empty($ret['message'])) {
				$ret['message'] .= '<hr/>';
			}
			$ret['message'] .= "<strong>存在新版本: {$ret['version']} (Release {$ret['release']})</strong><br />系统发布更新了, 赶快更新你的系统来体验新功能吧. <br /><br /><a href=\"javascript:;\" onclick=\"$('#we7_tips').remove();window.frames['main'].location.href='" . create_url('cloud/upgrade') . "';\">立即查看更新</a>";
			$ret['upgrade'] = true;
		}
		$upgrade = array();
		$upgrade['message'] = $ret['message'];
		$upgrade['upgrade'] = $ret['upgrade'];
		$upgrade['lastupdate'] = TIMESTAMP;
		cache_write('upgrade', iserializer($upgrade));
	} else {
		message("存在错误, 不能自动更新. 错误详情: 更新服务器返回数据错误, 请访问论坛获取最新更新程序. 元数据:" . $dat['meta']);
	}
	return $ret;
}

function cloud_upgrade_download($archive) {
	global $_W;
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['family'] = IMS_FAMILY;
	$pars['version'] = IMS_VERSION;
	$pars['release'] = IMS_RELEASE_DATE;
	$pars['key'] = $_W['setting']['site']['key'];
	$pars['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
	$clients = cloud_upgrade_client_define();
	$string = '';
	foreach($clients as $cli) {
		$string .= md5_file(IA_ROOT . $cli);
	}
	$pars['client'] = md5($string);
	$pars['archive'] = base64_encode(json_encode($archive));
	$headers = array('content-type' => 'application/x-www-form-urlencoded');
	$dat = ihttp_request('http://addons.we7.cc/gateway.php', $pars, $headers, 300);
	$ret = array();
	if($dat && $dat['content']) {
		$content = $dat['content'];
		$obj = @simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
		if(!empty($obj)) {
			$ret['announcement'] = strval($obj->announcement);
			$ret['error'] = strval($obj->error);
			$string = $content . $_W['setting']['site']['token'];
			if(trim($dat['headers']['hash']) != md5($string)) {
				message('数据校验错误, 请检查您的站点注册信息中的AppKey和Token是否与本地一致. 如果确认无误依然提示本错误, 则你的网络可能被攻击. 请检查并保证安全后重试.');
			}
		}
	}
	if(!empty($ret)) {
		cloud_upgrade_check($ret);
	} else {
		message("存在错误, 不能自动更新. 错误详情: 推送更新包失败. 元数据:" . $dat['meta']);
	}
	$upfile = IA_ROOT . '/data/upgrade.zip';
	if(!file_exists($upfile)) {
		message("存在错误, 不能自动更新. 错误详情: 没有接收到服务器提供的更新包.");
	}
	$sign = md5(md5_file($upfile) . $_W['setting']['site']['token']);
	if($sign != $ret['announcement']) {
		@unlink($upfile);
		message("存在错误, 不能自动更新. 错误详情: 接收到的服务器提供的更新包数据校验错误, 请诊断你的云服务参数.");
	}
	return $upfile;
}

function cloud_upgrade_check(&$ret) {
	if($ret['error']) {
		switch($ret['error']) {
			case 'error-client':
				$ret['attachments'] = array(
					'error-client'
				);
				break;
			case 'error-warning':
				message('存在错误, 不能自动更新. 错误详情: 你使用的系统是由非法渠道传播的, 微擎已记录这个情况, 泄露此系统的商业用户将被停止商业服务, 微擎团队保留法律追究的权利. (如果属于误报, 请联系微擎团队)');
				break;
			case 'error-limit':
				message('公众开源版用户需要推荐2个用户安装使用微擎系统(需要推荐的用户注册云服务), 才能激活自动更新功能. 您也可以升级为商业用户, 来直接使用自动更新功能. 如果您是微擎合作开发者, 请联系微擎团队直接激活自动更新.', create_url('cloud/promotion'));
				break;
			default:
				message("存在错误, 不能自动更新. 错误详情: {$ret['error']}");
				break;
		}
	}
}
