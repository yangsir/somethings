<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	function process_form($input){
		
		$form_id = (int) trim($input['form_id']);
		
		global $lang;
		
		//this function handle password submission and general form submission
		//check for password requirement
		$query = "select form_password from `ap_forms` where form_id='$form_id'";
		$result = do_query($query);
		$row = do_fetch_result($result);
		if(!empty($row['form_password'])){
			$require_password = true;
		}else{
			$require_password = false;
		}
		
		//if this form require password and no session has been set
		if($require_password && (empty($_SESSION['user_authenticated']) || $_SESSION['user_authenticated'] != $form_id)){ 
			
			$query = "select count(form_id) valid_password from `ap_forms` where form_id='{$form_id}' and form_password='{$input['password']}'";
			$result = do_query($query);
			$row = do_fetch_result($result);
			
			if(!empty($row['valid_password'])){
				$process_result['status'] = true;
				$_SESSION['user_authenticated'] = $form_id;
			}else{
				$process_result['status'] = false;
				$process_result['custom_error'] = $lang['form_pass_invalid'];
			}
			
			return $process_result;
		}
		
		
		$element_child_lookup['address'] 	 = 5;
		$element_child_lookup['simple_name'] = 1;
		$element_child_lookup['name'] 		 = 3;
		$element_child_lookup['phone'] 		 = 2;
		$element_child_lookup['date'] 		 = 2;
		$element_child_lookup['europe_date'] = 2;
		$element_child_lookup['time'] 		 = 3;
		$element_child_lookup['money'] 		 = 1; //this applies to dollar,euro and pound. yen don't have child
		$element_child_lookup['checkbox'] 	 = 1; //this is just a dumb value
		
		//never trust user input, get a list of input fields based on info stored on table
		//element has real child -> address, simple_name, name
		//element has virtual child -> phone, date, europe_date, time, money
		
		$query = "select 
						element_id,
       					element_title,
       					element_is_required,
       					element_is_unique,
       					element_type, 
       					element_constraint,
       					element_total_child,
       					element_is_private,
       					element_default_value
					from 
						ap_form_elements 
				   where 
				   		form_id='$form_id' order by element_id asc";
		
		$result = do_query($query);
		
		$element_to_get = array();
		$private_elements = array(); //admin-only fields
		
		while($row = do_fetch_result($result)){
			if($row['element_type'] == 'section'){
				continue;
			}
			//store element info
			$element_info[$row['element_id']]['title'] 			= $row['element_title'];
			$element_info[$row['element_id']]['type'] 			= $row['element_type'];
			$element_info[$row['element_id']]['is_required'] 	= $row['element_is_required'];
			$element_info[$row['element_id']]['is_unique'] 		= $row['element_is_unique'];
			$element_info[$row['element_id']]['is_private'] 	= $row['element_is_private'];
			$element_info[$row['element_id']]['constraint'] 	= $row['element_constraint'];
			$element_info[$row['element_id']]['default_value'] 	= $row['element_default_value'];
			
			//get element form name, complete with the childs
			if(empty($element_child_lookup[$row['element_type']]) || ($row['element_constraint'] == 'yen')){ //elements with no child
				$element_to_get[] = 'element_'.$row['element_id'];
							
			}else{ //elements with child
				if($row['element_type'] != 'checkbox'){
					$max = $element_child_lookup[$row['element_type']] + 1;
					
					for ($j=1;$j<=$max;$j++){
						$element_to_get[] = "element_{$row['element_id']}_{$j}";
					}
				}else{ 
					//for checkbox, get childs elements from ap_element_options table 
					$sub_query = "select 
										option_id 
									from 
										ap_element_options 
								   where 
								   		form_id='{$form_id}' and element_id='{$row['element_id']}' and live=1 
								order by 
										`position` asc";
					$sub_result = do_query($sub_query);
					while($sub_row = do_fetch_result($sub_result)){
						$element_to_get[] = "element_{$row['element_id']}_{$sub_row['option_id']}";
						$checkbox_childs[$row['element_id']][] =  $sub_row['option_id']; //store the child into array for further reference
					}
				}
			}
		}
		
		//pick user input
		$user_input = array();
		foreach ($element_to_get as $element_name){
			$user_input[$element_name] = @$input[$element_name];
		}
					
						
		$error_elements = array();
		$table_data = array();
		//validate input based on rules specified for each field
		foreach ($user_input as $element_name=>$element_data){
			
			//get element_id from element_name
			$exploded = array();
			$exploded = explode('_',$element_name);
			$element_id = $exploded[1];
			
			$rules = array();
			$target_input = array();
			
			$element_type = $element_info[$element_id]['type'];
			
			
			//if this is private fields and not logged-in as admin, bypass operation below, just supply the default value if any
			if(($element_info[$element_id]['is_private'] == 1) && empty($_SESSION['logged_in'])){
				if(!empty($element_info[$element_id]['default_value'])){
					$table_data['element_'.$element_id] = $element_info[$element_id]['default_value'];
				}
				continue;
			}
			
			
			if('text' == $element_type){ //Single Line Text
											
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['max'] 		= 255;
				
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('textarea' == $element_type){ //Paragraph
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
												
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('radio' == $element_type){ //Multiple Choice
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = $element_data; 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('number' == $element_type){ //Number
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				//check for numeric if not empty
				if(!empty($user_input[$element_name])){ 
					$rules[$element_name]['numeric'] = true;
				}
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = $element_data; 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('url' == $element_type){ //Website
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['website'] = true;
														
				if($element_data == 'http://'){
					$element_data = '';
				}
						
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('email' == $element_type){ //Email
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['email'] = true;
														
										
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('simple_name' == $element_type){ //Simple Name
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 2 elements total	
				$element_name_2 = substr($element_name,0,-1).'2';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed on next loop
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
					$rules[$element_name_2]['required'] = true;
				}
																		
				$target_input[$element_name]   = $user_input[$element_name];
				$target_input[$element_name_2] = $user_input[$element_name_2];
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				
				//prepare data for table column
				$table_data[$element_name] 	 = $user_input[$element_name]; 
				$table_data[$element_name_2] = $user_input[$element_name_2];
				
			}elseif ('name' == $element_type){ //Name -  Extended
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 4 elements total	
				//only element no 2&3 matters (first and last name)
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
				$element_name_4 = substr($element_name,0,-1).'4';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
				$processed_elements[] = $element_name_4;
								
				if($element_info[$element_id]['is_required']){
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
				}
																		
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				$form_data[$element_name_4]['default_value'] = htmlspecialchars($user_input[$element_name_4]);
				
				//prepare data for table column
				$table_data[$element_name] 	 = $user_input[$element_name]; 
				$table_data[$element_name_2] = $user_input[$element_name_2];
				$table_data[$element_name_3] = $user_input[$element_name_3];
				$table_data[$element_name_4] = $user_input[$element_name_4];
				
			}elseif ('time' == $element_type){ //Time
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 4 elements total	
				
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
				$element_name_4 = substr($element_name,0,-1).'4';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
				$processed_elements[] = $element_name_4;
								
				if($element_info[$element_id]['is_required']){
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
					$rules[$element_name_4]['required'] = true;
				}

				//check time validity if any of the compound field entered
				$time_entry_exist = false;
				if(!empty($user_input[$element_name]) || !empty($user_input[$element_name_2]) || !empty($user_input[$element_name_3])){
					$rules['element_time']['time'] = true;
					$time_entry_exist = true;
				}
				
				if($time_entry_exist && empty($element_info[$element_id]['constraint'])){
					$user_input[$element_name_3] = '00';
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules['element_time_no_meridiem']['unique'] = $form_id.'#'.substr($element_name,0,-2); //to check uniquenes we need to use 24 hours HH:MM:SS format
				}
							
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				$target_input[$element_name_4] = $user_input[$element_name_4];
				if($time_entry_exist){
					$target_input['element_time']  = $user_input[$element_name].':'.$user_input[$element_name_2].':'.$user_input[$element_name_3].' '.$user_input[$element_name_4];
					$target_input['element_time_no_meridiem'] = @date("G:i:s",strtotime($target_input['element_time']));
				}
				
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				$form_data[$element_name_4]['default_value'] = htmlspecialchars($user_input[$element_name_4]);
				
				//prepare data for table column
				$table_data[substr($element_name,0,-2)] 	 = @$target_input['element_time_no_meridiem'];
								
			}elseif ('address' == $element_type){ //Address
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 6 elements total, element #2 (address line 2) is optional	
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
				$element_name_4 = substr($element_name,0,-1).'4';
				$element_name_5 = substr($element_name,0,-1).'5';
				$element_name_6 = substr($element_name,0,-1).'6';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
				$processed_elements[] = $element_name_4;
				$processed_elements[] = $element_name_5;
				$processed_elements[] = $element_name_6;
								
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] = true;
					$rules[$element_name_3]['required'] = true;
					$rules[$element_name_4]['required'] = true;
					$rules[$element_name_5]['required'] = true;
					$rules[$element_name_6]['required'] = true;
					
				}
				
				$target_input[$element_name]   = $user_input[$element_name];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				$target_input[$element_name_4] = $user_input[$element_name_4];
				$target_input[$element_name_5] = $user_input[$element_name_5];
				$target_input[$element_name_6] = $user_input[$element_name_6];
				
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				$form_data[$element_name_4]['default_value'] = htmlspecialchars($user_input[$element_name_4]);
				$form_data[$element_name_5]['default_value'] = htmlspecialchars($user_input[$element_name_5]);
				$form_data[$element_name_6]['default_value'] = htmlspecialchars($user_input[$element_name_6]);
				
				//prepare data for table column
				$table_data[$element_name] 	 = $user_input[$element_name]; 
				$table_data[$element_name_2] = $user_input[$element_name_2];
				$table_data[$element_name_3] = $user_input[$element_name_3];
				$table_data[$element_name_4] = $user_input[$element_name_4];
				$table_data[$element_name_5] = $user_input[$element_name_5];
				$table_data[$element_name_6] = $user_input[$element_name_6];
				
			}elseif ('money' == $element_type){ //Price
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 2 elements total (for currency other than yen)	
				if($element_info[$element_id]['constraint'] != 'yen'){ //if other than yen
					$base_element_name = substr($element_name,0,-1);
					$element_name_2 = $base_element_name.'2';
					$processed_elements[] = $element_name_2;
										
					if($element_info[$element_id]['is_required']){
						$rules[$base_element_name]['required'] 	= true;
					}
					
					//check for numeric if not empty
					if(!empty($user_input[$element_name]) || !empty($user_input[$element_name_2])){
						$rules[$base_element_name]['numeric'] = true;
					}
					
					if($element_info[$element_id]['is_unique']){
						$rules[$base_element_name]['unique'] 	= $form_id.'#'.substr($element_name,0,-2);
					}
				
					$target_input[$base_element_name]   = $user_input[$element_name].'.'.$user_input[$element_name_2]; //join dollar+cent
					if($target_input[$base_element_name] == '.'){
						$target_input[$base_element_name] = '';
					}
					
					//save old data into array, for form redisplay in case errors occured
					$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
					$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
					
					//prepare data for table column
					if(!empty($user_input[$element_name]) || !empty($user_input[$element_name_2]) 
					   || $user_input[$element_name] === '0' || $user_input[$element_name_2] === '0'){
					  	$table_data[substr($element_name,0,-2)] = $user_input[$element_name].'.'.$user_input[$element_name_2];
					}		
				}else{
					if($element_info[$element_id]['is_required']){
						$rules[$element_name]['required'] 	= true;
					}
					
					//check for numeric if not empty
					if(!empty($user_input[$element_name])){ 
						$rules[$element_name]['numeric'] = true;
					}
					
					if($element_info[$element_id]['is_unique']){
						$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
					}
									
					$target_input[$element_name]   = $user_input[$element_name];
					
					//save old data into array, for form redisplay in case errors occured
					$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
					
					//prepare data for table column
					$table_data[$element_name] 	 = $user_input[$element_name]; 
								
				}
								
				
												
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
			}elseif ('checkbox' == $element_type){ //Checkboxes
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				if($element_info[$element_id]['is_required']){
					//checking 'required' for checkboxes is more complex
					//we need to get total child, and join it into one element
					//only one element is required to be checked
					$all_child_array = array();
					$all_child_array = $checkbox_childs[$element_id];  
					$base_element_name = substr($element_name,0,-1);
					
					$all_checkbox_value = '';
					foreach ($all_child_array as $i){
						$all_checkbox_value .= $user_input[$base_element_name.$i];
						$processed_elements[] = $base_element_name.$i;
						
						//save old data into array, for form redisplay in case errors occured
						$form_data[$base_element_name.$i]['default_value']   = $user_input[$base_element_name.$i];
						
						//prepare data for table column
						$table_data[$base_element_name.$i] = $user_input[$base_element_name.$i];
				
					}
					
					$rules[$base_element_name]['required'] 	= true;
					
					$target_input[$base_element_name] = $all_checkbox_value;
					$validation_result = validate_element($target_input,$rules);
					
					if($validation_result !== true){
						$error_elements[$element_id] = $validation_result;
					}	
					
				}else{ //if not required, we only need to capture all data
					$all_child_array = array();
					$base_exploded = array();
					$all_child_array = $checkbox_childs[$element_id]; 
					
					$base_exploded = explode("_",$element_name);
					$base_element_name = "element_".$base_exploded[1]."_";
					
					
					foreach ($all_child_array as $i){
											
						//save old data into array, for form redisplay in case errors occured
						$form_data[$base_element_name.$i]['default_value']   = $user_input[$base_element_name.$i];
						
						//prepare data for table column
						$table_data[$base_element_name.$i] = $user_input[$base_element_name.$i];
					}
				    
				}
			}elseif ('select' == $element_type){ //Drop Down
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = $user_input[$element_name]; 
				
				//prepare data for table column
				$table_data[$element_name] = $user_input[$element_name]; 
				
			}elseif ('date' == $element_type || 'europe_date' == $element_type){ //Date
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 3 elements total	
				
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
								
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
												
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] = true;
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
				}
				
				$rules['element_date']['date'] = 'yyyy/mm/dd';
				
				if($element_info[$element_id]['is_unique']){
					$rules['element_date']['unique'] = $form_id.'#'.substr($element_name,0,-2); 
				}
							
				$target_input[$element_name] = $user_input[$element_name];
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				
				$base_element_name = substr($element_name,0,-2);
				if('date' == $element_type){ //MM/DD/YYYY
					$target_input['element_date'] = $user_input[$element_name_3].'-'.$user_input[$element_name].'-'.$user_input[$element_name_2];
					
					//prepare data for table column
					$table_data[$base_element_name] = $user_input[$element_name_3].'-'.$user_input[$element_name].'-'.$user_input[$element_name_2];
				}else{ //DD/MM/YYYY
					$target_input['element_date'] = $user_input[$element_name_3].'-'.$user_input[$element_name_2].'-'.$user_input[$element_name];
					
					//prepare data for table column
					$table_data[$base_element_name] = $user_input[$element_name_3].'-'.$user_input[$element_name_2].'-'.$user_input[$element_name];
				}
				
				$test_empty = str_replace('-','',$target_input['element_date']); //if user not submitting any entry, remove the dashes
				if(empty($test_empty)){
					unset($target_input['element_date']);
					$table_data[$base_element_name] = '';
				}
										
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
								
			}elseif ('simple_phone' == $element_type){ //Simple Phone
							
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if(!empty($user_input[$element_name])){
					$rules[$element_name]['simple_phone'] = true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
									
				$target_input[$element_name]   = $user_input[$element_name];
							
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				
				//prepare data for table column
				$table_data[$element_name] = $user_input[$element_name]; 
								
			}elseif ('phone' == $element_type){ //Phone - US format
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 3 elements total	
				
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
								
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
												
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required']   = true;
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
				}
				
				$rules['element_phone']['phone'] = true;
				
				
				if($element_info[$element_id]['is_unique']){
					$rules['element_phone']['unique'] = $form_id.'#'.substr($element_name,0,-2); 
				}
				
				$target_input[$element_name]   = $user_input[$element_name];			
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				$target_input['element_phone'] = $user_input[$element_name].$user_input[$element_name_2].$user_input[$element_name_3];
									
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				
				//prepare data for table column
				$table_data[substr($element_name,0,-2)] = $user_input[$element_name].$user_input[$element_name_2].$user_input[$element_name_3];
				
			}elseif ('email' == $element_type){ //Email
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['email'] = true;
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				
				//prepare data for table column
				$table_data[$element_name] = $user_input[$element_name]; 
				
			}elseif ('file' == $element_type){ //File
				
				$rules[$element_name]['filetype'] 	= true;
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required_file'] 	= true;
				}
																
				$target_input[$element_name] = $element_name; //special for file, only need to pass input name
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}else{
					//if validation passed, store uploaded file info into array
					if($_FILES[$element_name]['size'] > 0){
						$uploaded_files[] = $element_name;
					}
				}
						
				
			}
			
		}

		
		
		//get form redirect info, if any
		//get form properties data
		$query 	= "select 
						 form_redirect,
						 form_email,
						 form_unique_ip,
						 form_captcha,
						 form_review,
						 esl_from_name,
						 esl_from_email_address,
						 esl_subject,
						 esl_content,
						 esl_plain_text,
						 esr_email_address,
						 esr_from_name,
						 esr_from_email_address,
						 esr_subject,
						 esr_content,
						 esr_plain_text
				     from 
				     	 `ap_forms` 
				    where 
				    	 form_id='$form_id'";
		
		$result = do_query($query);
		$row 	= do_fetch_result($result);
		$form_redirect  = $row['form_redirect'];
		$form_unique_ip = $row['form_unique_ip'];
		$form_email 	= $row['form_email'];
		$form_captcha	= $row['form_captcha'];
		$form_review	= $row['form_review'];
		$user_ip_address 	= $_SERVER['REMOTE_ADDR'];
		
		$esl_from_name 	= $row['esl_from_name'];
		$esl_from_email_address = $row['esl_from_email_address'];
		$esl_subject 	= $row['esl_subject'];
		$esl_content 	= $row['esl_content'];
		$esl_plain_text	= $row['esl_plain_text'];
		
		$esr_email_address 	= $row['esr_email_address'];
		$esr_from_name 	= $row['esr_from_name'];
		$esr_from_email_address = $row['esr_from_email_address'];
		$esr_subject 	= $row['esr_subject'];
		$esr_content 	= $row['esr_content'];
		$esr_plain_text	= $row['esr_plain_text'];
		
		$process_result['form_redirect']  = $form_redirect;
		$process_result['old_values'] 	  = $form_data;
		$process_result['error_elements'] = $error_elements;
		
		//check for ip address
		if(!empty($form_unique_ip)){
			//if ip address checking enabled, compare user ip address with value in db
			$query = "select count(id) total_ip from `ap_form_{$form_id}` where ip_address='$user_ip_address'";
			$result = do_query($query);
			$row = do_fetch_result($result);
			if(!empty($row['total_ip'])){
				$process_result['custom_error'] = 'Sorry, but this form is limited to one submission per user.';
			}
		}
		
		//check for captcha if enabled and there is no errors from previous fields
		if(!empty($form_captcha) && empty($error_elements)){
			
			if(USE_INTERNAL_CAPTCHA === true){//if internal captcha is being used
				if(!empty($_POST['captcha_response_field'])){
					$captcha_response_field = trim($_POST['captcha_response_field']);
					 if (PhpCaptcha::Validate($captcha_response_field) !== true) {
					 	$error_elements['element_captcha'] = 'incorrect-captcha-sol';
				        $process_result['error_elements'] = $error_elements;
					 }
				}else{ //user not entered the words at all
					$error_elements['element_captcha'] = 'el-required';
				    $process_result['error_elements']  = $error_elements;
				}
			}else{ //otherwise reCaptcha is being used
				if(!empty($_POST['recaptcha_response_field'])){
					$recaptcha_response = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
		                                        $user_ip_address,
		                                        $_POST["recaptcha_challenge_field"],
		                                        $_POST["recaptcha_response_field"]);
					
		            if($recaptcha_response !== false){ //if false, then we can't connect to captcha server, bypass captcha checking            	
		            	if ($recaptcha_response->is_valid === false) {
							$error_elements['element_captcha'] = $recaptcha_response->error;
				            $process_result['error_elements'] = $error_elements;
				        }
		            }
				}else{ //user not entered the words at all
					$error_elements['element_captcha'] = 'el-required';
				    $process_result['error_elements']  = $error_elements;
				}
			}
            
		}
		
		
		//insert ip address and date created
		$table_data['ip_address']   = $user_ip_address;
		$table_data['date_created'] = date("Y-m-d H:i:s");
		
		$is_inserted = false;
		
		//start insert data into table ----------------------		
		//dynamically create the field list and field values, based on the input given
		if(!empty($table_data) && empty($error_elements) && empty($process_result['custom_error'])){
			$has_value = false;
			
			$field_list = '';
			$field_values = '';
			
			foreach ($table_data as $key=>$value){
				
				if($value == ''){ //don't insert blank entry
					continue;
				}
				$value		    = mysql_real_escape_string($value);
				$field_list    .= "`$key`,";
				$field_values  .= "'$value',";
				
				if(!empty($value)){
					$has_value = true;
				}
			}
			
			//add session_id to query if 'form review' enabled 
			if(!empty($form_review)){
				//save previously uploaded file list, so users don't need to reupload files 
				//get all file uploads elements first
				
				if(!empty($_SESSION['review_id'])){
					
					$query = "SELECT 
									element_id 
								FROM 
									ap_form_elements 
							   WHERE 
							   		form_id='{$form_id}' AND 
							   		element_type='file' AND 
							   		element_is_private=0";
					$result = do_query($query);
					$file_uploads_array = array();
					while($row = do_fetch_result($result)){
						$file_uploads_array[] = 'element_'.$row['element_id'];
					}
					
					$file_uploads_column = implode('`,`',$file_uploads_array);
					$file_uploads_column = '`'.$file_uploads_column.'`';
					
					if(!empty($file_uploads_array)){
						$query = "SELECT {$file_uploads_column} FROM `ap_form_{$form_id}_review` where id='{$_SESSION['review_id']}'";
						$result = do_query($query);
						$row = do_fetch_result($result);
						foreach ($file_uploads_array as $element_name){
							if(!empty($row[$element_name])){
								$uploaded_file_lookup[$element_name] = $row[$element_name];
							}
						}
					}
				}
			
				
				//add session_id to query if 'form review' enabled 
				$session_id = session_id();
				
				do_query("DELETE FROM `ap_form_{$form_id}_review` where session_id='{$session_id}'");
				
				$field_list    .= "`session_id`,";
				$field_values  .= "'{$session_id}',";
			}
			
			
			if($has_value){ //if blank form submitted, dont insert anything
							
				//start insert query ----------------------------------------	
				$field_list   = substr($field_list,0,-1);
				$field_values = substr($field_values,0,-1);
			
				if(empty($form_review)){ 
					$query = "INSERT INTO `ap_form_{$form_id}` ($field_list) VALUES ($field_values);"; 
				}else{ //insert to temporary table, if form review is enabled
					$query = "INSERT INTO `ap_form_{$form_id}_review` ($field_list) VALUES ($field_values);"; 
				}
				
				do_query($query);
			
				$record_insert_id = mysql_insert_id();
				//end insert query ------------------------------------------
				
				$is_inserted = true;			
			}
		}
		//end insert data into table -------------------------		
		
		//upload the files
		if(!empty($record_insert_id)){
			if(!empty($uploaded_files)){
				foreach ($uploaded_files as $element_name){
					$file_token = md5(uniqid(rand(), true)); //add random token to uploaded filename, to increase security
					
					if(empty($form_review)){
						//move file and check for invalid file
						$destination_file = $input['machform_data_path'].UPLOAD_DIR."/form_{$form_id}/files/{$element_name}_{$file_token}-{$record_insert_id}-{$_FILES[$element_name]['name']}";
						if (move_uploaded_file($_FILES[$element_name]['tmp_name'], $destination_file)) {
							$filename = mysql_real_escape_string($_FILES[$element_name]['name']);
							$query = "update ap_form_{$form_id} set $element_name='{$element_name}_{$file_token}-{$record_insert_id}-{$filename}' where id='$record_insert_id'";
							do_query($query);
						}
					}else{
						//for form with review enabled, append .tmp suffix to all uploaded files
						//move file and check for invalid file
						$destination_file = $input['machform_data_path'].UPLOAD_DIR."/form_{$form_id}/files/{$element_name}_{$file_token}-{$record_insert_id}-{$_FILES[$element_name]['name']}.tmp";
						if (move_uploaded_file($_FILES[$element_name]['tmp_name'], $destination_file)) {
							$filename = mysql_real_escape_string($_FILES[$element_name]['name']);
							$query = "update ap_form_{$form_id}_review set $element_name='{$element_name}_{$file_token}-{$record_insert_id}-{$filename}' where id='$record_insert_id'";
							do_query($query);
						}
						
						if(!empty($uploaded_file_lookup[$element_name])){
							unset($uploaded_file_lookup[$element_name]);
						}
					}
				}
			}
			
			//update the previouly uploaded file to the current record
			if(!empty($form_review) && !empty($uploaded_file_lookup)){
				//update the database record
				$update_clause = '';
				foreach ($uploaded_file_lookup as $element_name=>$filename){
					$update_clause .= "`{$element_name}`='{$filename}',";
				}	
				$update_clause = rtrim($update_clause,",");
				do_query("UPDATE `ap_form_{$form_id}_review` SET {$update_clause} WHERE id='{$record_insert_id}'");
			}
		}
		
							
		//start sending notification email to admin ------------------------------------------
		if($is_inserted && !empty($form_email) && empty($form_review)){
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
			
			send_notification($form_id,$record_insert_id,$form_email,$admin_email_param);
    	
		}
		//end emailing notifications to admin ----------------------------------------------
		
		
		//start sending notification email to user ------------------------------------------
		if($is_inserted && !empty($esr_email_address) && empty($form_review)){
			//get parameters for the email
			
			//to email
			if(is_numeric($esr_email_address)){
				$esr_email_address = '{element_'.$esr_email_address.'}';
			}
					
			//from name
			if(!empty($esr_from_name)){
				$user_email_param['from_name'] = $esr_from_name;
			}elseif (NOTIFICATION_MAIL_FROM_NAME != ''){
				$user_email_param['from_name'] = NOTIFICATION_MAIL_FROM_NAME;
			}else{
				$user_email_param['from_name'] = 'MachForm';
			}
			
			//from email address
			if(!empty($esr_from_email_address)){
				if(is_numeric($esr_from_email_address)){
					$user_email_param['from_email'] = '{element_'.$esr_from_email_address.'}';
				}else{
					$user_email_param['from_email'] = $esr_from_email_address;
				}
			}elseif(NOTIFICATION_MAIL_FROM != ''){
				$user_email_param['from_email'] = NOTIFICATION_MAIL_FROM;
			}else{
				$domain = str_replace('www.','',$_SERVER['SERVER_NAME']);
				$user_email_param['from_email'] = "no-reply@{$domain}";
			}
			
			//subject
			if(!empty($esr_subject)){
				$user_email_param['subject'] = $esr_subject;
			}elseif (NOTIFICATION_MAIL_SUBJECT != ''){
				$user_email_param['subject'] = NOTIFICATION_MAIL_SUBJECT;
			}else{
				$user_email_param['subject'] = '{form_name} - Receipt';
			}
			
			//content
			if(!empty($esr_content)){
				$user_email_param['content'] = $esr_content;
			}else{
				$user_email_param['content'] = '{entry_data}';
			}
			
			$user_email_param['as_plain_text'] = $esr_plain_text;
			$user_email_param['target_is_admin'] = false; 
			
			send_notification($form_id,$record_insert_id,$esr_email_address,$user_email_param);
		}
		//end emailing notifications to user ----------------------------------------------
			
		
		//if there is any error message or elements, send false as status
		if(empty($error_elements) && empty($process_result['custom_error'])){		
			$process_result['status'] = true;
			
			//if 'form review' enabled, send review_id
			if(!empty($form_review)){
				$process_result['review_id']   = $record_insert_id;
			}
		}else{
			$process_result['status'] = false;
		}
		
				
		return $process_result; 
	}
	
	
	//similar to above, except this function is being used to update form data
	function process_form_update($input){
		
		$form_id  = (int) trim($input['form_id']);
		$entry_id = (int) trim($input['edit_id']);
		
		global $lang;
		
		//this function handle password submission and general form submission
		if(isset($input['password'])){ //if there is password input, do validation
			$query = "select count(form_id) valid_password from `ap_forms` where form_password='{$input['password']}'";
			$result = do_query($query);
			$row = do_fetch_result($result);
			if(!empty($row['valid_password'])){
				$process_result['status'] = true;
				$_SESSION['user_authenticated'] = $form_id;
			}else{
				$process_result['status'] = false;
				$process_result['custom_error'] = $lang['form_pass_invalid'];
			}
			
			return $process_result;
		}
		
		
		$element_child_lookup['address'] 	 = 5;
		$element_child_lookup['simple_name'] = 1;
		$element_child_lookup['name'] 		 = 3;
		$element_child_lookup['phone'] 		 = 2;
		$element_child_lookup['date'] 		 = 2;
		$element_child_lookup['europe_date'] = 2;
		$element_child_lookup['time'] 		 = 3;
		$element_child_lookup['money'] 		 = 1; //this applies to dollar,euro and pound. yen don't have child
		$element_child_lookup['checkbox'] 	 = 1; //this is just a dumb value
		
		//never trust user input, get a list of input fields based on info stored on table
		//element has real child -> address, simple_name, name
		//element has virtual child -> phone, date, europe_date, time, money
		
		$query = "select 
						element_id,
       					element_title,
       					element_is_required,
       					element_is_unique,
       					element_type, 
       					element_constraint,
       					element_total_child
					from 
						ap_form_elements 
				   where 
				   		form_id='$form_id' order by element_id asc";
		
		$result = do_query($query);
		
		$element_to_get = array();
		
		while($row = do_fetch_result($result)){
			if($row['element_type'] == 'section'){
				continue;
			}
			//store element info
			$element_info[$row['element_id']]['title'] 			= $row['element_title'];
			$element_info[$row['element_id']]['type'] 			= $row['element_type'];
			$element_info[$row['element_id']]['is_required'] 	= $row['element_is_required'];
			$element_info[$row['element_id']]['is_unique'] 		= $row['element_is_unique'];
			$element_info[$row['element_id']]['constraint'] 	= $row['element_constraint'];
						
			//get element form name, complete with the childs
			if(empty($element_child_lookup[$row['element_type']]) || ($row['element_constraint'] == 'yen')){ //elements with no child
				$element_to_get[] = 'element_'.$row['element_id'];	
			}else{ //elements with child
				if($row['element_type'] != 'checkbox'){
					$max = $element_child_lookup[$row['element_type']] + 1;
					
					for ($j=1;$j<=$max;$j++){
						$element_to_get[] = "element_{$row['element_id']}_{$j}";
					}
				}else{ 
					//for checkbox, get childs elements from ap_element_options table 
					$sub_query = "select 
										option_id 
									from 
										ap_element_options 
								   where 
								   		form_id='{$form_id}' and element_id='{$row['element_id']}' and live=1 
								order by 
										`position` asc";
					$sub_result = do_query($sub_query);
					while($sub_row = do_fetch_result($sub_result)){
						$element_to_get[] = "element_{$row['element_id']}_{$sub_row['option_id']}";
						$checkbox_childs[$row['element_id']][] =  $sub_row['option_id']; //store the child into array for further reference
					}
				}
			
				
				
			}
					
		}
		
		//pick user input
		$user_input = array();
		foreach ($element_to_get as $element_name){
			$user_input[$element_name] = $input[$element_name];
		}
		
				
		$error_elements = array();
		$table_data = array();
		//validate input based on rules specified for each field
		foreach ($user_input as $element_name=>$element_data){
			
			//get element_id from element_name
			$exploded = array();
			$exploded = explode('_',$element_name);
			$element_id = $exploded[1];
			
			$rules = array();
			$target_input = array();
			
			$element_type = $element_info[$element_id]['type'];
			
			if('text' == $element_type){ //Single Line Text
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['max'] 		= 255;
				
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('textarea' == $element_type){ //Paragraph
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
												
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('radio' == $element_type){ //Multiple Choice
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = $element_data; 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('number' == $element_type){ //Number
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				//check for numeric if not empty
				if(!empty($user_input[$element_name])){ 
					$rules[$element_name]['numeric'] = true;
				}
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = $element_data; 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('url' == $element_type){ //Website
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['website'] = true;
														
				if($element_data == 'http://'){
					$element_data = '';
				}
						
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('email' == $element_type){ //Email
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['email'] = true;
														
										
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = htmlspecialchars($element_data); 
				
				//prepare data for table column
				$table_data[$element_name] = $element_data; 
				
			}elseif ('simple_name' == $element_type){ //Simple Name
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 2 elements total	
				$element_name_2 = substr($element_name,0,-1).'2';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed on next loop
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
					$rules[$element_name_2]['required'] = true;
				}
																		
				$target_input[$element_name]   = $user_input[$element_name];
				$target_input[$element_name_2] = $user_input[$element_name_2];
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				
				//prepare data for table column
				$table_data[$element_name] 	 = $user_input[$element_name]; 
				$table_data[$element_name_2] = $user_input[$element_name_2];
				
			}elseif ('name' == $element_type){ //Name -  Extended
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 4 elements total	
				//only element no 2&3 matters (first and last name)
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
				$element_name_4 = substr($element_name,0,-1).'4';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
				$processed_elements[] = $element_name_4;
								
				if($element_info[$element_id]['is_required']){
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
				}
																		
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				$form_data[$element_name_4]['default_value'] = htmlspecialchars($user_input[$element_name_4]);
				
				//prepare data for table column
				$table_data[$element_name] 	 = $user_input[$element_name]; 
				$table_data[$element_name_2] = $user_input[$element_name_2];
				$table_data[$element_name_3] = $user_input[$element_name_3];
				$table_data[$element_name_4] = $user_input[$element_name_4];
				
			}elseif ('time' == $element_type){ //Time
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 4 elements total	
				
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
				$element_name_4 = substr($element_name,0,-1).'4';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
				$processed_elements[] = $element_name_4;
								
				if($element_info[$element_id]['is_required']){
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
					$rules[$element_name_4]['required'] = true;
				}

				//check time validity if any of the compound field entered
				$time_entry_exist = false;
				if(!empty($user_input[$element_name]) || !empty($user_input[$element_name_2]) || !empty($user_input[$element_name_3])){
					$rules['element_time']['time'] = true;
					$time_entry_exist = true;
				}
				
				if($time_entry_exist && empty($element_info[$element_id]['constraint'])){
					$user_input[$element_name_3] = '00';
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules['element_time_no_meridiem']['unique'] = $form_id.'#'.substr($element_name,0,-2); //to check uniquenes we need to use 24 hours HH:MM:SS format
				}
							
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				$target_input[$element_name_4] = $user_input[$element_name_4];
				if($time_entry_exist){
					$target_input['element_time']  = $user_input[$element_name].':'.$user_input[$element_name_2].':'.$user_input[$element_name_3].' '.$user_input[$element_name_4];
					$target_input['element_time_no_meridiem'] = @date("G:i:s",strtotime($target_input['element_time']));
				}
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				$form_data[$element_name_4]['default_value'] = htmlspecialchars($user_input[$element_name_4]);
				
				//prepare data for table column
				$table_data[substr($element_name,0,-2)] 	 = $target_input['element_time_no_meridiem'];
								
			}elseif ('address' == $element_type){ //Address
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 6 elements total, element #2 (address line 2) is optional	
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
				$element_name_4 = substr($element_name,0,-1).'4';
				$element_name_5 = substr($element_name,0,-1).'5';
				$element_name_6 = substr($element_name,0,-1).'6';
				
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
				$processed_elements[] = $element_name_4;
				$processed_elements[] = $element_name_5;
				$processed_elements[] = $element_name_6;
								
				if($element_info[$element_id]['is_required']){
					$rules[$element_name_3]['required'] = true;
					$rules[$element_name_4]['required'] = true;
					$rules[$element_name_5]['required'] = true;
					$rules[$element_name_6]['required'] = true;
					
				}
																		
				$target_input[$element_name_3] = $user_input[$element_name_3];
				$target_input[$element_name_4] = $user_input[$element_name_4];
				$target_input[$element_name_5] = $user_input[$element_name_5];
				$target_input[$element_name_6] = $user_input[$element_name_6];
				
				
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				$form_data[$element_name_4]['default_value'] = htmlspecialchars($user_input[$element_name_4]);
				$form_data[$element_name_5]['default_value'] = htmlspecialchars($user_input[$element_name_5]);
				$form_data[$element_name_6]['default_value'] = htmlspecialchars($user_input[$element_name_6]);
				
				//prepare data for table column
				$table_data[$element_name] 	 = $user_input[$element_name]; 
				$table_data[$element_name_2] = $user_input[$element_name_2];
				$table_data[$element_name_3] = $user_input[$element_name_3];
				$table_data[$element_name_4] = $user_input[$element_name_4];
				$table_data[$element_name_5] = $user_input[$element_name_5];
				$table_data[$element_name_6] = $user_input[$element_name_6];
				
			}elseif ('money' == $element_type){ //Price
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 2 elements total (for currency other than yen)	
				if($element_info[$element_id]['constraint'] != 'yen'){ //if other than yen
					$base_element_name = substr($element_name,0,-1);
					$element_name_2 = $base_element_name.'2';
					$processed_elements[] = $element_name_2;
										
					if($element_info[$element_id]['is_required']){
						$rules[$base_element_name]['required'] 	= true;
					}
					
					//check for numeric if not empty
					if(!empty($user_input[$element_name]) || !empty($user_input[$element_name_2])){
						$rules[$base_element_name]['numeric'] = true;
					}
					
					if($element_info[$element_id]['is_unique']){
						$rules[$base_element_name]['unique'] 	= $form_id.'#'.substr($element_name,0,-2);
					}
				
					$target_input[$base_element_name]   = $user_input[$element_name].'.'.$user_input[$element_name_2]; //join dollar+cent
					if($target_input[$base_element_name] == '.'){
						$target_input[$base_element_name] = '';
					}
					
					//save old data into array, for form redisplay in case errors occured
					$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
					$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
					
					//prepare data for table column
					if(!empty($user_input[$element_name]) || !empty($user_input[$element_name_2])){
						$table_data[substr($element_name,0,-2)] = $user_input[$element_name].'.'.$user_input[$element_name_2];
					}		
				}else{
					if($element_info[$element_id]['is_required']){
						$rules[$element_name]['required'] 	= true;
					}
					
					//check for numeric if not empty
					if(!empty($user_input[$element_name])){ 
						$rules[$element_name]['numeric'] = true;
					}
					
					if($element_info[$element_id]['is_unique']){
						$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
					}
									
					$target_input[$element_name]   = $user_input[$element_name];
					
					//save old data into array, for form redisplay in case errors occured
					$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
					
					//prepare data for table column
					$table_data[$element_name] 	 = $user_input[$element_name]; 
								
				}
								
				
												
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
			}elseif ('checkbox' == $element_type){ //Checkboxes
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				if($element_info[$element_id]['is_required']){
					//checking 'required' for checkboxes is more complex
					//we need to get total child, and join it into one element
					//only one element is required to be checked
					$all_child_array = array();
					$all_child_array = $checkbox_childs[$element_id];  
					$base_element_name = substr($element_name,0,-1);
					
					$all_checkbox_value = '';
					foreach ($all_child_array as $i){
						$all_checkbox_value .= $user_input[$base_element_name.$i];
						$processed_elements[] = $base_element_name.$i;
						
						//save old data into array, for form redisplay in case errors occured
						$form_data[$base_element_name.$i]['default_value']   = $user_input[$base_element_name.$i];
						
						//prepare data for table column
						$table_data[$base_element_name.$i] = $user_input[$base_element_name.$i];
						
					}
					
					$rules[$base_element_name]['required'] 	= true;
					
					$target_input[$base_element_name] = $all_checkbox_value;
					$validation_result = validate_element($target_input,$rules);
					
					if($validation_result !== true){
						$error_elements[$element_id] = $validation_result;
					}	
					
				}else{ //if not required, we only need to capture all data
					$all_child_array = array();
					$base_exploded = array();
					$all_child_array = $checkbox_childs[$element_id]; 
					
					$base_exploded = explode("_",$element_name);
					$base_element_name = "element_".$base_exploded[1]."_";
					
					foreach ($all_child_array as $i){
											
						//save old data into array, for form redisplay in case errors occured
						$form_data[$base_element_name.$i]['default_value']   = $user_input[$base_element_name.$i];
						
						//prepare data for table column
						$table_data[$base_element_name.$i] = $user_input[$base_element_name.$i];
						
					}
				
				}
			}elseif ('select' == $element_type){ //Drop Down
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value'] = $user_input[$element_name]; 
				
				//prepare data for table column
				$table_data[$element_name] = $user_input[$element_name]; 
				
			}elseif ('date' == $element_type || 'europe_date' == $element_type){ //Date
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 3 elements total	
				
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
								
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
												
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] = true;
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
				}
				
				$rules['element_date']['date'] = 'yyyy/mm/dd';
				
				if($element_info[$element_id]['is_unique']){
					$rules['element_date']['unique'] = $form_id.'#'.substr($element_name,0,-2); 
				}
							
				$target_input[$element_name] = $user_input[$element_name];
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				
				$base_element_name = substr($element_name,0,-2);
				if('date' == $element_type){ //MM/DD/YYYY
					$target_input['element_date'] = $user_input[$element_name_3].'-'.$user_input[$element_name].'-'.$user_input[$element_name_2];
					
					//prepare data for table column
					$table_data[$base_element_name] = $user_input[$element_name_3].'-'.$user_input[$element_name].'-'.$user_input[$element_name_2];
				}else{ //DD/MM/YYYY
					$target_input['element_date'] = $user_input[$element_name_3].'-'.$user_input[$element_name_2].'-'.$user_input[$element_name];
					
					//prepare data for table column
					$table_data[$base_element_name] = $user_input[$element_name_3].'-'.$user_input[$element_name_2].'-'.$user_input[$element_name];
				}
				
				$test_empty = str_replace('-','',$target_input['element_date']); //if user not submitting any entry, remove the dashes
				if(empty($test_empty)){
					unset($target_input['element_date']);
					$table_data[$base_element_name] = '';
				}
										
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
								
			}elseif ('simple_phone' == $element_type){ //Simple Phone
							
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if(!empty($user_input[$element_name])){
					$rules[$element_name]['simple_phone'] = true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
									
				$target_input[$element_name]   = $user_input[$element_name];
							
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				
				//prepare data for table column
				$table_data[$element_name] = $user_input[$element_name]; 
								
			}elseif ('phone' == $element_type){ //Phone - US format
				
				if(!empty($processed_elements) && is_array($processed_elements) && in_array($element_name,$processed_elements)){
					continue;
				}
				
				//compound element, grab the other element, 3 elements total	
				
				$element_name_2 = substr($element_name,0,-1).'2';
				$element_name_3 = substr($element_name,0,-1).'3';
								
				$processed_elements[] = $element_name_2; //put this element into array so that it won't be processed next
				$processed_elements[] = $element_name_3;
												
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required']   = true;
					$rules[$element_name_2]['required'] = true;
					$rules[$element_name_3]['required'] = true;
				}
				
				$rules['element_phone']['phone'] = true;
				
				
				if($element_info[$element_id]['is_unique']){
					$rules['element_phone']['unique'] = $form_id.'#'.substr($element_name,0,-2); 
				}
				
				$target_input[$element_name]   = $user_input[$element_name];			
				$target_input[$element_name_2] = $user_input[$element_name_2];
				$target_input[$element_name_3] = $user_input[$element_name_3];
				$target_input['element_phone'] = $user_input[$element_name].$user_input[$element_name_2].$user_input[$element_name_3];
									
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				$form_data[$element_name_2]['default_value'] = htmlspecialchars($user_input[$element_name_2]);
				$form_data[$element_name_3]['default_value'] = htmlspecialchars($user_input[$element_name_3]);
				
				//prepare data for table column
				$table_data[substr($element_name,0,-2)] = $user_input[$element_name].$user_input[$element_name_2].$user_input[$element_name_3];
				
			}elseif ('email' == $element_type){ //Email
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required'] 	= true;
				}
				
				if($element_info[$element_id]['is_unique']){
					$rules[$element_name]['unique'] 	= $form_id.'#'.$element_name;
				}
				
				$rules[$element_name]['email'] = true;
																
				$target_input[$element_name] = $element_data;
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}
				
				//save old data into array, for form redisplay in case errors occured
				$form_data[$element_name]['default_value']   = htmlspecialchars($user_input[$element_name]); 
				
				//prepare data for table column
				$table_data[$element_name] = $user_input[$element_name]; 
				
			}elseif ('file' == $element_type){ //File
				//file upload field doesn't need to be required on edit mode
				$element_info[$element_id]['is_required'] = false;
				
				if($element_info[$element_id]['is_required']){
					$rules[$element_name]['required_file'] 	= true;
				}
																
				$target_input[$element_name] = $element_name; //special for file, only need to pass input name
				$validation_result = validate_element($target_input,$rules);
				
				if($validation_result !== true){
					$error_elements[$element_id] = $validation_result;
				}else{
					//if validation passed, store uploaded file info into array
					if($_FILES[$element_name]['size'] > 0){
						$uploaded_files[] = $element_name;
					}
				}
						
				
			}
			
		}

		
				
		
		$process_result['old_values'] 	  = $form_data;
		$process_result['error_elements'] = $error_elements;
		
		$table_data['date_updated'] = date("Y-m-d H:i:s");
		
		//start update data into table ----------------------		
		//dynamically create the field list and field values, based on the input given
		if(!empty($table_data) && empty($error_elements) && empty($process_result['custom_error'])){
			
			//dynamically create the sql update string, based on the input given
			$update_values = '';
			foreach ($table_data as $key=>$value){
				$value = mysql_real_escape_string($value);
				$update_values .= "`$key`='$value',";
			}
			$update_values = substr($update_values,0,-1);
			
			$query = "UPDATE `ap_form_{$form_id}` set 
										$update_values
								  where 
							  	  		id='{$entry_id}';";
			do_query($query);
		}
		//end update data into table -------------------------		
				
		
		//upload the files
		if(!empty($uploaded_files)){
			
			foreach ($uploaded_files as $element_name){
				//remove old file if any
				$query = "select $element_name from `ap_form_{$form_id}` where id='$entry_id'";
				$result = do_query($query);
				$row = do_fetch_result($result);
				if(!empty($row[$element_name])){
					$old_filename = $row[$element_name];
				}
				
				$file_token = md5(uniqid(rand(), true)); //add random token to uploaded filename, to increase security
				
				//move new file and check for invalid file
				$destination_file = UPLOAD_DIR."/form_{$form_id}/files/{$element_name}_{$file_token}-{$entry_id}-{$_FILES[$element_name]['name']}";
				if (move_uploaded_file($_FILES[$element_name]['tmp_name'], $destination_file)) {
					$filename = mysql_real_escape_string($_FILES[$element_name]['name']);
					$query = "update ap_form_{$form_id} set $element_name='{$element_name}_{$file_token}-{$entry_id}-{$filename}' where id='$entry_id'";
					do_query($query);
				
					//remove old file if any
					if(!empty($old_filename)){
						@unlink(UPLOAD_DIR."/form_{$form_id}/files/".$old_filename);
					}
				}
			}
		}
		
		
			
		//if there is any error message or elements, send false as status
		if(empty($error_elements) && empty($process_result['custom_error'])){		
			$process_result['status'] = true;
		}else{
			$process_result['status'] = false;
		}
			
		
		return $process_result; 
	}
	
	//process form review submit
	//move the record from temporary review table to the actual table
	function commit_form_review($form_id,$record_id,$options=array()){
		//move data from ap_form_x_review table to ap_form_x table
		//get all column name except session_id and id
		$query  = "SELECT * FROM `ap_form_{$form_id}_review` WHERE id='$record_id'";
		$result = do_query($query);
				
		$i = 0;
		$fields_num = mysql_num_fields($result);
		$columns = array();
		while($i < $fields_num){
			$meta = mysql_fetch_field($result, $i);
			if(($meta->name != 'session_id') && ($meta->name != 'id')){
				$columns[] = $meta->name;
			}
			$i++;
		}
		
		$columns_joined = implode("`,`",$columns);
		$columns_joined = '`'.$columns_joined.'`';
		
		//copy data from review table
		$query = "INSERT INTO `ap_form_{$form_id}`($columns_joined) SELECT {$columns_joined} from `ap_form_{$form_id}_review` WHERE id='{$record_id}'";
		do_query($query);
		
		$new_record_id = mysql_insert_id();
		
		//rename file uploads, if any
		//get all file uploads elements first
		$query = "SELECT 
						element_id 
					FROM 
						ap_form_elements 
				   WHERE 
				   		form_id='{$form_id}' AND 
				   		element_type='file' AND 
				   		element_is_private=0";
		$result = do_query($query);
		$file_uploads_array = array();
		while($row = do_fetch_result($result)){
			$file_uploads_array[] = 'element_'.$row['element_id'];
		}
		if(!empty($file_uploads_array)){
			$file_uploads_column = implode('`,`',$file_uploads_array);
			$file_uploads_column = '`'.$file_uploads_column.'`';
			
			$query = "SELECT {$file_uploads_column} FROM `ap_form_{$form_id}_review` where id='{$record_id}'";
			$result = do_query($query);
			$row = do_fetch_result($result);
			$file_update_query = '';
			
			foreach ($file_uploads_array as $element_name){
				$filename = $row[$element_name];
				
				if(empty($filename)){
					continue;
				}
				
				$target_filename 	  = $options['machform_data_path'].UPLOAD_DIR."/form_{$form_id}/files/{$filename}.tmp";
				
				$regex    = '/^element_([0-9]*)_([0-9a-zA-Z]*)-([0-9]*)-(.*)$/';
				$matches  = array();
				preg_match($regex, $filename,$matches);
				$filename_noelement = $matches[4];
				
				$file_token = md5(uniqid(rand(), true)); //add random token to uploaded filename, to increase security
				$destination_filename = $options['machform_data_path'].UPLOAD_DIR."/form_{$form_id}/files/{$element_name}_{$file_token}-{$new_record_id}-{$filename_noelement}";
				
				if(file_exists($target_filename)){
					rename($target_filename,$destination_filename);
				}
				
				//build update query
				$file_update_query .= "`{$element_name}`='{$element_name}_{$file_token}-{$new_record_id}-{$filename_noelement}',";
			}
			
			$file_update_query = rtrim($file_update_query,',');
			if(!empty($file_update_query)){
				do_query("UPDATE `ap_form_{$form_id}` SET {$file_update_query} WHERE id='{$new_record_id}'");
			}
		}
		
		//send notification emails
		//get form properties data
		$query 	= "select 
						 form_redirect,
						 form_email,
						 esl_from_name,
						 esl_from_email_address,
						 esl_subject,
						 esl_content,
						 esl_plain_text,
						 esr_email_address,
						 esr_from_name,
						 esr_from_email_address,
						 esr_subject,
						 esr_content,
						 esr_plain_text
				     from 
				     	 `ap_forms` 
				    where 
				    	 form_id='$form_id'";
		
		$result = do_query($query);
		$row 	= do_fetch_result($result);
		$form_redirect  = $row['form_redirect'];
		$form_email 	= $row['form_email'];
		
		$esl_from_name 	= $row['esl_from_name'];
		$esl_from_email_address = $row['esl_from_email_address'];
		$esl_subject 	= $row['esl_subject'];
		$esl_content 	= $row['esl_content'];
		$esl_plain_text	= $row['esl_plain_text'];
		
		$esr_email_address 	= $row['esr_email_address'];
		$esr_from_name 	= $row['esr_from_name'];
		$esr_from_email_address = $row['esr_from_email_address'];
		$esr_subject 	= $row['esr_subject'];
		$esr_content 	= $row['esr_content'];
		$esr_plain_text	= $row['esr_plain_text'];
		
		//start sending notification email to admin ------------------------------------------
		if(!empty($form_email)){
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
			
			send_notification($form_id,$new_record_id,$form_email,$admin_email_param);
    	
		}
		//end emailing notifications to admin ----------------------------------------------
		
		
		//start sending notification email to user ------------------------------------------
		if(!empty($esr_email_address)){
			//get parameters for the email
			
			//to email
			if(is_numeric($esr_email_address)){
				$esr_email_address = '{element_'.$esr_email_address.'}';
			}
					
			//from name
			if(!empty($esr_from_name)){
				$user_email_param['from_name'] = $esr_from_name;
			}elseif (NOTIFICATION_MAIL_FROM_NAME != ''){
				$user_email_param['from_name'] = NOTIFICATION_MAIL_FROM_NAME;
			}else{
				$user_email_param['from_name'] = 'MachForm';
			}
			
			//from email address
			if(!empty($esr_from_email_address)){
				if(is_numeric($esr_from_email_address)){
					$user_email_param['from_email'] = '{element_'.$esr_from_email_address.'}';
				}else{
					$user_email_param['from_email'] = $esr_from_email_address;
				}
			}elseif(NOTIFICATION_MAIL_FROM != ''){
				$user_email_param['from_email'] = NOTIFICATION_MAIL_FROM;
			}else{
				$domain = str_replace('www.','',$_SERVER['SERVER_NAME']);
				$user_email_param['from_email'] = "no-reply@{$domain}";
			}
			
			//subject
			if(!empty($esr_subject)){
				$user_email_param['subject'] = $esr_subject;
			}elseif (NOTIFICATION_MAIL_SUBJECT != ''){
				$user_email_param['subject'] = NOTIFICATION_MAIL_SUBJECT;
			}else{
				$user_email_param['subject'] = '{form_name} - Receipt';
			}
			
			//content
			if(!empty($esr_content)){
				$user_email_param['content'] = $esr_content;
			}else{
				$user_email_param['content'] = '{entry_data}';
			}
			
			$user_email_param['as_plain_text'] = $esr_plain_text;
			$user_email_param['target_is_admin'] = false; 
			
			send_notification($form_id,$new_record_id,$esr_email_address,$user_email_param);
		}
		//end emailing notifications to user ----------------------------------------------
		
		//delete all entry from this user in review table
		$session_id = session_id();
		do_query("DELETE FROM `ap_form_{$form_id}_review` where id='{$record_id}' or session_id='{$session_id}'");
		
		$commit_result['form_redirect'] = $form_redirect;
		
		return $commit_result;
	}
?>
