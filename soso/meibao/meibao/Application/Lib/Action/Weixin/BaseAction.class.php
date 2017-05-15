<?php
class BaseAction extends Action {

    protected $jssdk;
    protected $ucode;
	
	/*
	* 公共初始化操作
	*/
	protected function _initialize(){

		$this->ucode = $_COOKIE['ucode'] ? $_COOKIE['ucode'] : 'oJNFAuFsKnEEOVj7wNU7kj321YTY';
		
	}

    //加载公用文件
    protected function requireCommon() {
        require_once('./Application/Common/function.php');
    }

    //加载jssdk
    protected function requireJsSdk() {

        require_once('./Application/Common/jssdk.php');
        $this->jssdk = new JSSDK();

    }

    //获取微信js的分享id
    protected function getWeixinJsid($current_url) {

        $this->requireJsSdk();
        $jssdk = $this->jssdk;
        $signPackage = $jssdk->GetSignPackage($current_url);

        return $signPackage;

    }

    //获取card验证码
    protected function getCardSignature($openid,$card_id) {

        $this->requireJsSdk();
        $jssdk = $this->jssdk;
        $signature = $jssdk->get_signature($openid,$card_id);

        return $signature;

    }

}
