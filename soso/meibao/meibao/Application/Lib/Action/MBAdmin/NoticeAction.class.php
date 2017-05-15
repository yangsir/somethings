<?php
class NoticeAction extends BaseAction {

	private $NoticeM = null;
	
	public function _initialize(){
		parent::_initialize();
		
		$this->NoticeM = new NoticeModel();
	}

	// 通知列表
    public function lists(){
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['n.notice_title'] = array( 'like',"%{$keyword}%" );
		}

		$count = $this->NoticeM->getCount($where);
		$lists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = $this->NoticeM->getLists($where,$_REQUEST['p'],20); // 用户卡列表
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

	// 添加通知
    public function add(){
    	if ( $_POST ) {
    		$data = array(
    			'notice_title' => $_POST['title'],
    			'notice_des'   => $_POST['des'],
    			'add_time'     => time(),
    			'end_time'     => strtotime($_POST['end_time']),
    			);
    		$lists = $this->NoticeM->add($data); // 用户卡配置信息
			redirect( U('Notice/lists') );
    	}
		$this->display();
    }

	// 编辑通知
    public function edit(){
    	if ( $_POST['title'] && $_POST['des'] && $_POST['nid'] ) {
    		$data = array(
    			'notice_title' => $_POST['title'],
    			'notice_des'   => $_POST['des'],
    			);
			$this->NoticeM->updateBase($data,array('notice_id'=>$_POST['nid']));
			redirect( U('Notice/lists') );
    	}
		$base = $this->NoticeM->getBase();
		$this->assign( 'data',$base );
		$this->display();
    }

	// 删除
    public function dele(){
		$nid = intval($_GET['nid']);
    	if ( !$nid ) {
			$this->alert('参数错误！');
    	}
		
		
		$info = $this->NoticeM->dele( array('notice_id'=>$nid) );
		redirect( U('Notice/lists') );
    }

}