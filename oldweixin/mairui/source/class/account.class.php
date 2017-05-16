<?php
defined('IN_IA') or exit('Access Denied');

abstract class WeAccount {

	public function __construct() {
	}

	public function checkSign() {
		$signkey = array($token, $_GET['timestamp'], $_GET['nonce']);
		sort($signkey);
		$signString = implode($signkey);
		$signString = sha1($signString);
		return $signString == $_GET['signature'];
	}

	abstract public function fetchAccountInfo();

	abstract public function queryAvailableMessages();
	abstract public function parse($message);
	abstract public function response($packet);

	abstract public function queryPushActions();
	abstract public function push($openid, $packet);

	abstract public function queryMenuActions();
	abstract public function menuCreate($menu);
	abstract public function menuDelete();
	abstract public function menuModify($menu);
	abstract public function menuQuery();

	abstract public function queryFansActions();
	abstract public function fansGroupCreate($group);
	abstract public function fansGroupModify($group);
	abstract public function fansGroupAll();
	abstract public function fansGroupQuery($fan);
	abstract public function fansGroupMove($fan, $group);
	abstract public function fansQueryInfo($fan);
	abstract public function fansAll();

	abstract public function queryTrackActions();
	abstract public function trackCurrent($fan);
	abstract public function trackHistory($fan, $count = 10);

	abstract public function queryBarCodeActions();
	abstract public function barCodeCreateDisposable();
	abstract public function barCodeCreateFixed();
}
