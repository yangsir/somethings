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
	
	$form_id = trim($_POST['form_id']);
	$operation = trim($_POST['operation']);
	
	if(!empty($form_id)){
		
		connect_db();
		
		if($operation == 'enable'){ //activate this form
			$query = "update `ap_forms` set form_active=1 where form_id='$form_id'";
		}elseif ($operation == 'disable'){ //disable this form
			$query = "update `ap_forms` set form_active=0 where form_id='$form_id'";
		}
		
		do_query($query);
	}
	
	echo $form_id;
?>
