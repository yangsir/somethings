<?php
class CardAction extends BaseAction {
	
	public function _initialize(){
		parent::_initialize();
        $this->requireCommon();

		$tmplmobile = C('TMPL_MOBILE');    // 模板样式根目录
		$this->assign( 'tmplmobile',$tmplmobile );
	}
	
	// 会员卡信息
    public function show_card() {

        if(!$this->ucode) { //防止直接进入这个页面的情况
            require_once('./Application/Common/weixin.php');
            $url = "index.php/Card/show_card";
            
            $weixin = new WPublicController($url);
            $userInfo = $weixin->userInfo;
            $this->ucode = $userInfo['openid'];

            setcookie('ucode',$userInfo['openid'],time()+3600*24*15);
        }

		// 卡基本信息
		$CardM = new CardModel();
        $card = $CardM->getBase($this->ucode);
        $conf = $CardM->getConfig();
		//echo json_encode($data);
		
		// 消费积分记录
		$where = array('cl.user_code'=>$this->ucode);
		$valsum = $CardM->getCLogSum($where);
        $valsum = $valsum ? $valsum : 0;
		$this->assign( 'valsum',$valsum );
		
		// 通知
		$NoticeM = new NoticeModel();
        $lists = $NoticeM->getLists();
        $logs  = $NoticeM->getLogLists( array('l.user_code'=>$this->ucode),1,9999 );
		$nids  = $ids  = array();
		foreach ( $lists as $val ) {
			$ids[] = $val['notice_id'];
		}
		foreach ( $logs as $val ) {
			$nids[] = $val['notice_id'];
		}
		$this->assign( 'noticecount',count( array_diff($ids,$nids) ) );
		$this->assign( 'nids',$nids );
		
		$this->assign( 'card',array_merge($card,$conf) );
		$this->assign( 'page_title','用户卡' );
		$this->display();
    }
	
	// 消费记录
	public function clog(){
		$this->CardM = new CardModel();
		$where = array('cl.user_code'=>$this->ucode);
		$count = $this->CardM->getCLogCount($where);
		$cloglists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,5);
			//$pagehtml = $Page->show();
			//$this->assign( 'pagehtml',$pagehtml );
			$_REQUEST['p'] = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
			if ( $_REQUEST['p']<ceil($count/5) ) {
				$p = $_REQUEST['p']+1;
			} else {
				$p = 0;
			}
			$this->assign( 'p',$p );
			$this->assign( 'count',$count );

			$cloglists = $this->CardM->getCLogLists($where,$_REQUEST['p'],5); // 用户卡列表
		}
		$this->assign( 'cloglists',$cloglists );
		$this->display();
	}
	
	// 会员卡使用说明
	public function introduction(){
		$CardM = new CardModel();
        $conf = $CardM->getConfig();
		$this->assign( 'card',$conf );
		$this->display();
	}
	
	// 会员信息
	public function edit_cardmem(){
		$CardM = new CardModel();
		if ( $_POST['username'] && $_POST['phone'] ) {
			$where = array( 'user_code' => $this->ucode, );
			$data  = array(
				'member_name' => $_POST['username'],
				'member_tel'  => $_POST['phone'],
				);
			$CardM->updateCardMember($data,$where);
			redirect( U('Card/show_card') );
		}
		$conf = $CardM->getBase($this->ucode);
		$this->assign( 'info',$conf );
		$this->display();
		
	}
	
	// 积分兑换礼品
    public function exchange() {

		// 卡基本信息
		$CardM = new CardModel();
        $card = $CardM->getBase($this->ucode);
		$this->assign( 'card_value',$card['card_value'] );
		
		$gameshow = M('awardintro')->find(1);
        $this->assign('data',$gameshow);
        $this->display();
    }
	
	// 商家信息
    public function business() {

		$BusinessM = new BusinessModel();
        $lists = $BusinessM->getLists();
		//echo json_encode($data);

		$this->assign( 'lists',$lists );
		$this->assign( 'page_title','用户卡' );
		$this->display();
    }
	
	// 商家信息
    public function shop_info() {

		$BusinessM = new BusinessModel();
        $info = $BusinessM->getBase();
		//echo json_encode($data);

		$this->assign( 'info',$info );
		$this->assign( 'page_title','用户卡' );
		$this->display();
    }
	
	// 通知
	function notice_show(){

		$NoticeM = new NoticeModel();
        $lists = $NoticeM->getLists();
        $logs  = $NoticeM->getLogLists( array('user_code'=>$this->ucode),1,9999 );
		$nids  = array();
		foreach ( $logs as $val ) {
			$nids[] = $val['notice_id'];
		}

		$this->assign( 'nids',$nids );
		$this->assign( 'lists',$lists );
		$this->assign( 'page_title','用户卡' );
		$this->display();
	}
	
	function ajaxseek(){

		$NoticeM = new NoticeModel();
		$nid = $_POST['nid'];
		$data = array(
			'notice_id' => intval($nid),
			'user_code' => $this->ucode,
			'add_time'  => time(),
		);
        $re = $NoticeM->addLog($data);
		if ( $re ) echo 1;
		else echo 0;
		die();
	}
	
	function notice_show_only(){

		$NoticeM = new NoticeModel();
        $lists = $NoticeM->getBase();

		$this->assign( 'notice',$lists );
		$this->assign( 'page_title','用户卡' );
		$this->display();
	}

}


