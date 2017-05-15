<?php
class BaseAction extends Action {
	
	/*
	* 公共初始化操作
	*/
	public function _initialize(){
		//如果已经登录
		if ( ! $_SESSION ["logininfo"] ) {
			redirect( U('Login/index') );
		}
		$tmplace = C('TMPL_ACE');    // 模板样式根目录
        $this->assign( 'menu_action',ACTION_NAME );
        $this->assign( 'menu_countro',MODULE_NAME );
        $this->assign( 'tmplace',$tmplace );
		$this->getMenu();
	}

    //获取菜单
    protected function getMenu() {
        $admin = $_SESSION['logininfo'];
        if($admin['issuper'] == 1) {
            $menuList = D('Menu')->select();
        } else {
            $menuList = D('Menu')->where("menu_id in({$admin['menu_ids']})")->select();
        }
        $this->assign('menuList',$menuList);
    }
	
	function export_exl($title_data,$newList,$title){
		ini_set( 'memory_limit', '1024M' );
		import("ORG.Util.Xls");

        $XLS=new Xls();
        $XLS->printData($title_data,$newList,$title );
	}

    //弹出错误信息
    protected function alert($msg="", $url="", $second=0){
    	if(empty($second)){
    		if(!empty($msg))
    			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><script language="javascript">alert("'.$msg.'");</script>';
    		else
    			header('Location:'.$url);
    			
    		if (!empty($url))
    			echo "<meta http-equiv=\"REFRESH\" content=\"0;URL='".$url."'\">";
    		else
    			echo '<script language="javascript">history.go(-1);</script>';
    	}else {
    		$inf = '<style>body {background-color:#eee}</style><div style="text-align:center"><div style="width:600px; border:5px solid #ddd; margin:50px; padding:0.8em; text-align:left; background-color:white; font-size:1.3em">';
    		$inf .= $msg;
    		$inf .= '<div style="font-size:12px; margin-top:1em; color:gray;">页面正在跳转<a href="'.$url.'" style="color:blue;">这个</a>，请稍后...<b id="left_time">'.$second.'</b> 秒,</div></div></div>';
    		if($_SERVER['SERVER_PORT'] == '443'){
    			echo "<meta http-equiv=\"REFRESH\" content=\"$second;URL='".$url."'\">";
    		}else{
                $inf .= '<script type="text/javascript">var sec = '.$second.'; window.setInterval(cooldown,1000);';
    			$inf .= 'function cooldown(){if(sec>0) sec--; else{window.clearInterval(); window.location.replace("'.$url.'"); }; document.getElementById("left_time").innerHTML = sec;}';
    			$inf .= '</script>';
            }
            echo $inf;
        }
        exit;
    }
}
