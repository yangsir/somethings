<?php
//处理微信高级接口参数返回接口处理
function https_request($url,$data = '') {
    $curl = curl_init ();
    curl_setopt ( $curl, CURLOPT_URL, $url );
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        
    if (!empty($data)){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}

    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
    $data = curl_exec ( $curl );
    if (curl_errno ( $curl )) {
        return 'ERROR ' . curl_error ( $curl );
    }
    curl_close ( $curl );
    return $data;
}

//get
function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

// 判断是否是在微信浏览器里
function isWeixinBrowser() {
	$agent = $_SERVER ['HTTP_USER_AGENT'];
	if (! strpos ( $agent, "icroMessenger" )) {
		return false;
	}
	return true;
}

// 通过openid获取微信用户基本信息,此功能只有认证的服务号才能用
function getWeixinUserInfo($openid, $token) {
	$param ['appid'] = C('WEIXIN_APPID');
	$param ['secret'] = C('WEIXIN_SECRET');
	$param ['grant_type'] = 'client_credential';
	
	$url = 'https://api.weixin.qq.com/cgi-bin/token?' . http_build_query ( $param );
	$content = file_get_contents ( $url );
	$content = json_decode ( $content, true );
	
	$param2 ['access_token'] = $content ['access_token'];
	$param2 ['openid'] = $openid;
	$param2 ['lang'] = 'zh_CN';
	
	$url = 'https://api.weixin.qq.com/cgi-bin/user/info?' . http_build_query ( $param2 );
	$content = file_get_contents ( $url );
	$content = json_decode ( $content, true );
	return $content;
}

//网页获取用户
function OAuthWeixin($callback) {
	$isWeixinBrowser = isWeixinBrowser ();
	if (! $isWeixinBrowser ) {
		redirect ( $callback . '&openid=-1' );
	}
	$param ['appid'] = C('WEIXIN_APPID');
	
	if (! isset ( $_GET ['getOpenId'] )) {
		$param ['redirect_uri'] = $callback . '&getOpenId=1';
		$param ['response_type'] = 'code';
		$param ['scope'] = 'snsapi_base';
		$param ['state'] = 123;
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?' . http_build_query ( $param ) . '#wechat_redirect';
		redirect ( $url );
	} elseif ($_GET ['state']) {
		$param ['secret'] = C('WEIXIN_SECRET');
		$param ['code'] = I('code');
		$param ['grant_type'] = 'authorization_code';
		
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?' . http_build_query ( $param );
		$content = file_get_contents ( $url );
		$content = json_decode ( $content, true );
		redirect ( $callback . '&openid=' . $content ['openid'] );
	}
}

function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("./Public/key/access_token.json"));
    if ($data->expire_time < time()) {
        // 如果是企业号用以下URL获取access_token
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".C('WEIXIN_APPID')."&secret=".C('WEIXIN_SECRET');
        $res = json_decode(httpGet($url));
        $access_token = $res->access_token;
        if ($access_token) {
            $data->expire_time = time() + 7000;
            $data->access_token = $access_token;
            $fp = fopen("./Public/key/access_token.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    } else {
        $access_token = $data->access_token;
    }
    return $access_token;
}

//openid list
function getuserList($access_token, $openid = '') {

    $in   = 0; //是否在关注列表
    $data = json_decode(file_get_contents("./Public/key/wxuser_list.json"));
    $wxuser_list = $data->wxuser_list;
    if($wxuser_list && $openid && in_array($openid,$wxuser_list)) $in = 1;

    if (!$wxuser_list || $data->expire_time < time() || ($openid && $in == 0) ) {
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token&next_openid= ";
        $content = httpGet($url);
        
        $temp = json_decode($content,true);
        $wxuser_list = $temp['data']['openid'];
        
        if ($wxuser_list) {
            $data->expire_time = time() + 3600;
            $data->wxuser_list = $wxuser_list;
            $fp = fopen("./Public/key/wxuser_list.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);

            if($openid && in_array($openid,$wxuser_list)) $in = 1;
        }
    }

    return array(
            'attention' => $in,
            'list'      => $wxuser_list,
        );

}

// 生成用户卡卡号 TODO
function createCardNo(){
	$number = mt_rand(40000000,99999999);
    $count = M('card_member')->where("cart_no = $number")->count();
    if(!$count) return $number;

    createCardNo();
}

//二维码换取ticket
function createCodeTicket($access_token, $str) {
    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
    //'{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "123"}}}';
    $data = array(
        'action_name' => 'QR_LIMIT_STR_SCENE',
        'action_info' => array(
            'scene'=>array('scene_str'=>$str)
            )
    );
    $data = json_encode($data);
    $content = https_request($url,$data);

    $temp = json_decode($content,true);
    return $temp['ticket'];
}

//二维码换取
function getCodeShow($ticket) {

    $ticket = urlencode($ticket);
    $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";

}
