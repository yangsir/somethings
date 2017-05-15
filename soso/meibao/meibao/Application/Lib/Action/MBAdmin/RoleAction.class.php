<?php
class RoleAction extends BaseAction {

	public function _initialize(){
		parent::_initialize();
	}

	// 角色列表
    public function lists() {
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['username'] = array( 'like',"%{$keyword}%" );
		}

        $count = D('Role')->getCount($where);

		$lists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = D('Role')->getLists($where,$_REQUEST['p'],20); // 用户卡列表
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

    //添加编辑角色
    public function config(){

        $admin_id = $_REQUEST['admin_id'];

        //菜单列表
        $menuArr = D('menu')->field('menu_id,name')->select();
        if($admin_id) {
            $data = D('Role')->find($admin_id);
            $this->assign('data',$data);
        } 

        $this->assign('menuArr',$menuArr);
		$this->display();

    }

    //添加编辑角色处理
    public function doconfig(){

        $del      = $_REQUEST['del'];
        $admin_id = $_REQUEST['admin_id'];
        if(!$_POST && !$del) exit();

        if(!$del) { //添加编辑
            $menu_ids = implode(',',$_POST['menu_id']);
            $username = trim($_POST['username']);
            $issuper  = intval($_POST['issuper']);
            if(!$menu_ids && $issuper==0) $this->alert('请选择权限');

            $data = array(
                'store'       => $_POST['store'],
                'nickname'    => $_POST['nickname'],
                'phone'       => $_POST['phone'],
                'menu_ids'    => $menu_ids,
                'issuper'     => $issuper,
                'update_time' => time(),
            );

            if($admin_id) {
                if(trim($_POST['pwd']) != trim($_POST['oldpwd'])) $data['pwd'] = md5(trim($_POST['pwd']));
                if($username != trim($_POST['oldusername'])) {
                    $data['username'] = $username;
                    $count = D('Role')->where("username = '{$data['username']}'")->count();
                    if($count) $this->alert('该用户名被占用');
                }

                D('Role')->where("admin_id = $admin_id")->save($data);
            } else {
                $count = D('Role')->where("username = '$username'")->count();
                if($count) $this->alert('该用户名被占用');

                $data['username'] = $username;
                $data['add_time'] = time();
                $data['pwd']      = md5(trim($_POST['pwd']));
                D('Role')->add($data);
            }
        } else {
            D('Role')->where("admin_id = $admin_id")->delete();
        }

        redirect(U('Role/lists'));
    }

	
}
