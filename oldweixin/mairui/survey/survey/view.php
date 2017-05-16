<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	header("p3p: CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\"");
	session_start();
	
	require('config.php');
	require('includes/language.php');
	require('includes/db-core.php');
	require('includes/common-validator.php');
	require('includes/view-functions.php');
	require('includes/post-functions.php');
	require('includes/filter-functions.php');
	require('includes/entry-functions.php');
	require('includes/helper-functions.php');
	require('hooks/custom_hooks.php');
	require('lib/class.phpmailer.php');
	require('lib/recaptchalib.php');
	require('lib/php-captcha/php-captcha.inc.php');
		
	//get data from database
	connect_db();
	
	if(!empty($_POST['submit'])){ //if form submitted
		$input_array   = ap_sanitize_input($_POST);
		
		$submit_result = process_form($input_array);
		
		if(!isset($input_array['password'])){ //if normal form submitted
			if($submit_result['status'] === true){
				if(empty($submit_result['review_id'])){
					if(empty($submit_result['form_redirect'])){
						$ssl_suffix = get_ssl_suffix();						
						
						header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?id={$input_array['form_id']}&done=1");
						exit;
					}else{
						echo "<script type=\"text/javascript\">top.location.replace('{$submit_result['form_redirect']}')</script>";
						exit;
					}
				}else{ //redirect to review page
					$ssl_suffix = get_ssl_suffix();	
					
					$_SESSION['review_id'] = $submit_result['review_id'];
					header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].get_dirname($_SERVER['PHP_SELF'])."/confirm.php?id={$input_array['form_id']}");
					exit;
				}
			}else{
				$old_values = $submit_result['old_values'];
				$custom_error = @$submit_result['custom_error'];
				$error_elements = $submit_result['error_elements'];
							
				$markup = display_form($input_array['form_id'],$old_values,$error_elements,$custom_error);
			}
		}else{ //if password form submitted
			if($submit_result['status'] === true){ //on success, display the form
				$markup = display_form($input_array['form_id']);
			}else{
				$custom_error = $submit_result['custom_error']; //error, display the pasword form again
				$markup = display_form($input_array['form_id'],null,null,$custom_error);
			}
		}
	}else{
		$form_id = (int) trim($_GET['id']);
		if(empty($form_id)){
			die('ID required.');
		}
		
		//check for delete file option
		//this is available for form with review enabled
		if(!empty($_GET['delete_file']) && !empty($_SESSION['review_id'])){
			$element_id = (int) trim($_GET['delete_file']);
			delete_review_file_entry($form_id,$_SESSION['review_id'],$element_id);
		}
		
		if(!empty($_GET['done'])){
			$markup = display_success($form_id);
		}else{
			$markup = display_form($form_id);
		}
	}
	
	header("Content-Type: text/html; charset=UTF-8");
	echo $markup;
	
?>
