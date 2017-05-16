<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	session_start();

	require('config.php');
	require('includes/check-session.php');
	require('includes/db-core.php');
	require('includes/db-functions.php');
	require('includes/helper-functions.php');
	require('includes/entry-functions.php');
	require('lib/class.phpmailer.php');
	
	connect_db();
	
	$form_id  = (int) trim($_REQUEST['form_id']);
	$entry_id = (int) trim($_REQUEST['entry_id']);
		
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	
		
	if (empty($action)) {
	
		// Send back the HTML form
		echo "<div style='display:none'>
			<a href='#' title='Close' class='modalCloseX modalClose'>x</a>
			<div class='contact-top'></div>
			<div class='contact-content'>
				<h1 class='contact-title'>Email Entry #{$entry_id} to:</h1>
				<div class='contact-loading' style='display:none'></div>
				<div class='contact-message' style='display:none'></div>
				<form action='#' style='display:none'>
					<input type='text' id='contact-email' class='contact-input' name='email' tabindex='1003' /><br/>
					Use commas to separate email addresses<br/><br />
					<button type='submit' class='contact-send contact-button' tabindex='1004' style='float: none'>Send</button>
					<button type='submit' class='contact-cancel contact-button modalClose' tabindex='1005' style='float: none'>Cancel</button>
					<br/>
					<input type='hidden' name='form_id' value='{$form_id}' />
					<input type='hidden' name='entry_id' value='{$entry_id}' />
				</form>
			</div>
			<div class='contact-bottom'></div>
		</div>";
	}elseif ($action == 'send') {
		
		$to_emails = trim($_REQUEST['email']); 
		
		//get form properties data
		$query 	= "select 
						 esl_from_name,
						 esl_from_email_address,
						 esl_subject,
						 esl_content,
						 esl_plain_text
					 from 
				     	 `ap_forms` 
				    where 
				    	 form_id='$form_id'";
		
		$result = do_query($query);
		$row 	= do_fetch_result($result);
				
		$esl_from_name 	= $row['esl_from_name'];
		$esl_from_email_address = $row['esl_from_email_address'];
		$esl_subject 	= $row['esl_subject'];
		$esl_content 	= $row['esl_content'];
		$esl_plain_text	= $row['esl_plain_text'];
		
		//get parameters for the email
						
		//from name
		if(!empty($esl_from_name)){
			$admin_email_param['from_name'] = $esl_from_name;
		}elseif (NOTIFICATION_MAIL_FROM_NAME != ''){
			$admin_email_param['from_name'] = NOTIFICATION_MAIL_FROM_NAME;
		}else{
			$admin_email_param['from_name'] = 'MachForm';
		}
			
		//from email address
		if(!empty($esl_from_email_address)){
			if(is_numeric($esl_from_email_address)){
				$admin_email_param['from_email'] = '{element_'.$esl_from_email_address.'}';
			}else{
				$admin_email_param['from_email'] = $esl_from_email_address;
			}
		}elseif(NOTIFICATION_MAIL_FROM != ''){
			$admin_email_param['from_email'] = NOTIFICATION_MAIL_FROM;
		}else{
			$domain = str_replace('www.','',$_SERVER['SERVER_NAME']);
			$admin_email_param['from_email'] = "no-reply@{$domain}";
		}
			
		//subject
		if(!empty($esl_subject)){
			$admin_email_param['subject'] = $esl_subject;
		}elseif (NOTIFICATION_MAIL_SUBJECT != ''){
			$admin_email_param['subject'] = NOTIFICATION_MAIL_SUBJECT;
		}else{
			$admin_email_param['subject'] = '{form_name} [#{entry_no}]';
		}
			
		//content
		if(!empty($esl_content)){
			$admin_email_param['content'] = $esl_content;
		}else{
			$admin_email_param['content'] = '{entry_data}';
		}
			
		$admin_email_param['as_plain_text'] = $esl_plain_text;
		$admin_email_param['target_is_admin'] = true; 
			
		send_notification($form_id,$entry_id,$to_emails,$admin_email_param);
		
		echo "Entry #{$entry_id} successfully sent.";
	}
	
	


?>
