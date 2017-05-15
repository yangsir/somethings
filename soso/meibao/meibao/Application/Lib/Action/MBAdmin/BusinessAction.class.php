<?php
class BusinessAction extends BaseAction {

	private $BusinessM = null;
	
	public function _initialize(){
		parent::_initialize();
		
		$this->BusinessM = new BusinessModel();
	}

	// 通知列表
    public function lists(){
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['shop_title'] = array( 'like',"%{$keyword}%" );
		}

		$count = $this->BusinessM->getCount($where);
		$lists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = $this->BusinessM->getLists($where,$_REQUEST['p'],20); // 用户卡列表
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

	// 添加
    public function add(){
    	if ( $_POST ) {
            if ( !$_POST['title'] || !$_POST['tel'] || !$_POST['addr'] || !$_POST['info'] || !$_POST['shop_jingdu'] || !$_POST['shop_weidu']) 
                $this->alert('信息未填写完整！');

    		$data = array(
    			'shop_title' => $_POST['title'],
    			'shop_tel'   => $_POST['tel'],
    			'shop_addr'  => $_POST['addr'],
    			'shop_info'  => $_POST['info'],
    			'shop_jingdu'  => $_POST['shop_jingdu'],
    			'shop_weidu'   => $_POST['shop_weidu'],
    			'add_time'     => time(),
    			);
    		$lists = $this->BusinessM->add($data);
			redirect( U('Business/lists') );
    	}
		$this->display();
    }

	// 编辑
    public function edit(){
    	if ( $_POST ) {
			if ( !$_POST['title'] || !$_POST['tel'] || !$_POST['addr'] || !$_POST['info'] || !$_POST['shop_jingdu'] || !$_POST['shop_weidu'] || !$_POST['bid'] )
				$this->alert('信息未填写完整！');

    		$data = array(
    			'shop_title' => $_POST['title'],
    			'shop_tel'   => $_POST['tel'],
    			'shop_addr'  => $_POST['addr'],
    			'shop_info'  => $_POST['info'],
                'shop_jingdu'  => $_POST['shop_jingdu'],
    			'shop_weidu'   => $_POST['shop_weidu'],
    			'add_time'     => time(),
    			);
    		$lists = $this->BusinessM->updateBase($data,array('business_shop_id'=>intval($_GET['bid'])));
			redirect( U('Business/lists') );
    	}
		
		if ( !intval($_GET['bid']) ) redirect( U('Index/index') );
		$info = $this->BusinessM->getBase( array('business_shop_id'=>intval($_GET['bid'])) );
		$this->assign( 'data',$info );
		$this->display();
    }

	// 删除
    public function dele(){
		$bid = intval($_GET['bid']);
    	if ( !$bid ) {
			$this->alert('参数错误！');
    	}
		
		
		$info = $this->BusinessM->dele( array('business_shop_id'=>$bid) );
		redirect( U('Business/lists') );
    }

}
