<?php
class WxinfoAction extends BaseAction {

    private $fromUsername;
    private $toUsername;
    private $times;
    private $keyword;
    private $MsgType;
    private $Event;
    private $IsGiveByFriend;
    private $CardId;

    private $appid;
    private $appsecret;
    private $token;

    public function _initialize() {
		$this->requireCommon();

        $this->appid= C('WEIXIN_APPID');
        $this->appsecret = C('WEIXIN_SECRET');
        $this->token = C('WEIXIN_TOKEN');
    }

    //微信认证
    public function index() {

        $echoStr = $_GET["echostr"];
        if (! isset ( $echoStr )) {
        	$this->weixin_run();
        } else {
        	$this->valid ($echoStr);
        }
    }

    // 创建菜单
	public function createmenu() {

        /*
https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx444158ee22c7ad87&redirect_uri=http://www.yanghehong.cn/meibao/index.php/Credit&response_type=code&scope=snsapi_base&state=1&uin=MjU3NTUzNTk4Mg%3D%3D&key=70f481640b56f9ba4f16a7343e5eda937a1090cbd80fad02edf46c974706983e4d02dc00d8e344cdfecf9da66e19d064&version=16010310&pass_ticket=%2B8jMAMIdnEUgKIy6qqhZEIIfdyogppJCMoY5ppMsAaeqIpdS0rovfdjsiabIAeLo

https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx444158ee22c7ad87&redirect_uri=http://www.yanghehong.cn/meibao/index.php/Credit&response_type=code&scope=snsapi_base&state=1&uin=MjU3NTUzNTk4Mg%3D%3D&key=70f481640b56f9ba6954319952dc7aae0828ca28f11d1c3ff586138b81c65f4267467c96786b48ff554ab365f90dee27&version=16010310&pass_ticket=%2B8jMAMIdnEUgKIy6qqhZEIIfdyogppJCMoY5ppMsAaeqIpdS0rovfdjsiabIAeLo

https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx444158ee22c7ad87redirect_uri=http://www.yanghehong.cn/meibao/index.php/Credit&response_type=codescope=snsapi_basestate=1#wechat_redirect

https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcd983566d32442bc&redirect_uri=http://192.168.1.1/weixin/weixin.do?action=viewtest&response_type=code&scope=snsapi_base&state=1#wechat_redirect

https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0738856156f7323c&redirect_uri=http://israel.sinaapp.com/oauth2/&response_type=code&scope=snsapi_base&state=1#wechat_redirect;

        $gameurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".C('GAME_URL')."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
         */

        $jsonmenu = '{
     "button":[
        {
           "type":"view",
           "name":"玩游戏",
           "url":"'.C('GAME_URL').'"
        },
        {
           "type":"view",
           "name":"包子店",
           "url":"http://kdt.im/QO85h6BR9"
        },
        {
           "type":"view",
           "name":"会员卡",
           "url":"'.C('DOMAIN_URL').'index.php/Card/show_card"
        }
    ]
 }';

		$access_token=$this->initAccessToken();
        var_dump($access_token);
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
      
		$result = https_request($url, $jsonmenu);
        var_dump($result);
	    
	}

    private function valid($echoStr) {
        //$echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature() {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING); //这个在新的sdk中添加了第二个参数(compare items as strings)
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    //获取access_token
    protected function initAccessToken() {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->appsecret";
        $output = https_request($url);
        $jsoninfo = json_decode ( $output, true );
        $access_token = $jsoninfo ["access_token"];
        return $access_token;
    }

    protected function responseMsg() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        file_put_contents('xml.log',var_export($postStr,true),FILE_APPEND);
		if (!empty($postStr)) { 
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->fromUsername   = $postObj->FromUserName;
            $this->toUsername     = $postObj->ToUserName;
            $this->keyword        = trim($postObj->Content);
            $this->time           = time();
            $this->MsgType        = $postObj->MsgType;
            $this->Event          = $postObj->Event;
            $this->IsGiveByFriend = $postObj->IsGiveByFriend;
            $this->CardId         = $postObj->CardId;
        } else {
        	echo "Pay attention to <a href='http://{$_SERVER['HTTP_HOST']}'>http://{$_SERVER['HTTP_HOST']}</a>,thanks!";
        	exit;
        }
    }

    protected function weixin_run() {
        $this->responseMsg();

        if($this->Event =='text') { //文本咨询
            $data = $this->getData();
	        $this->fun_xml("text", $data, 1); 
        } else if($this->Event == 'subscribe') { //关注
            $data = $this->getWelData();
	        $this->fun_xml("text", $data, 1); 
        } else if($this->Event == 'user_get_card') { //领取卡卷
            $data = $this->getCardData();
	        $this->fun_xml("text", $data, 1); 
        }

    }

	//type: text 文本类型, news 图文类型
	//text,array(内容),array(ID)
	//news,array(array(标题,介绍,图片,超链接),...小于10条),条数
	private function fun_xml($type, $value_arr, $count) {
	    $con="<xml>
			  <ToUserName><![CDATA[{$this->fromUsername}]]></ToUserName>
			  <FromUserName><![CDATA[{$this->toUsername}]]></FromUserName>
			  <CreateTime>{$this->times}</CreateTime>
			  <MsgType><![CDATA[{$type}]]></MsgType>";
				
        switch($type) {
	        case "text" : 
		    $con.="<Content><![CDATA[$value_arr]]></Content>";
		        break;
		    case "news" : 
		    $con.="<ArticleCount>{$count}</ArticleCount>
				   <Articles>";
		    foreach($value_arr as $key => $v) {
            $con.="<item>
		       	   <Title><![CDATA[{$v[0]}]]></Title> 
		       	   <Description><![CDATA[{$v[1]}]]></Description>
		       	   <PicUrl><![CDATA[{$v[2]}]]></PicUrl>
		       	   <Url><![CDATA[{$v[3]}]]></Url>
		       	   </item>";
		    }
		    $con.="</Articles>";
		        break;
	    }
	    echo $con."</xml>";
	}

    private function getData() {
        $data = "你好，欢迎咨询美包包~\r\n".
                "<a href='".C('GAME_URL')."?openid=".$this->fromUsername."'>加入会员并开始游戏吧!</a>";
        return $data;
    }

    private function getWelData() {
        $data = "你好，欢迎关注美包包~\r\n".
                "<a href='".C('GAME_URL')."?openid=".$this->fromUsername."'>加入会员并开始游戏吧!</a>";
        return $data;
    }

    //卡卷进入
    private function getCardData() {
        //$cardname = $this->cardReduce(); 

        $data = "你好，你已成功领取美包包$cardname卡卷一张~\r\n".
                "可以在微信=》我=》卡包中查收!";
        return $data;
    }

    //卡卷扣分
    private function cardReduce() {
        $cardname = '';
        if($this->IsGiveByFriend == 0) {
            $CardId = $this->CardId;
            $award = M('award')->where("card_id = '$CardId'")->find();
            $value = $award['credit'];
            $cardname = $award['card_name'];

            $creadit = new CreditModel($this->fromUsername);
            $creadit->delCredit($value);
        }

        return $cardname;
    }

}
