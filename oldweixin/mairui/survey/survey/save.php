<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	session_start();
	
	require('config.php');
		
	//PHP 5.2.x is having pcre.backtrack_limit defaulted to 100000
	//adjust this limit to the old default 10 million
	//so it won't dies when saving large form
	$pcre_backtrack_limit = ini_get("pcre.backtrack_limit");
	if(!empty($pcre_backtrack_limit)){
		ini_set("pcre.backtrack_limit","10000000");
	}
		
	require('includes/check-session.php');
	require('includes/db-core.php');
	require('includes/db-functions.php');
	require('includes/filter-functions.php');
	require('includes/common-validator.php');
	require('includes/JSON.php');

	
	/* Variables */
	$element_child_lookup['address'] 	 = 5;
	$element_child_lookup['simple_name'] = 1;
	$element_child_lookup['name'] 		 = 3;
	
	$comment_desc['text'] 		= 'Single Line Text';
	$comment_desc['phone'] 		= 'Phone';
	$comment_desc['simple_phone'] = 'Phone';
	$comment_desc['url'] 		= 'Web Site';
	$comment_desc['email'] 		= 'Email';
	$comment_desc['file'] 		= 'File Upload';
	$comment_desc['textarea'] 	= 'Paragraph Text';
	$comment_desc['radio'] 		= 'Multiple Choice';
	$comment_desc['select'] 	= 'Drop Down';
	$comment_desc['time'] 		= 'Time';
	$comment_desc['date'] 		= 'Date';
	$comment_desc['europe_date'] = 'Europe Date';
	$comment_desc['money'] 		 = 'Price';
	$comment_desc['number'] 	 = 'Number';
	$comment_desc['simple_name'] = 'Normal Name';
	$comment_desc['name'] 		 = 'Extended Name';
	$comment_desc['address'] 	 = 'Address';
	$comment_desc['checkbox'] 	 = 'Checkbox';
	
	
	connect_db();
	
	$input_array['form'] 	 = $_REQUEST['form'];
	$input_array['elements'] = $_REQUEST['elements'];
	
	$input_array = ap_sanitize_input($input_array);	//filter from slashes if any
		
	$json = new Services_JSON(); //parse form data, decode from JSON format
	
	$form_object 	   = $json->decode($input_array['form']);
	$elements_object   = $json->decode($input_array['elements']); 
	
	$form_id = $form_object->id;
	
	/******** Start Form Section ********************************************************************/
	//check for form_id, if exist this is an update operation
	//if not this is an insert operation
	$query 	= "select count(form_id) form_exist from ap_forms where form_id='$form_id'";
	$result = do_query($query);
	$row 	= do_fetch_result($result);
	
	if(empty($row['form_exist'])){
		$is_new_form = true;
	}else{
		$is_new_form = false;
	}
	
	$form_input['form_id'] 			    = $form_id;
	$form_input['form_name'] 			= $form_object->name;
	$form_input['form_description'] 	= $form_object->description;
	$form_input['form_redirect'] 		= $form_object->redirect;
	$form_input['form_success_message'] = $form_object->success_message;
	$form_input['form_password'] 		= $form_object->password;
	$form_input['form_unique_ip'] 		= $form_object->unique_ip;
	$form_input['form_captcha'] 		= $form_object->captcha;
	$form_input['form_review'] 			= $form_object->review;
	$form_input['form_frame_height'] 	= $form_object->frame_height;
		
	if($is_new_form){ //this is a new form, insert new form data
		$result = ap_forms_insert($form_input);
		check_result($result);
		
		$form_id = mysql_insert_id();
		
		//create new table for this form
		$query = "CREATE TABLE `ap_form_{$form_id}` (
  													`id` int(11) NOT NULL auto_increment,
  													`date_created` datetime NOT NULL default '0000-00-00 00:00:00',
  													`date_updated` datetime default NULL,
  													`ip_address` varchar(15) default NULL,
  													PRIMARY KEY (`id`)
  													) DEFAULT CHARACTER SET utf8;";
		do_query($query);
		
		//create data folder for this form
		if(is_writable(DATA_DIR)){
			
			$old_mask = umask(0);
			mkdir(DATA_DIR."/form_{$form_id}",0777);
			mkdir(DATA_DIR."/form_{$form_id}/css",0777);
			if(DATA_DIR != UPLOAD_DIR){
				mkdir(UPLOAD_DIR."/form_{$form_id}",0777);
			}
			mkdir(UPLOAD_DIR."/form_{$form_id}/files",0777);
						
			umask($old_mask);
			
			//copy default view.css to css folder
			if(copy("./view.css",DATA_DIR."/form_{$form_id}/css/view.css")){
				//on success update 'form_has_css' field on ap_forms table
				$form_update_input['form_has_css'] = 1;
				ap_forms_update($form_id,$form_update_input);
			}
		}
	}else{ //this is just an update, update form info
		$result = ap_forms_update($form_id,$form_input);		
		check_result($result);
	}
	/******** End Form Section ********************************************************************/
	
		
	/******** Start Elements Section ********************************************************************/
	$elements_array = $elements_object->elements;
	
	//separate optionable elements (checkbox/radio button/dropdown) from other elements
	$optionable_elements = array();
	
	foreach ($elements_array as $key=>$value){
		if(($value->type == 'radio') || ($value->type == 'checkbox') || ($value->type == 'select')){
			$optionable_elements[] = $value;
		}else{
			$value->options = null; //remove options for elements other than checkbox/radio/dropdown 
			$non_optionable_elements[] = $value;
		}
	}
	
	//1. Process non optionable elements
	if(!empty($non_optionable_elements)){
		foreach ($non_optionable_elements as $element){
			if(empty($element->is_db_live)){
				$new_non_optionable_elements[] = $element;
			}else{
				$old_non_optionable_elements[] = $element;
			}
		}
	}
	//1.1 Process new non-optionable element. Insert to table
	
	//get new element_id 
	$query 	= "select ifnull(max(element_id),0)+1 next_element_id from ap_form_elements where form_id='{$form_id}'";
	$result = do_query($query);
	$row 	= do_fetch_result($result);
	
	$next_element_id = $row['next_element_id'];
	
	$element_input 			  = array();
	$element_input['form_id'] = $form_id; 
	
	if(!empty($new_non_optionable_elements)){
		foreach ($new_non_optionable_elements as $element){
			$element_input['element_id'] 			= $next_element_id; 
			$element_input['element_title'] 		= $element->title;
			$element_input['element_guidelines'] 	= $element->guidelines;
			$element_input['element_size'] 			= $element->size; 
			$element_input['element_is_required'] 	= $element->is_required; 
			$element_input['element_is_unique'] 	= $element->is_unique;
			$element_input['element_is_private'] 	= $element->is_private; 
			$element_input['element_type'] 			= $element->type;
			$element_input['element_position'] 		= $element->position;
			$element_input['element_default_value'] = $element->default_value;
			$element_input['element_constraint'] 	= $element->constraint;
			
			if(empty($element_child_lookup[$element->type])){
				$element_input['element_total_child'] = 0;
			}else{
				$element_input['element_total_child'] = $element_child_lookup[$element->type];
			}
			
			ap_form_elements_insert($element_input); //insert field information
			table_add_field($form_id,$next_element_id,$element->type); //actually create the field
			
			$next_element_id++;
		}
	}
	
	//1.2 Process old non-optionable element. Update table
	
	
	$element_input 	= array();
	$updatable_element_type = array('phone','simple_phone','date','europe_date');
		
	if(!empty($old_non_optionable_elements)){
		foreach ($old_non_optionable_elements as $element){
			$element_input['element_title'] 		= $element->title;
			$element_input['element_guidelines'] 	= $element->guidelines;
			$element_input['element_size'] 			= $element->size; 
			$element_input['element_is_required'] 	= $element->is_required; 
			$element_input['element_is_unique'] 	= $element->is_unique;
			$element_input['element_is_private'] 	= $element->is_private; 
			$element_input['element_position'] 		= $element->position;
			$element_input['element_default_value'] = $element->default_value;
			$element_input['element_constraint'] 	= $element->constraint;
			
			if(empty($element_child_lookup[$element->type])){
				$element_input['element_total_child'] = 0;
			}else{
				$element_input['element_total_child'] = $element_child_lookup[$element->type];
			}
			
			//special for phone,simple_phone,date,europe_date .. type can be changed
			if(in_array($element->type,$updatable_element_type)){
				$element_input['element_type'] 		= $element->type;
			}else{
				unset($element_input['element_type']);
			} 
			
			ap_form_elements_update($form_id,$element->id,$element_input);
			
		}
	}
	
	
	
	//2. Process optionable elements (checkbox/radio button/dropdown)
	foreach ($optionable_elements as $element){
		if(empty($element->is_db_live)){
			$new_optionable_elements[] = $element;
		}else{
			$old_optionable_elements[] = $element;
		}
	}
	
	//2.1 Process new optionable element. Insert to table
	$element_input 			  = array();
	$element_input['form_id'] = $form_id; 
	
	if(!empty($new_optionable_elements)){
		foreach ($new_optionable_elements as $element){
			$element_input['element_id'] 			= $next_element_id; 
			$element_input['element_title'] 		= $element->title;
			$element_input['element_guidelines'] 	= $element->guidelines;
			$element_input['element_size'] 			= $element->size; 
			$element_input['element_is_required'] 	= $element->is_required; 
			$element_input['element_is_unique'] 	= $element->is_unique;
			$element_input['element_is_private'] 	= $element->is_private; 
			$element_input['element_type'] 			= $element->type;
			$element_input['element_position'] 		= $element->position;
			$element_input['element_default_value'] = $element->default_value;
			$element_input['element_constraint'] 	= $element->constraint;
			
			if(empty($element_child_lookup[$element->type])){
				$element_input['element_total_child'] = 0;
			}else{
				$element_input['element_total_child'] = $element_child_lookup[$element->type];
			}
			ap_form_elements_insert($element_input);
			
			if(($element->type == 'radio') || ($element->type == 'select')){ //radio button and select only need one field total,,while checkboxes need one field per option
				table_add_field($form_id,$next_element_id,$element->type); //actually create the field
			}
			
			//insert options into ap_element_options table
			//get new option_id 
			$query 	= "select ifnull(max(option_id),0)+1 next_option_id from ap_element_options where form_id='{$form_id}' and element_id='{$next_element_id}'";
			$result = do_query($query);
			$row 	= do_fetch_result($result);
		
			$next_option_id = $row['next_option_id'];
			
			$option_input = array();
			$option_input['form_id']    = $form_id;
			$option_input['element_id'] = $next_element_id;
			$position = 1;
			foreach ($element->options as $option){
				$option->option = trim($option->option);
				if(empty($option->option) && ($option->option != '0')){
					continue;
				}
				$option_input['option_id']  		= $next_option_id;
				$option_input['option']				= $option->option;
				$option_input['option_is_default']	= $option->is_default;
				$option_input['position']			= $position;
				ap_element_options_insert($option_input);
				
				if($element->type == 'checkbox'){
					table_add_field($form_id,$next_element_id,$element->type,$next_option_id); //actually create the field for checkbox
				}
				
				$next_option_id++;
				$position++;
			}
			
			//update 'element_total_child' on ap_form_elements
			$position -= 2;
			do_query("update ap_form_elements set element_total_child='$position' where form_id='$form_id' and element_id='$next_element_id'");
			
			$next_element_id++;
		}
	}
	
	
		
	//2.2 Process old optionable element. Update table
	$element_input 	= array();
		
	if(!empty($old_optionable_elements)){
		foreach ($old_optionable_elements as $element){
			//update ap_forms_element table
			$element_input['element_title'] 		= $element->title;
			$element_input['element_guidelines'] 	= $element->guidelines;
			$element_input['element_size'] 			= $element->size; 
			$element_input['element_is_required'] 	= $element->is_required; 
			$element_input['element_is_unique'] 	= $element->is_unique;
			$element_input['element_is_private'] 	= $element->is_private; 
			$element_input['element_position'] 		= $element->position;
			$element_input['element_default_value'] = $element->default_value;
			$element_input['element_constraint'] 	= $element->constraint;
			
			if(empty($element_child_lookup[$element->type])){
				$element_input['element_total_child'] = 0;
			}else{
				$element_input['element_total_child'] = $element_child_lookup[$element->type];
			}
		
			ap_form_elements_update($form_id,$element->id,$element_input);
			
			//update ap_element_options table (checkbox/radio button/dropdown)
			//'erase' all old data
			$query = "update `ap_element_options` set `live`=0 where form_id='{$form_id}' and element_id='{$element->id}'";
			do_query($query);
			
			
			//get new option_id for current element_id, just in case we need it to insert new option
			$query 	= "select ifnull(max(option_id),0)+1 next_option_id from ap_element_options where form_id='{$form_id}' and element_id='{$element->id}'";
			$result = do_query($query);
			$row 	= do_fetch_result($result);
		
			$next_option_id = $row['next_option_id'];
			
			$option_input = array();
			$option_input['form_id'] = $form_id;
			$option_input['element_id'] = $element->id;
			
			//loop through elements
			$position = 1;
			foreach ($element->options as $option){
				$option->option = trim($option->option);
				if(empty($option->option) && ($option->option != '0')){
					continue;
				}
				
				$option_input['option']				= $option->option;
				$option_input['option_is_default']	= $option->is_default;
				$option_input['live']				= 1;
				$option_input['position']			= $position;
					
				if(empty($option->id)){ //if id=0 insert new row
					$option_input['option_id']  	= $next_option_id;
					
					ap_element_options_insert($option_input);
					
					if($element->type == 'checkbox'){
						table_add_field($form_id,$element->id,$element->type,$next_option_id); //actually create the field for checkbox
					}
					
					$next_option_id++;
				}else{//if id !=0 update
					unset($option_input['option_id']);
					ap_element_options_update($form_id,$element->id,$option->id,$option_input);					
				}
				$position++;
			}
			
			//update 'element_total_child' on ap_form_elements
			$position -= 2;
			do_query("update ap_form_elements set element_total_child='$position' where form_id='$form_id' and element_id='{$element->id}'");
		}
	}
				
	/******** End Elements Section **********************************************************************/
	
	/******** Start processing review table **********************************************************/
	//delete review table if exists
	do_query("DROP TABLE IF EXISTS `ap_form_{$form_id}_review`");
	
	//if review is enabled, create the table
	if(!empty($form_input['form_review'])){
		do_query("CREATE TABLE `ap_form_{$form_id}_review` like `ap_form_{$form_id}`");
		
		do_query("ALTER TABLE `ap_form_{$form_id}_review` ADD COLUMN `session_id` varchar(100) NULL");
	}
	
	/******** End processing review table **********************************************************/
	
		
	$_SESSION['AP_SUCCESS']['title'] = 'Success';
	$_SESSION['AP_SUCCESS']['desc']  = 'Your form has been saved.';
	
	echo '{ "status" : "ok", "message" : "'.$form_id.'" }';
	
	/** Functions *************************/
	
	function check_result($result){
		if($result !== true){
			if(!is_array($result)){ //if one line error message
				$error = '{ "status" : "error","message" : "'.$result.'"}';
				echo $error;
			}
		}
	}
	
	
	

	//add fields to the specified form table
	function table_add_field($form_id,$element_id,$type,$option_id=0){
		global $comment_desc;
		
		$comment = @$comment_desc[$type];
			
		if(('text' == $type) || ('phone' == $type) || ('simple_phone' == $type) || ('url' == $type) || ('email' == $type) || ('file' == $type)){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}` text NULL COMMENT '{$comment}';";
			do_query($query);
		}elseif ('textarea' == $type){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}` mediumtext NULL COMMENT '{$comment}';";
			do_query($query);
		}elseif (('radio' == $type) || ('select' == $type)){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '{$comment}';";
			do_query($query);
		}elseif ('time' == $type){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}` time NULL COMMENT '{$comment}';";
			do_query($query);
		}elseif (('date' == $type) || ('europe_date' == $type)){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}` date NULL COMMENT '{$comment}';";
			do_query($query);
		}elseif ('money' == $type){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}` decimal(62,2) NULL COMMENT '{$comment}';";
			do_query($query);
		}elseif ('number' == $type){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}` double NULL COMMENT '{$comment}';";
			do_query($query);
		}elseif ('simple_name' == $type){
			//add two field, first and last name
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}_1` varchar(255) NULL COMMENT '{$comment} - First', ADD COLUMN `element_{$element_id}_2` varchar(255) NULL COMMENT '{$comment} - Last';";
			do_query($query);
		}elseif ('name' == $type){
			//add four field, title, first, last, suffix 
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}_1` varchar(255) NULL COMMENT '{$comment} - Title', ADD COLUMN `element_{$element_id}_2` varchar(255) NULL COMMENT '{$comment} - First', ADD COLUMN `element_{$element_id}_3` varchar(255) NULL COMMENT '{$comment} - Last', ADD COLUMN `element_{$element_id}_4` varchar(255) NULL COMMENT '{$comment} - Suffix';";
			do_query($query);
		}elseif ('address' == $type){
			//add six field
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}_1` varchar(255) NULL COMMENT '{$comment} - Street', ADD COLUMN `element_{$element_id}_2` varchar(255) NULL COMMENT '{$comment} - Line 2', ADD COLUMN `element_{$element_id}_3` varchar(255) NULL COMMENT '{$comment} - City', ADD COLUMN `element_{$element_id}_4` varchar(255) NULL COMMENT '{$comment} - State/Province/Region', ADD COLUMN `element_{$element_id}_5` varchar(255) NULL COMMENT '{$comment} - Zip/Postal Code', ADD COLUMN `element_{$element_id}_6` varchar(255) NULL COMMENT '{$comment} - Country';";
			do_query($query);
		}elseif ('checkbox' == $type){
			$query = "ALTER TABLE `ap_form_{$form_id}` ADD COLUMN `element_{$element_id}_{$option_id}` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '{$comment} - {$option_id}';";
			do_query($query);
		}
			
	}
?>
