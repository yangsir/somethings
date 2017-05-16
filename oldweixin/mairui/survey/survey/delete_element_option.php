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
	
	$form_id 	= (int) trim($_POST['form_id']);
	$element_id = (int) trim($_POST['element_id']);
	$option_id = (int) trim($_POST['option_id']);
	
	connect_db();
	
	if(empty($form_id) || empty($element_id) || empty($option_id)){
		die('{ "status" : "error", "message" : "invalid parameter" }');
	}
	
	//get option info, if checkbox, we need to update element_total_child on ap_form_elements
	$query = "select element_type from `ap_form_elements` where form_id='$form_id' and element_id='$element_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	
	if($row['element_type'] == 'checkbox'){
		//update 'element_total_child' on ap_form_elements
		do_query("update ap_form_elements  set element_total_child=element_total_child-1 where form_id='$form_id' and element_id='$element_id'");
	}
	
		
	//delete on table ap_element_options
	$query = "delete from `ap_element_options` where form_id='$form_id' and element_id='$element_id' and option_id='$option_id'";
	do_query($query);
	
	//delete actual column on table
	$query = "ALTER TABLE `ap_form_{$form_id}` DROP COLUMN `element_{$element_id}_{$option_id}`;";
	do_query($query);
	
	//delete on table ap_column_preferences
	do_query("delete from `ap_column_preferences` where form_id='{$form_id}' and element_name='element_{$element_id}_{$option_id}'");
	
	echo '{ "status" : "ok", "message" : "ok" }';
?>
