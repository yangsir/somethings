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
	
	connect_db();
	
	if(empty($form_id) || empty($element_id)){
		die('{ "status" : "error", "message" : "invalid parameter" }');
	}
	
	//get type of this element
	$query 	= "select element_type from `ap_form_elements` where form_id='$form_id' and element_id='$element_id'";
	$result = do_query($query);
	$row 	= do_fetch_result($result);
	$element_type = $row['element_type'];
	$col_prefs = array();
	
	//delete actual field on respective table data
	if('address' == $element_type){
		$query = "ALTER TABLE `ap_form_{$form_id}` DROP COLUMN `element_{$element_id}_1`,DROP COLUMN `element_{$element_id}_2`, DROP COLUMN `element_{$element_id}_3`, DROP COLUMN `element_{$element_id}_4`, DROP COLUMN `element_{$element_id}_5`, DROP COLUMN `element_{$element_id}_6`;";
		do_query($query);
		
		$col_prefs = array("element_{$element_id}_1","element_{$element_id}_2","element_{$element_id}_3","element_{$element_id}_4","element_{$element_id}_5","element_{$element_id}_6");
	}elseif ('simple_name' == $element_type){
		$query = "ALTER TABLE `ap_form_{$form_id}` DROP COLUMN `element_{$element_id}_1`,DROP COLUMN `element_{$element_id}_2`;";
		do_query($query);
		
		$col_prefs = array("element_{$element_id}_1","element_{$element_id}_2");
	}elseif ('name' == $element_type){
		$query = "ALTER TABLE `ap_form_{$form_id}` DROP COLUMN `element_{$element_id}_1`,DROP COLUMN `element_{$element_id}_2`, DROP COLUMN `element_{$element_id}_3`, DROP COLUMN `element_{$element_id}_4`;";
		do_query($query);
		
		$col_prefs = array("element_{$element_id}_1","element_{$element_id}_2","element_{$element_id}_3","element_{$element_id}_4");
	}elseif ('checkbox' == $element_type){
		
		//get option_id list
		$query = "select option_id from ap_element_options where form_id='{$form_id}' and element_id='{$element_id}' and live=1";
		$result = do_query($query);
		
		$option_id_array = array();
		while($row = do_fetch_result($result)){
			$option_id_array[] = $row['option_id'];
		}
		
		//delete each option
		$query = "ALTER TABLE `ap_form_{$form_id}` ";
		foreach ($option_id_array as $option_id){
			$query .= " DROP COLUMN `element_{$element_id}_{$option_id}`,";
			$col_prefs[] = "element_{$element_id}_{$option_id}";
		}
		
		$query = rtrim($query,',');
		do_query($query);
		
	}elseif ('section' == $element_type){
		//do nothing for section break
	}elseif ('file' == $element_type){
		//delete the files first
		$query = "select element_{$element_id} from `ap_form_{$form_id}`";
		$result = do_query($query);
		while($row = do_fetch_result($result)){
			$filename = $row['element_'.$element_id];
			@unlink(UPLOAD_DIR."/form_{$form_id}/files/".$filename);
		}
		
		$query = "ALTER TABLE `ap_form_{$form_id}` DROP COLUMN `element_{$element_id}`;";
		do_query($query);
		
		$col_prefs = array("element_{$element_id}");
	}else{
		$query = "ALTER TABLE `ap_form_{$form_id}` DROP COLUMN `element_{$element_id}`;";
		do_query($query);
		
		$col_prefs = array("element_{$element_id}");
	}
	
	
	//delete on table ap_element_options
	$query = "delete from `ap_element_options` where form_id='$form_id' and element_id='$element_id'";
	do_query($query);
	
	//delete on table ap_form_elements
	$query = "delete from `ap_form_elements` where form_id='$form_id' and element_id='$element_id'";
	do_query($query);
	
	//delete on table ap_column_preferences
	if(!empty($col_prefs)){
		foreach ($col_prefs as $element_name){
			do_query("delete from `ap_column_preferences` where form_id='{$form_id}' and element_name='{$element_name}'");
		}
	}
	
	//if there is no more elements on the table, we need to clear all default fields (id,date_created,date_updated,ip_address) 
	$query = "select * from `ap_form_{$form_id}` limit 1";
	$result = do_query($query);
	if(mysql_num_fields($result) <= 4){
		$query = "truncate `ap_form_{$form_id}`";
		do_query($query);	
	}	
	
	echo '{ "status" : "ok", "message" : "ok" }';
?>
