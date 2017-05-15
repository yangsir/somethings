<?php
class LoginAction extends BaseAction{
	
	function _initialize(){
		$tmplace = C('TMPL_ACE');    // 模板样式根目录
		$this->assign( 'tmplace',$tmplace );
	}
	
	function index()
	{
		//如果已经登录
		if ( $_SESSION ["logininfo"] ) {
			redirect( U('Credit/lists') );exit();
		}
		$this->display();
	}
	
	function login(){
		
		if ( $_POST ) {
			
			$username = trim ( $_POST ['username'] );
			$password = trim ( $_POST ['password'] );
			if (! $username || ! $password)
				$this->alert ( '用户名或者密码不能为空' );
			
			$password = md5 ( $password );
			$where = array(
				'username' => $username,
				'pwd'      => $password,
			);
            $baseinfo = D('Role')->where($where)->find();
			if (! $baseinfo) {
				$this->alert ( '用户名或者密码错误' );
			}
			
			$_SESSION ['logininfo'] = $baseinfo;
			
			redirect( U('Credit/lists') );
		} else {
			$this->alert ( '参数错误!' );
		}
	}
	
	//退出登录
	function loginOut()
	{
		unset($_SESSION['logininfo']);
		redirect( U('Login/index') );
	}
	
	/**
	 * 修改密码
	 */
	public function modifypwd() {
	
		if($_POST){
			$oldpwd  = trim($_REQUEST['oldpwd']);
			$newpwd  = trim($_REQUEST['newpwd']);
			$cnewpwd = trim($_REQUEST['cnewpwd']);
			if(!$oldpwd || !$newpwd || !$cnewpwd){
				$this->alert('请填写完成信息');
			}
			if($newpwd != $cnewpwd){
				$this->alert('两次输入密码不一致');
			}
	
			$where = array( 'admin_id'=>$_SESSION ['logininfo']['admin_id'] );
            $re = D('Role')->where($where)->find();
			if( $re['pwd']!=md5($oldpwd) ){
				$this->alert('原密码输入错误');
			}
			
			$where = array(
				'admin_id' =>$_SESSION ['logininfo']['admin_id'],
				'pwd'     => md5($newpwd),
			);
			$re = D('Role')->save($where);
			if ( $re ) $this->alert('密码修改成功');
			else $this->alert('密码修改成功');
		}else{
		    $this->getMenu();
			$this->display();
		}
	
	}
	
	/**
	 * for ajax
	 */
	function getUserAjax(){
		$where = array(
				'admin_id' => $_SESSION['logininfo']['admin_id'],
				'pwd' => md5($_REQUEST['pwd']),
		);
		$re = D('Role')->where($where)->find();
		if ( $re ) echo 1;
		else echo '当前密码输入错误';
	}
	
}

?>
