<?php
class MenuAction extends BaseAction {

	public function _initialize(){
		parent::_initialize();
	}

	// 菜单列表
    public function lists() {
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['name'] = array( 'like',"%{$keyword}%" );
		}

        $count = D('Menu')->getCount($where);

		$lists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = D('Menu')->getLists($where,$_REQUEST['p'],20); // 用户卡列表
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

    //添加编辑菜单
    public function config(){

        $menu_id = $_REQUEST['menu_id'];
        if($menu_id) {
            $data = D('Menu')->find($menu_id);
            $this->assign('data',$data);
        } 
		$this->display();

    }

    //添加编辑菜单处理
    public function doconfig(){

        $del     = $_REQUEST['del'];
        $menu_id = $_REQUEST['menu_id'];
        if(!$_POST && !$del) exit();

        if(!$del) { //添加编辑
            $data = array(
                'name'    => $_POST['name'],
                'menuurl' => $_POST['menuurl'],
                'add_time' => time(),
            );

            if($menu_id) {
                D('Menu')->where("menu_id = $menu_id")->save($data);
            } else {
                D('Menu')->add($data);
            }
        } else {
            D('Menu')->where("menu_id = $menu_id")->delete();
        }

        redirect(U('Menu/lists'));
    }

	
}
