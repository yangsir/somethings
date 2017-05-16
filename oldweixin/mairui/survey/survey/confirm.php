<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	session_start();
	
	require('config.php');
	require('includes/language.php');
	require('includes/db-core.php');
	require('includes/common-validator.php');
	require('includes/view-functions.php');
	require('includes/post-functions.php');
	require('includes/helper-functions.php');
	require('includes/entry-functions.php');
	require('lib/class.phpmailer.php');
		
	//get data from database
	connect_db();
	
	$form_id   = (int) trim($_REQUEST['id']);
	
	if(!empty($_POST['review_submit'])){ //if form submitted
		
		//commit data from review table to actual table
		$record_id 	   = $_SESSION['review_id'];
		$commit_result = commit_form_review($form_id,$record_id);
		
		unset($_SESSION['review_id']);
		
		if(empty($commit_result['form_redirect'])){
			$ssl_suffix = get_ssl_suffix();
			
			header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?id={$form_id}&done=1");
			exit;
		}else{
			
			echo "<script type=\"text/javascript\">top.location.replace('{$commit_result['form_redirect']}')</script>";
			exit;
		}
		
	}elseif (!empty($_POST['review_back'])){ 
		//go back to form
		$ssl_suffix = get_ssl_suffix();
		header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].get_dirname($_SERVER['PHP_SELF'])."/view.php?id={$form_id}");
		exit;
	}else{
				
		if(empty($form_id)){
			die('ID required.');
		}
		
		if(!empty($_GET['done'])){
			$markup = display_success($form_id);
		}else{
			if(empty($_SESSION['review_id'])){
				die("Your session has been expired. Please <a href='view.php?id={$form_id}'>click here</a> to start again.");
			}else{
				$record_id = $_SESSION['review_id'];
			}
			$markup = display_form_review($form_id,$record_id);
		}
	}
	
	header("Content-Type: text/html; charset=UTF-8");
	echo $markup;
	
?>
