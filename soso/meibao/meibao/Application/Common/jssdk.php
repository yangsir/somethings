<?php
class JSSDK {

  private $appId;
  private $appSecret;
  private $domain;

  public function __construct() {

    $this->appId     = C('WEIXIN_APPID');
    $this->appSecret = C('WEIXIN_SECRET');
    $this->domain    = C('DOMAIN_URL');

  }

  public function getSignPackage($current_url) {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    //$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    //$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    //$url = "http://www.yanghehong.cn/meibao/front/index.html";
    $url = $this->domain.$current_url;

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("./Public/key/jsapi_ticket.json"));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen("./Public/key/jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

  private function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("./Public/key/access_token.json"));
    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
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

  private function httpGet($url) {
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


  /*卡卷部分*/

  //获取卡卷列表
  public function getCardList() {

    $token = $this->getAccessToken();
    $url   = "https://api.weixin.qq.com/card/batchget?access_token=$token";

    $param = json_encode(array('offset'=>0,'count'=>10));
    $data = https_request($url,$param);
    //{"errcode":0,"errmsg":"ok","card_id_list":["pJNFAuJsAtgyTRgM7wA227L5MJEU","pJNFAuBOUSlJiqP2dUQi4HYuGHRc"],"total_num":2}
    return $data;

  }
  
  //获取卡卷详情
  public function getCardDetail($card_id) {

    $card_id = $card_id ? $card_id : 'pJNFAuJsAtgyTRgM7wA227L5MJEU';

    $token = $this->getAccessToken();
    $url   = "https://api.weixin.qq.com/card/get?access_token=$token";

    $param = json_encode(array('card_id'=>$card_id));
    $data = https_request($url,$param);
    //{"errcode":0,"errmsg":"ok","card":{"card_type":"GENERAL_COUPON","general_coupon":{"base_info":{"id":"pJNFAuBOUSlJiqP2dUQi4HYuGHRc","logo_url":"http:\/\/mmbiz.qpic.cn\/mmbiz\/hRbYnvLfiaBdQgs6giafBtRTQ9Ed1Qz2GgB3ticlflLOYVMqyokibdXqoqtWBMK5PFYeNq2VPib2whmMPuorhmTppVg\/0","code_type":"CODE_TYPE_QRCODE","brand_name":"MEIDICK","title":"89元换购真皮钱包","sub_title":"","date_info":{"type":2,"fixed_term":30,"fixed_begin_term":0},"color":"#E75735","notice":"换购时请先兑换卡券","description":"1、参与微信公众平台游戏，玩满3000积分，即可领取此券，凭券用89元即可换取价值399元的真皮钱包一个； \r\n\r\n2、仅限在有效期内兑换。","location_id_list":[],"get_limit":1,"can_share":true,"can_give_friend":true,"status":"CARD_STATUS_VERIFY_OK","sku":{"quantity":10000,"total_quantity":10000},"create_time":1429156226,"update_time":1429156226,"js_oauth_uin_list":[],"auto_update_new_location":true},"default_detail":"凭券89元换购指定一款手袋"}}}
    //{"errcode":0,"errmsg":"ok","card":{"card_type":"GENERAL_COUPON","general_coupon":{"base_info":{"id":"pJNFAuJsAtgyTRgM7wA227L5MJEU","logo_url":"http:\/\/mmbiz.qpic.cn\/mmbiz\/hRbYnvLfiaBdQgs6giafBtRTQ9Ed1Qz2GgB3ticlflLOYVMqyokibdXqoqtWBMK5PFYeNq2VPib2whmMPuorhmTppVg\/0","code_type":"CODE_TYPE_QRCODE","brand_name":"MEIDICK","title":"199元手袋换购券","sub_title":"","date_info":{"type":2,"fixed_term":30,"fixed_begin_term":0},"color":"#9058CB","notice":"换购时请先兑换卡券","description":"1、参与微信公众平台游戏，玩满5000积分，即可领取此券，凭券用199元即可换取价值939元的真皮手袋一个；\r\n\r\n2、仅限在有效期内兑换。","location_id_list":[],"get_limit":1,"can_share":true,"can_give_friend":true,"status":"CARD_STATUS_VERIFY_OK","sku":{"quantity":10000,"total_quantity":10000},"create_time":1429150764,"update_time":1429150765,"js_oauth_uin_list":[],"auto_update_new_location":true},"default_detail":"凭券199元换购指定一款手袋"}}}
    return $data;

  }

  //获取卡卷jsapi
  public function getCardTicket() {

    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("./Public/key/jscard_ticket.json"));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=wx_card&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen("./Public/key/jscard_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;   

  }

  //生成签名
  public function get_signature($openid,$card_id) {

    //openid+timestamp+api_ticket+card_id
    //$time = time();
    $time = strtotime(date('Y-m-d',time()));
    $api_ticket = $this->getCardTicket();

    $signatureObj = new Signature();
    $signatureObj->add_data( $time );
    $signatureObj->add_data( $api_ticket );
    $signatureObj->add_data( $openid );
    $signatureObj->add_data( $card_id );
    $signature = $signatureObj->get_signature();

    return $signature;
    
  }
  
}


/*
 *签名类
 * */
class Signature{
	function __construct(){
		$this->data = array();
	}
	function add_data($str){
		array_push($this->data, (string)$str);
	}
	function get_signature(){
		sort( $this->data, SORT_STRING );
		return sha1( implode( $this->data ) );
	}
};
