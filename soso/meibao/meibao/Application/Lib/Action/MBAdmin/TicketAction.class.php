<?php
class TicketAction extends BaseAction {

	private $cardM = null;
	
	public function _initialize(){
		parent::_initialize();
	}

	// 获取奖品列表
    public function lists(){

        $lists = M('award')->select();
		$this->assign( 'tickets',$lists );
		$this->display();

    }

	// 编辑奖品
    public function config(){

        $award_id = intval($_REQUEST['award_id']);
    	if ( $_POST ) {
    		$data = array(
                'card_id'     => $_POST['card_id'],
    			'card_name'   => $_POST['card_name'],
    			'descrption'  => $_POST['descrption'],
    			'money'       => $_POST['money'],
    			'credit'      => $_POST['credit'],
    			'update_time' => time(),
    		);
    
            if($_FILES['img']['size'] > 0) { //如果有上传图片
                import('ORG.Net.UploadFile');
		        $upload = new UploadFile();									// 实例化上传类
		        $upload->maxSize	= 3145728 ;								// 设置附件上传大小
		        $upload->allowExts	= array('jpg', 'gif', 'png', 'jpeg');	// 设置附件上传类型
		        $upload->savePath = 'Uploads/award/';					// 设置附件上传目录
                $upload->upload();
	            $file_info 	= $upload->getUploadFileInfo();				// 上传成功 获取上传文件信息
                $data['img'] = C('DOMAIN_URL').$file_info[0]['savepath'].$file_info[0]['savename'];
		    }
            M('award')->where("award_id = $award_id")->save($data);
            redirect(U('Ticket/lists'));
    	}
    	$data = M('award')->find($award_id);
		$this->assign( 'data',$data );
		$this->display();
    }

}
