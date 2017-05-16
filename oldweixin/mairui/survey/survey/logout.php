<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/	
	session_start();
	
	$ssl_suffix = '';
	if(!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')){
			$ssl_suffix = 's';
	}
	
	$current_dir = dirname($_SERVER['PHP_SELF']);
    if($current_dir == "/" || $current_dir == "\\"){
		$current_dir = '';
	}
	
	$_SESSION = array();
	header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].$current_dir."/index.php");
	exit;
?>
