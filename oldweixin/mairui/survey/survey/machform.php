<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	$include_path = dirname(__FILE__).'/';

	require($include_path.'config.php');
	require($include_path.'includes/language.php');
	require($include_path.'includes/db-core.php');
	require($include_path.'includes/common-validator.php');
	require($include_path.'includes/view-functions.php');
	require($include_path.'includes/post-functions.php');
	require($include_path.'includes/entry-functions.php');
	require($include_path.'includes/filter-functions.php');
	require($include_path.'includes/helper-functions.php');
	require($include_path.'hooks/custom_hooks.php');		
	require($include_path.'lib/recaptchalib.php');
	require($include_path.'lib/php-captcha/php-captcha.inc.php');
	require($include_path.'lib/class.phpmailer.php');
	
		
	function display_machform($config){
		
		$form_id       = $config['form_id'];
		$machform_path = $config['base_path'];
		$machform_data_path = dirname(__FILE__).'/';
		
		//by default all form submission will redirect using javascript, unless specified otherwise
		if(empty($config['use_header_redirect'])){
			$use_javascript_redirect = true;
		}else {
			$use_javascript_redirect = false;
		}
		
		//start session if there isn't any
		if(session_id() == ""){
			@session_start();
		}
				
		//get data from databae
		connect_db();
		
		if(!empty($_POST['submit'])){ //if normal form submitted
			$input_array   = ap_sanitize_input($_POST);
			
			$input_array['machform_data_path'] = $machform_data_path;
			$submit_result = process_form($input_array);
			
			if(!isset($input_array['password'])){ //if normal form submitted
				if($submit_result['status'] === true){
					if(empty($submit_result['form_redirect'])){
						
						if(strpos($_SERVER['REQUEST_URI'],'?') === false){
							if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$_SERVER['REQUEST_URI']}?done=1'</script>";
							}else{
								header("Location: {$_SERVER['REQUEST_URI']}?done=1");
							}
							exit;
						}else{
							if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$_SERVER['REQUEST_URI']}&done=1'</script>";
							}else{
								header("Location: {$_SERVER['REQUEST_URI']}&done=1");
							}
							exit;
						}
					}else{
						
						if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$submit_result['form_redirect']}'</script>";
						}else{
								header("Location: {$submit_result['form_redirect']}");
						}
						exit;
					}
				}else{
					$old_values = $submit_result['old_values'];
					$custom_error = $submit_result['custom_error'];
					$error_elements = $submit_result['error_elements'];
						
					$markup = display_integrated_form($input_array['form_id'],$old_values,$error_elements,$custom_error,0,$machform_path);
				}
			}else{ //if password form submitted
				if($submit_result['status'] === true){ //on success, display the form
					$markup = display_integrated_form($input_array['form_id'],null,null,'',0,$machform_path);
				}else{
					$custom_error = $submit_result['custom_error']; //error, display the pasword form again
					$markup = display_integrated_form($input_array['form_id'],null,null,$custom_error,0,$machform_path);
				}
			}
		}elseif(!empty($_POST['submit_continue'])){ //if form with continue (review) button submitted
			unset($_POST['submit_continue']);
			
			$input_array   = ap_sanitize_input($_POST);
			
			$input_array['machform_data_path'] = $machform_data_path;
			$submit_result = process_form($input_array);
			
			
			if($submit_result['status'] === true){
								
				if(empty($submit_result['review_id'])){
					if(empty($submit_result['form_redirect'])){
						
						if(strpos($_SERVER['REQUEST_URI'],'?') === false){
							if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$_SERVER['REQUEST_URI']}?done=1'</script>";
							}else{
								header("Location: {$_SERVER['REQUEST_URI']}?done=1");
							}
							exit;
						}else{
							if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$_SERVER['REQUEST_URI']}&done=1'</script>";
							}else{
								header("Location: {$_SERVER['REQUEST_URI']}&done=1");
							}
							exit;
						}	
						
					}else{
								
						if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$submit_result['form_redirect']}'</script>";
						}else{
								header("Location: {$submit_result['form_redirect']}");
						}
						exit;
					}
				}else{ //redirect to review page
				
					$_SESSION['review_id'] = $submit_result['review_id'];
					
					if(strpos($_SERVER['REQUEST_URI'],'?') === false){
						if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$_SERVER['REQUEST_URI']}?show_review=1'</script>";
						}else{
								header("Location: {$_SERVER['REQUEST_URI']}?show_review=1");
						}
					}else{
						if($use_javascript_redirect){
								echo "<script type=\"text/javascript\">top.location = '{$_SERVER['REQUEST_URI']}&show_review=1'</script>";
						}else{
								header("Location: {$_SERVER['REQUEST_URI']}&show_review=1");
						}
					}
							
					exit;
				}
				
			}else{
				$old_values = $submit_result['old_values'];
				$custom_error = $submit_result['custom_error'];
				$error_elements = $submit_result['error_elements'];
					
				$markup = display_integrated_form($input_array['form_id'],$old_values,$error_elements,$custom_error,0,$machform_path);
			}
			
		}elseif(!empty($_POST['review_submit'])){ //if review page submitted
			//commit data from review table to actual table
			$record_id 	   = $_SESSION['review_id'];
			$param['machform_data_path'] = $machform_data_path;
			$commit_result = commit_form_review($form_id,$record_id,$param);
			
			unset($_SESSION['review_id']);
			
			if(empty($commit_result['form_redirect'])){
				$url = str_replace(array("&show_review=1","?show_review=1"),"",$_SERVER['REQUEST_URI']);
				if(strpos($url,'?') === false){
					if($use_javascript_redirect){
						echo "<script type=\"text/javascript\">top.location = '{$url}?done=1'</script>";
					}else{
						header("Location: {$url}?done=1");
					}
				}else{
					if($use_javascript_redirect){
						echo "<script type=\"text/javascript\">top.location = '{$url}&done=1'</script>";
					}else{
						header("Location: {$url}&done=1");
					}
				}
				exit;
			}else{
				if($use_javascript_redirect){
					echo "<script type=\"text/javascript\">top.location = '{$commit_result['form_redirect']}'</script>";
				}else{
					header("Location: {$commit_result['form_redirect']}");
				}
				exit;
			}
		}elseif (!empty($_GET['show_review'])){ //show review page
			if(empty($_SESSION['review_id'])){
				die("Your session has been expired. Please start again.");
			}else{
				$record_id = $_SESSION['review_id'];
			}
			$markup = display_integrated_form_review($form_id,$record_id,$machform_path);
		}elseif (!empty($_POST['review_back'])){ //go back to form
			$url = str_replace(array("&show_review=1","?show_review=1"),"",$_SERVER['REQUEST_URI']);
			
			if($use_javascript_redirect){
				echo "<script type=\"text/javascript\">top.location = '{$url}'</script>";
			}else{
				header("Location: {$url}");
			}
			
			exit;
		}else{
			
			if(empty($form_id)){
				die('ID required.');
			}
			
			//check for delete file option
			//this is available for form with review enabled
			if(!empty($_GET['delete_file']) && !empty($_SESSION['review_id'])){
				$element_id = (int) trim($_GET['delete_file']);
				$param['machform_data_path'] = $machform_data_path;
				delete_review_file_entry($form_id,$_SESSION['review_id'],$element_id,$param);
			}
			
			if(!empty($_GET['done'])){
				$markup = display_integrated_success($form_id,$machform_path);
			}else{
				$markup = display_integrated_form($form_id,null,null,'',0,$machform_path);
			}
		}
		
		echo $markup;
		
	}

?>
