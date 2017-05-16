<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	//check if user logged in or not
	//if not redirect them into login page
	if(empty($_SESSION['logged_in'])){
		if(!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')){
			$ssl_suffix = 's';
		}
		
		$current_dir = dirname($_SERVER['PHP_SELF']);
      	if($current_dir == "/" || $current_dir == "\\"){
			$current_dir = '';
		}
		
		$_SESSION['AP_LOGIN_ERROR'] = 'Sorry, you must be logged in to do that.';
		header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].$current_dir.'/index.php?from='.base64_encode($_SERVER['REQUEST_URI']));
		exit;
	}
	
?>
