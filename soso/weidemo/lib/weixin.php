<?php
/**
 * 微信消息回复格式方法
 * 微信access_token获取
 * 微信cul方法封装
 *        
 */
error_reporting(7);

class WPublicController {

    private $appid;
    private $appsecret;

    protected $host = 'http://youji.qianfeicn.com';
    protected $callBackUrl;
    public $userInfo;
    protected $code;
    protected $ret=array('ret'=>0,'msg'=>'');

    public function __construct($url='') {
        $this->appid = 'wx933a94fb82105fc7';
        $this->appsecret = '21d678016e033f199cf0a6f9cca4b429';

        $this->madeCallBackUrl($url);
        if($_GET['code']){
            $this->code=$_GET['code'];
        }

        $this->theUserInfo();
    }

    /**
     * 处理回调连接
     */
    private function madeCallBackUrl($url='') {
        
        $param=array();

        //http://youji.qianfeicn.com/weidemo/lib/weixin.php
        $this->callBackUrl=$this->host.$url;
        //$this->callBackUrl=$this->host.'/weidemo/lib/weixin.php';
        if(!empty($param)){
            $this->callBackUrl.=http_build_query($param);
        }

    }

    /**
     * 处理用户信息
     */
    protected function theUserInfo(){
        
        if($_COOKIE['userInfo']){
            $userInfo = json_decode($_COOKIE['userInfo'],true);
            if($userInfo && $userInfo != false) {
                $this->userInfo = $userInfo;
                return true;
            }
        }
        if($_REQUEST['cmType']){
            $this->userInfo=$this->waGetUserInfo();
        }else{
            $this->userInfo=$this->getWeixinUser();
        }

        setcookie('userInfo',json_encode($this->userInfo),time()+3600*24*30);

    }

    /**
     * 获取access_token
     */
    protected function initAccessToken() {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appid&secret=$this->appsecret";
        $output=$this->https_request($url);
        $jsoninfo = json_decode ( $output, true );
        $access_token = $jsoninfo ["access_token"];
        return $access_token;
    }

    /**
     * 获取access_token
     */
    protected function getopenId( $code1 ) {
        $code  = $code1;
        $state = $_GET["state"];

        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->appsecret&code=$code&grant_type=authorization_code";
        $access_token_json = $this->https_request($access_token_url);
        $access_token_array = json_decode($access_token_json, true);

        //var_dump( $access_token_array );

        $openid = $access_token_array['openid'];

        return $openid;
    }

    /**
     * 获取已关注用户的信息
     */
    protected function getuserinfo($code) {
        //echo '[***'.$code.'***]';
        //获取认证
        $ACTOKEN = $this->initAccessToken();
        //var_dump($ACTOKEN);
        //获取OPENID
        $openid = $this->getopenId($code);
        //var_dump($openid);
        //获取用户信息
        $access_token_url1 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$ACTOKEN&openid=$openid";
        $retstr = $this->https_request($access_token_url1);
        $ret = json_decode($retstr, true);
        //echo 1233;
        //var_dump($ret);
        //die();
        //subscribe值为0时代表此用户没有关注公众号，拉取不到其余信息。
        if($ret['subscribe']){
            return $ret;
        }

        return false;
    }

    /**
     * 网页授权方式获取用户信息 第2步
     */
    protected function waAccessToken(){
        $url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'&code='.$this->code.'&grant_type=authorization_code';

        $tkRet=$this->https_request($url);
        $tkRet=json_decode($tkRet,true);
        //var_dump($tkRet);
        return array('token'=>$tkRet['access_token'],'openId'=>$tkRet['openid']);
    }


    /**
     * 网页授权方式获取用户信息 第3步
     */
    protected function waGetUserInfo(){
        $accessToken=$this->waAccessToken($this->code);

        $userInfoUrl='https://api.weixin.qq.com/sns/userinfo?access_token='.$accessToken['token'].'&openid='.$accessToken['openId'].'&lang=zh_CN';

        $userInfoRet=$this->https_request($userInfoUrl);
        $userInfoRet=json_decode($userInfoRet,true);
        //var_dump($userInfoRet);
        if(!empty($userInfoRet['errcode'])){
            return false;
        }

        return $userInfoRet;
    }

    /**
     * 网页授权方式获取用户信息 第1步
     */
    protected function getOauth(){
        $this->callBackUrl.='?cmType=2';

        $this->getCode('snsapi_userinfo');
    }


    /**
     * 获取code
     * @param $scope    调用类型
     */
    protected function getCode($scope){
        $callbackUrl=urlencode($this->callBackUrl);

        $str="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=$callbackUrl&response_type=code&scope=$scope&state=123#wechat_redirect";
        //echo $str;
        //header("Location: $str");
        header("Location:".$str);
    }

    //获取关注用户信息
    protected function getWeixinUser(){
        if(!$this->code){
            $this->getCode('snsapi_base');
        }
        $userInfo=$this->getuserinfo($this->code);

        if(!$userInfo){
            //echo 222222222222;
            $this->getOauth();
        }
        return $userInfo;
    }

    /**
     * 处理微信高级接口参数返回接口处理
     */
    protected function https_request($url) {
        $curl = curl_init ();
        curl_setopt ( $curl, CURLOPT_URL, $url );
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, FALSE );
        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
        $data = curl_exec ( $curl );
        if (curl_errno ( $curl )) {
            return 'ERROR ' . curl_error ( $curl );
        }
        curl_close ( $curl );
        return $data;
    }


    /**
     * 处理curl封装的语音值
     */
    protected function http_request($curlPost, $url) {
        $ch = curl_init (); // 初始化curl
        curl_setopt ( $ch, CURLOPT_URL, $url ); // 抓取指定网页
        curl_setopt ( $ch, CURLOPT_HEADER, 0 ); // 设置header
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); // 要求结果为字符串且输出到屏幕上
        curl_setopt ( $ch, CURLOPT_POST, 1 ); // post提交方式
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $curlPost );
        $data = curl_exec ( $ch ); // 运行curl
        curl_close ( $ch );
        return $data;
    }


    /**
     * 当对象是数据时
     * 回复文本消息格式
     */
    public function transmitText($object, $content) {
        $textTpl = "
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
        $result = sprintf ( $textTpl, $object->FromUserName, $object->ToUserName, time (), $content );
        return $result;
    }


    /**
     * 处理客服接口cul封装的数据
     */
    protected function http_requestscheme($url,$data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }

}
