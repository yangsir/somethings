<?php
class CardAction extends BaseAction {

	private $cardM = null;
	
	public function _initialize(){
		parent::_initialize();
		
		$this->cardM = new CardModel();
	}

	// 列表
    public function lists(){
		$this->cardM = new CardModel();
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['wxu.nickname'] = array( 'like',"%{$keyword}%" );
		}

		$count = $this->cardM->getCount($where);
		$lists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = $this->cardM->getLists($where,$_REQUEST['p'],20); // 用户卡列表
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }
	
	// 编辑
    public function edit(){
		if ( $_POST ) {
			if ( $_POST['cid'] && $_POST['card_value'] ) {
				$data = array(
					'm.card_value' => $_POST['card_value'],
				);
				$where = array(
					'm.card_member_id' => $_POST['cid'],
				);
				$this->cardM->updeBase($data,$where);
				redirect( U('Card/lists') );
			} else {
				$this->alert('参数错误');
			}
		}
		$data = $this->cardM->getBaseNew( array('m.card_member_id'=>$_GET['cid']) );
		$this->assign( 'data',$data );
		$this->display();
	}

	// 配置
    public function config(){
    	if ( $_POST ) {
    		$data = array(
    			'card_config_id' => $_POST['cardid'],
    			'card_name'    => $_POST['name'],
    			'introduce'    => $_POST['info'],
    			'addr'         => $_POST['addre'],
    			'tel'          => $_POST['telphone'],
    			'site_url'     => $_POST['url'],
    			'discount'     => $_POST['discount'],
    			'privilege'    => $_POST['privilege'],
    			'update_time'  => time(),
    			);
    		if($_FILES['img']['size'] > 0) { //如果有上传图片
                import('ORG.Net.UploadFile');
		        $upload = new UploadFile();									// 实例化上传类
		        $upload->maxSize	= 3145728 ;								// 设置附件上传大小
		        $upload->allowExts	= array('jpg', 'gif', 'png', 'jpeg');	// 设置附件上传类型
		        $upload->savePath = 'Uploads/Others/';					// 设置附件上传目录
                $upload->upload();
	            $file_info 	= $upload->getUploadFileInfo();				// 上传成功 获取上传文件信息
                $data['bgurl'] = C('DOMAIN_URL').$file_info[0]['savepath'].$file_info[0]['savename'];
		    }
    		$lists = $this->cardM->updateCard($data); // 用户卡配置信息
    	}
    	$data = $this->cardM->getConfig();
		$this->assign( 'data',$data );
		$this->display();
    }
	
	function export_clog(){
        $list = $this->cardM->getCLogLists(array(),$_REQUEST['p'],5000); // 会员卡列表

        $title_data = array (
            'cart_no'=>'卡号',
            'nickname'=>'微信名',
            //'member_name'=>'姓名',
            //'member_tel'=>'联系电话',
            'card_name'=>'姓名',
            'card_tel'=>'联系电话',
            'mem_time'=>'领卡时间',
			
            'card_value'=>'剩余积分',
            'cvalue'=>'已兑换积分',
            'discount'=>'会员卡特权',
            'add_time'=>'消费时间',
            'cdeduction'=>'消费金额',
            'username'=>'店员',
        );
        $newList = array();
        $tempKey=array_keys($title_data);
        foreach($list as $key=>$listV){
            //$tempList=array();
            foreach($tempKey as $tempKeyV){
				if ($tempKeyV== 'add_time' )  $tempList[$tempKeyV] = date('Y-m-d H:i:s',$listV[$tempKeyV]);
				elseif ($tempKeyV== 'mem_time' )  $tempList[$tempKeyV] = date('Y-m-d H:i:s',$listV[$tempKeyV]);
                else $tempList[$tempKeyV] = $listV[$tempKeyV]?$listV[$tempKeyV]:'';                
            }
            $newList[]=$tempList;
            $tempList=array();
        }
		
		$this->export_exl( array_values($title_data),$newList,'消费记录列表' );
	}
	
	function export(){
        $list = $this->cardM->getLists(array(),$_REQUEST['p'],5000); // 会员卡列表

        $title_data = array (
            'cart_no'=>'卡号',
            'nickname'=>'微信名',
            'member_name'=>'姓名',
            'member_tel'=>'联系电话',
            'add_time'=>'领卡时间',
			
        );
        $newList = array();
        $tempKey=array_keys($title_data);
        foreach($list as $key=>$listV){
            //$tempList=array();
            foreach($tempKey as $tempKeyV){
				if ($tempKeyV== 'serial' )  $tempList[$tempKeyV] = $key+1;
				elseif ($tempKeyV== 'add_time' )  $tempList[$tempKeyV] = date('Y-m-d H:i:s',$listV[$tempKeyV]);
                else $tempList[$tempKeyV] = $listV[$tempKeyV]?$listV[$tempKeyV]:'';                
            }
            $newList[]=$tempList;
            $tempList=array();
        }
		
		$this->export_exl( array_values($title_data),$newList,'会员卡列表' );
	}

	
	// 消费记录列表
    public function cloglists(){
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['wxu.nickname'] = array( 'like',"%{$keyword}%" );
		}

		$count = $this->cardM->getCLogCount($where);
		$lists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = $this->cardM->getCLogLists($where,$_REQUEST['p'],20); // 用户卡列表
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

	// 新增消费记录
    public function clogadd(){
    	if ( $_POST ) {
			$cart_no     = trim($_POST['cart_no']);
			$cvalue      = intval($_POST['cvalue']);
			$cdeduction  = intval($_POST['cdeduction']);
			$cardinfo = $this->cardM->getBaseByNo($cart_no);
			if ( !$cardinfo ) {
				$this->alert('卡号输入错误');
			}
			if ( $cardinfo['card_value']<$cvalue ) {
				$this->alert('积分不足');
			}
			if ( !$cdeduction ) {
				$this->alert('消费金额不能为空');
			}
			//if ( $cvalue > $cdeduction ) {
			//	$this->alert('抵扣积分不能大于消费金额');
			//}
    		$data = array(
    			'card_member_id' => $cardinfo['card_member_id'],
    			'user_code'   => $cardinfo['user_code'],
    			'cvalue'      => $cvalue,
    			'cdeduction'  => $cdeduction,
    			'discount'    => $_POST['discount'],
    			'operate_uid' => $_SESSION['logininfo']['admin_id'],
    			'card_name'   => $_POST['card_name'],
    			'card_tel'    => $_POST['card_tel'],
    			'productnumber' => $_POST['productnumber'],
    			'add_time'      => time(),
    			);
    		
    		$re = $this->cardM->addCLog($data); // 用户卡配置信息
			redirect( U('Card/cloglists') );
    	} else {	
            $card_config = M('card_config')->field('discount')->find(1);
		    $this->assign( 'discount',$card_config['discount'] );

			$this->assign( 'data',$data );
			$this->display();
		}
    }
}
