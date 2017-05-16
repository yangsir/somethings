<?php
/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */
defined('IN_IA') or exit('Access Denied');

function setting_save($data = '', $key = '', $weid = 0) {
	if (empty($data) && empty($key)) {
		return FALSE;
	}
	if (is_array($data) && empty($key)) {
		foreach ($data as $key => $value) {
			$record[] = "('$key', '".iserializer($value)."')";
		}
		if ($record) {
			$return = pdo_query("REPLACE INTO ".tablename('settings')." (`key`, `value`) VALUES " . implode(',', $record));
		}
	} else {
		$record = array();
		$record['key'] = $key;
		$record['value'] = iserializer($data);
		$return = pdo_insert('settings', $record, TRUE);
	}
	cache_build_setting();
	return $return;
}

function setting_load($key = '') {
	if (empty($key)) {
		$settings = pdo_fetchall('SELECT * FROM ' . tablename('settings'), array(), 'key');
		
	} else {
		$key = is_array($key) ? $key : array($key);
		$settings = pdo_fetchall('SELECT * FROM ' . tablename('settings') . " WHERE `key` IN ('".implode("','", $key)."')", array(), 'key');
	}
	if(is_array($settings)) {
		foreach($settings as $k => &$v) {
			$settings[$k] = iunserializer($v['value']);
		}
	}
	return $settings;
}

function setting_upgrade_version($family, $version, $release) {
	$verfile = IA_ROOT . '/source/version.inc.php';
	$verdat = <<<VER
<?php
/**
 * 版本号
 * 
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 */

defined('IN_IA') or exit('Access Denied');

define('IMS_FAMILY', '{$family}');
define('IMS_VERSION', '{$version}');
define('IMS_RELEASE_DATE', '{$release}');
VER;
	file_put_contents($verfile, trim($verdat));
}

function setting_module_convert($manifest) {
	$module = array(
		'name' => $manifest['application']['identifie'],
		'title' => $manifest['application']['name'],
		'version' => $manifest['application']['version'],
		'type' => $manifest['application']['type'],
		'ability' => $manifest['application']['ability'],
		'description' => $manifest['application']['description'],
		'author' => $manifest['application']['author'],
		'url' => $manifest['application']['url'],
		'settings'  => intval($manifest['application']['setting']),
		'subscribes' => iserializer(is_array($manifest['platform']['subscribes']) ? $manifest['platform']['subscribes'] : array()),
		'handles' => iserializer(is_array($manifest['platform']['handles']) ? $manifest['platform']['handles'] : array()),
		'isrulefields' => intval($manifest['platform']['isrulefields']),
		'cover' => $manifest['bindings']['cover'],
		'rule' => $manifest['bindings']['rule'],
		'menu' => $manifest['bindings']['menu'],
		'home' => $manifest['bindings']['home'],
		'profile' => $manifest['bindings']['profile'],
		'shortcut' => $manifest['bindings']['shortcut'],
		'issystem' => 0
	);
	return $module;
}

function setting_module_manifest($modulename) {
	$manifest = array();
	$filename = IA_ROOT . '/source/modules/' . $modulename . '/manifest.xml';
	if (!file_exists($filename)) {
		return array();
	}
	$dom = new DOMDocument();
	$dom->load($filename);
	if($dom->schemaValidateSource(setting_module_manifest_validate())) {
		// 0.51xml
		$root = $dom->getElementsByTagName('manifest')->item(0);
		$vcode = explode(',', $root->getAttribute('versionCode'));
		$manifest['versions'] = array();
		if(is_array($vcode)) {
			foreach($vcode as $v) {
				$v = trim($v);
				if(!empty($v)) {
					$manifest['versions'][] = $v;
				}
			}
			$manifest['versions'][] = '0.52';
		}
		$manifest['install'] = $root->getElementsByTagName('install')->item(0)->textContent;
		$manifest['uninstall'] = $root->getElementsByTagName('uninstall')->item(0)->textContent;
		$manifest['upgrade'] = $root->getElementsByTagName('upgrade')->item(0)->textContent;
		$application = $root->getElementsByTagName('application')->item(0);
		$manifest['application'] = array(
			'name' => trim($application->getElementsByTagName('name')->item(0)->textContent),
			'identifie' => trim($application->getElementsByTagName('identifie')->item(0)->textContent),
			'version' => trim($application->getElementsByTagName('version')->item(0)->textContent),
			'type' => trim($application->getElementsByTagName('type')->item(0)->textContent),
			'ability' => trim($application->getElementsByTagName('ability')->item(0)->textContent),
			'description' => trim($application->getElementsByTagName('description')->item(0)->textContent),
			'author' => trim($application->getElementsByTagName('author')->item(0)->textContent),
			'url' => trim($application->getElementsByTagName('url')->item(0)->textContent),
			'setting' => trim($application->getAttribute('setting')) == 'true',
		);
		$platform = $root->getElementsByTagName('platform')->item(0);
		if(!empty($platform)) {
			$manifest['platform'] = array(
				'subscribes' => array(),
				'handles' => array(),
				'isrulefields' => false,
			);
			$subscribes = $platform->getElementsByTagName('subscribes')->item(0);
			if(!empty($subscribes)) {
				$messages = $subscribes->getElementsByTagName('message');
				for($i = 0; $i < $messages->length; $i++) {
					$t = $messages->item($i)->getAttribute('type');
					if(!empty($t)) {
						$manifest['platform']['subscribes'][] = $t;
					}
				}
			}
			$handles = $platform->getElementsByTagName('handles')->item(0);
			if(!empty($handles)) {
				$messages = $handles->getElementsByTagName('message');
				for($i = 0; $i < $messages->length; $i++) {
					$t = $messages->item($i)->getAttribute('type');
					if(!empty($t)) {
						$manifest['platform']['handles'][] = $t;
					}
				}
			}
			$rule = $platform->getElementsByTagName('rule')->item(0);
			if(!empty($rule) && $rule->getAttribute('embed') == 'true') {
				$manifest['platform']['isrulefields'] = true;
			}
		}
		$bindings = $root->getElementsByTagName('bindings')->item(0);
		if(!empty($bindings)) {
			global $points;
			$ps = array_keys($points);
			$manifest['bindings'] = array();
			foreach($ps as $p) {
				$define = $bindings->getElementsByTagName($p)->item(0);
				$manifest['bindings'][$p] = _setting_module_manifest_entries($define);
			}
		}
	} else {
		$err = error_get_last();
		if($err['type'] == 2) {
			return $err['message'];
		}
	}
	return $manifest;
}

function _setting_module_manifest_entries($elm) {
	$ret = array();
	if(!empty($elm)) {
		$call = $elm->getAttribute('call');
		if(!empty($call)) {
			$ret[] = array('call' => $call);
		}
		$entries = $elm->getElementsByTagName('entry');
		for($i = 0; $i < $entries->length; $i++) {
			$entry = $entries->item($i);
			$row = array(
				'title' => $entry->getAttribute('title'),
				'do' => $entry->getAttribute('do'),
				'direct' => $entry->getAttribute('direct') == 'true',
				'state' => $entry->getAttribute('state')
			);
			if(!empty($row['title']) && !empty($row['do'])) {
				$ret[] = $row;
			}
		}
	}
	return $ret;
}

function setting_module_manifest_validate() {
	$xsd = <<<TPL
<?xml version="1.0" encoding="utf-8"?>
<xs:schema xmlns="http://www.we7.cc" targetNamespace="http://www.we7.cc" xmlns:xs="http://www.w3.org/2001/XMLSchema" elementFormDefault="qualified">
	<xs:element name="entry">
		<xs:complexType>
			<xs:attribute name="title" type="xs:string" />
			<xs:attribute name="do" type="xs:string" />
			<xs:attribute name="direct" type="xs:boolean" />
			<xs:attribute name="state" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="message">
		<xs:complexType>
			<xs:attribute name="type" type="xs:string" />
		</xs:complexType>
	</xs:element>
	<xs:element name="manifest">
		<xs:complexType>
			<xs:all>
				<xs:element name="application" minOccurs="1" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="name" type="xs:string" minOccurs="1" maxOccurs="1" />
							<xs:element name="identifie" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="version" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="type" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="ability" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="description" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="author" type="xs:string"  minOccurs="1" maxOccurs="1" />
							<xs:element name="url" type="xs:string"  minOccurs="1" maxOccurs="1" />
						</xs:all>
						<xs:attribute name="setting" type="xs:boolean" />
					</xs:complexType>
				</xs:element>
				<xs:element name="platform" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="subscribes" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="handles" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="message" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:attribute name="embed" type="xs:boolean" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="bindings" minOccurs="0" maxOccurs="1">
					<xs:complexType>
						<xs:all>
							<xs:element name="cover" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="rule" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="menu" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="home" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="profile" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
							<xs:element name="shortcut" minOccurs="0" maxOccurs="1">
								<xs:complexType>
									<xs:sequence>
										<xs:element ref="entry" minOccurs="0" maxOccurs="unbounded" />
									</xs:sequence>
									<xs:attribute name="call" type="xs:string" />
								</xs:complexType>
							</xs:element>
						</xs:all>
					</xs:complexType>
				</xs:element>
				<xs:element name="install" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="uninstall" type="xs:string" minOccurs="0" maxOccurs="1" />
				<xs:element name="upgrade" type="xs:string" minOccurs="0" maxOccurs="1" />
			</xs:all>
			<xs:attribute name="versionCode" type="xs:string" />
		</xs:complexType>
	</xs:element>
</xs:schema>
TPL;
	return trim($xsd);
}
