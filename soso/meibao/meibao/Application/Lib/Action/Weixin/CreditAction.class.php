<?php
class CreditAction extends BaseAction {
	
	public function _initialize(){
		parent::_initialize();
        $this->requireCommon();
		
        if(!isWeixinBrowser()) exit('请在微信中打开');

        $this->share_ucode = $_COOKIE['share_ucode'];
	}

    //游戏入口
    public function index() {

        //分享者的用户id储存
        $share_ucode = $_GET['share_ucode'];
        if($share_ucode) setcookie('share_ucode',$share_ucode,time()+3600*24*15);
        else setcookie('share_ucode','',time()-3600);

        $_SESSION['attention'] = 0; //没有关注
        $_SESSION['attention_a'] = '';
        $openid = $_GET['openid'];
        if($openid) { //用户通过点击消息进来
            $userInfo = M('wxuser')->where("user_code = '$openid'")->find();
            if(!$userInfo) { //第一次需记录用户信息
                $token = getAccessToken();
                $userInfo = getWeixinUserInfo($openid, $token);

                $data = array(
                    'user_code'  => $openid,
                    'nickname'   => $userInfo['nickname'],
                    'headimgurl' => $userInfo['headimgurl'],
                    'add_time'   => time(),
                );
                M('wxuser')->add($data);
            }

            $_SESSION['attention'] = 1; //关注
            $_SESSION['attention_a'] = '*+`!meibao*+`!'; //关注
            setcookie('ucode',$openid,time()+3600*24*15);
            setcookie('nickname',$userInfo['nickname'],time()+3600*24*15);
            setcookie('headimgurl',$userInfo['headimgurl'],time()+3600*24*15);

            redirect(C('DOMAIN_URL').'front/index.html');

        } else { //网页授权
            require_once('./Application/Common/weixin.php');
            $url = "index.php/Credit";
            
            $weixin = new WPublicController($url);
            $userInfo = $weixin->userInfo;
            $openid = $userInfo['openid'];
            if($openid) {
                $count = M('wxuser')->where("user_code = '$openid'")->count();
                if(!$count) { //第一次需记录用户信息
                    $data = array(
                        'user_code'  => $openid,
                        'nickname'   => $userInfo['nickname'],
                        'headimgurl' => $userInfo['headimgurl'],
                        'add_time'   => time(),
                    );
                    M('wxuser')->add($data);
                }

                $token = getAccessToken();
                $openidArr = getuserList($token,$openid);
                $_SESSION['attention'] = $openidArr['attention'] ? 1 : 0;
                if($openidArr['attention']) $_SESSION['attention_a'] = '*+`!meibao*+`!'; //关注
                //if(in_array($openid,$openidArr)) {
                //    $_SESSION['attention'] = 1; //关注
                //} else {
                //    $_SESSION['attention'] = 0; //未关注
                //}

                setcookie('ucode',$openid,time()+3600*24*15);
                setcookie('nickname',$userInfo['nickname'],time()+3600*24*15);
                setcookie('headimgurl',$userInfo['headimgurl'],time()+3600*24*15);
                redirect(C('DOMAIN_URL').'front/index.html');
            }
        }

    }

	// 获取总排名、总积分
    public function getRank() {

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        $pagename = $_REQUEST['pagename'];
        $url = 'front/'.$pagename.'.html';
        if($_REQUEST['score']) $url .= '?score='.$_REQUEST['score'];
        $signPackage = $this->getWeixinJsid($url); //weixin js share

		$cardObj = new CardModel($ucode);
        $cardObj->checkCard($ucode); //生成会员卡

		$creadit = new CreditModel($ucode);
        $attention = $_SESSION['attention'];
        if($attention) {
            //$credit = $creadit->getCount(); // 总积分
            $credit = $creadit->getNewCount(); // 总积分
        }
        $credit = $credit ? $credit : 0;
		$rank   = $creadit->getRank( $credit ); // 总排名

        $mycredit  = M('credit')->where("user_code = '$ucode'")->getField('credit'); //获取我的积分
        $cardidArr = M('award')->where('award_id in(2,3)')->field('credit,card_id')->order('award_id asc')->select();
        $cardcount = 0;
        foreach($cardidArr as $key => $val) {
            if($mycredit >= $val['credit']) {
                $signature = $this->getCardSignature($ucode,$val['card_id']);
                $time = strtotime(date('Y-m-d',time()));
                $card[] = array(
                    'card_id'   => $val['card_id'],
                    'openid'    => $ucode,
                    'timestamp' => $time,
                    'signature' => $signature,
                );
                $cardcount++;
            }
        }

		$data = array( 'result'=>1 );
		$data['data'] = array(
			'credit'      => $credit,
			'rank'        => $rank,
            'ucode'       => $attention ? $ucode : '',
		);
        $data['signPackage'] = $signPackage;
        $data['card']        = $card;
        $data['cardcount']   = $cardcount;

		exit(json_encode($data));
    }

    // 获取微信分享信息
    public function getWxshare() {

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        $pagename = $_REQUEST['pagename'];
        $signPackage = $this->getWeixinJsid('front/'.$pagename.'.html'); //weixin js share

        $mycredit  = M('credit')->where("user_code = '$ucode'")->getField('credit'); //获取我的积分
        $cardidArr = M('award')->where('award_id in(2,3)')->field('credit,card_id')->order('award_id asc')->select();
        $cardcount = 0;
        foreach($cardidArr as $key => $val) {
            if($mycredit >= $val['credit']) {
                $signature = $this->getCardSignature($ucode,$val['card_id']);
                $time = strtotime(date('Y-m-d',time()));
                $card[] = array(
                    'card_id'   => $val['card_id'],
                    'openid'    => $ucode,
                    'timestamp' => $time,
                    'signature' => $signature,
                );
                $cardcount++;
            }
        }

		$data = array( 'result'=>1 );
        $data['signPackage']   = $signPackage;
        $data['data']['ucode'] = $ucode;
        $data['card']        = $card;
        $data['cardcount']   = $cardcount;

		exit(json_encode($data));
    }

    //微信拉取卡劵
    public function getCard() {

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        /*
        $pagename = $_REQUEST['pagename'];
        $this->signPackage = $this->getWeixinJsid('front/'.$pagename.'.html'); //weixin js share
         */

        $award_id = $_REQUEST['award_id'] ? $_REQUEST['award_id'] : 2;
        if(!in_array($award_id,array(2,3))) $this->errorshow();

        $award = M('award')->find($award_id);
        $card_id = $award['card_id'];
        $credit = M('credit')->where("user_code = '$ucode'")->getField('credit');
        if($credit < $award['credit']) $this->errorshow();

        $signature = $this->getCardSignature($ucode,$card_id);
        $time = strtotime(date('Y-m-d',time()));
        $card = array(
            'card_id'   => $card_id,
            'openid'    => $ucode,
            'timestamp' => $time,
            'signature' => $signature,
        );

		$data = array( 'result'=>1 );
        $data['card'] = $card;
        //$data['signPackage'] = $this->signPackage;

        exit(json_encode($data));

    }

    //获取奖品
    public function getAward() {

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        $data = array('result' => 1);
        $data['data'] = M('award')->field('award_id,card_name,descrption,img,credit,money')->select();
        exit(json_encode($data));

    }

	// 获取总排名、总积分
    public function getList(){

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        /*
        $pagename = $_REQUEST['pagename'];
        $this->signPackage = $this->getWeixinJsid('front/'.$pagename.'.html'); //weixin js share
         */

		$creadit = new CreditModel($ucode);
        $lists = $creadit->getLists(array(),1,20,'c.credit'); 
        //取排行榜总积分前20
        if($lists) {
            foreach($lists as $key => $val) {
                $list[$key] = array(
                    'rank'       => $key + 1,
                    'nickname'   => $val['nickname'],
                    'headimgurl' => $val['headimgurl'],
                    'creadit'    => $val['credit'],
                );
            } 
            unset($lists);
        }

        //somethings about me
        //$credit = $creadit->getCount();
        $credit = $creadit->getNewCount(); // 总积分
        $credit = $credit ? $credit : 0;

        $nickname   = $_COOKIE['nickname'];
        $headimgurl = $_COOKIE['headimgurl'];
        if(empty($nickname) || empty($headimgurl)) {
            $userInfo = M('wxuser')->where("user_code = '$ucode'")->find();
            $nickname   = $userInfo['nickname'];
            $headimgurl = $userInfo['headimgurl'];
        }
        $current = array(
            'rank'        => $creadit->getRank( $credit ), 
            'nickname'    => $nickname,
            'headimgurl'  => $headimgurl,
            'creadit'     => $credit,
        );

		$data = array( 'result'=>1 );
		$data['data']        = $list;
        $data['current']     = $current;
        $data['isaward']     = $this->packetJudge();
        //$data['signPackage'] = $this->signPackage;
		exit(json_encode($data));

    }

	// 添加积分
    public function addCredit() {

        if(!$_SESSION['attention'] || $_SESSION['attention_a'] != '*+`!meibao*+`!') $this->errorshow(); //没关注不允许进来

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

		$value = intval($_REQUEST['credit']);
        if(!$value || $value > 1000) $this->errorshow();

		$creadit = new CreditModel($ucode);

        $share_ucode = $this->share_ucode;
        if($ucode == $share_ucode) $share_ucode = ''; //自己的分享给自己没有双倍积分
		$creadit->addCredit($value,$share_ucode);
		
	    exit(json_encode(array('result'=>1)));

    }

    //分享到朋友圈双倍积分
    public function shareDoubleCredit() {

        if(!$_SESSION['attention']) $this->errorshow(); //没关注不允许进来

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        $creadit = new CreditModel($ucode);
		$result = $creadit->addDoubleCredit();
        $tips = $result ? '喵，恭喜您获得双倍积分！' : '喵，双倍积分一天只能一次哦，明天再来！';
        exit($tips);

    }

    //获奖人填写资料
    public function awardMember() {

        if(!$_SESSION['attention']) $this->errorshow(); //没关注不允许进来

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        $data = array();
        $data['name']    = trim($_REQUEST['name']);
        $data['phone']   = trim($_REQUEST['phone']);
        $data['address'] = trim($_REQUEST['address']);
        $data['user_code'] = $ucode;
        $data['add_time']  = time();
        if(!$data['name'] || !$data['phone'] || !$data['address']) $this->errorshow();

        //中奖检查
        $count = M('packet_member')->where("user_code = '$ucode' and isfill = 0")->count();
        if(!$count) $this->errorshow();

        $result1 = M('award_member')->add($data); //中奖人信息填写
        if(!$result1) $this->errorshow();

        //填写状态更改
        $sdata = array('isfill'=>1);
        if($count == 1) { 
            M('packet_member')->where("user_code = '$ucode'")->save($sdata); 
        } else { //狗屎多个
            $item = M('packet_member')->field('packet_member_id')->where("user_code = '$ucode' and isfill = 0")->find();
            M('packet_member')->where("packet_member_id = {$item['packet_member_id']}")->save($sdata);
        }

		exit(json_encode(array('result'=>1)));

    }

    //中奖人列表
    public function getAwardMem() {

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

		$this->awardM = new AwardModel();
		$lists = $this->awardM->getMemLists($where,1,20);
        $data = array();
        if($lists) {
            foreach($lists as $key => $val) {
                $data[$key]['rank']       = $key+1;
                $data[$key]['headimgurl'] = $val['headimgurl'];
                $data[$key]['nickname']   = $val['nickname'];
                $data[$key]['credit']     = $val['credit'];
                $data[$key]['add_time']   = date('Y-m-d',$val['add_time']);
            }
        }

        $pagename = $_REQUEST['pagename'];
        $url = 'front/'.$pagename.'.html';
        $signPackage = $this->getWeixinJsid($url); //weixin js share

        $return = array( 
            'result'      => 1,
            'data'        => $data,
            'signPackage' => $signPackage,
        );
        exit(json_encode($return));

    }

    //卡卷扣分
    public function cardReduce() {
        
        if(!$_SESSION['attention']) $this->errorshow(); //没关注不允许进来
        
        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        $card_id = $_REQUEST['card_id'];
        $award = M('award')->where("card_id = '$card_id'")->find();
        if(!$award) $this->errorshow();

        $value = $award['credit'];
        $cardname = $award['card_name'];

        $creadit = new CreditModel($ucode);
        $creadit->delCredit($value);

        //exit($cardname);
        exit('1');

    }

    //游戏说明
    public function gameshow() {

        $gameshow = M('gameshow')->find(1);
        $gameshow['starttime'] = date('Y年m月d日',$gameshow['starttime']);
        $gameshow['endtime']   = date('Y年m月d日',$gameshow['endtime']);
        $data = array(
            'result' => 1,
            'data'   => $gameshow,
        ); 
        exit(json_encode($data));

    }

    //错误提示
    protected function errorshow() {
        exit(json_encode(array('result'=>0)));
    }

    //判断是否手袋中奖
    protected function packetJudge() {

        $ucode = $this->ucode;
        if(!$ucode) $this->errorshow();

        $count   = M('packet_member')->where("user_code = '$ucode' and isfill=0")->count();
        $isaward = $count ? 1 : 0;

        return $isaward;

    }



    public function test() {

        //$creadit = new CreditModel($this->ucode);
        //$creadit->delCredit($value=5);

        $card_id = 'pJNFAuJsAtgyTRgM7wA227L5MJEU';
        $openid = 'oJNFAuFsKnEEOVj7wNU7kj321YTY';
        $signature = $this->getCardSignature($openid,$card_id);

        $time = strtotime(date('Y-m-d',time()));
        $card = array(
            'card_id'   => $card_id,
            'openid'    => $openid,
            'timestamp' => $time,
            'signature' => $signature,
        );

        $url = 'index.php/Credit/test';
        $signPackage = $this->getWeixinJsid($url); //weixin js share

		$data = array( 'result'=>1 );
        $data['signPackage'] = $signPackage;
        $data['card']        = $card;

        $this->assign('data',$data);
        $this->display();

    }

    public function test1() {

        $this->requireJsSdk();
        $jssdk = $this->jssdk;

        $cardArr = $jssdk->getCardList();
        var_dump($cardArr);

        $card_id = $_GET['card_id'];

        $detail = $jssdk->getCardDetail($card_id);
        var_dump($detail);

    }

}
