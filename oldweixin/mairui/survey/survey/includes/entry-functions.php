<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	//get an array containing values from respective table for certain id
	function get_entry_values($form_id,$entry_id,$use_review_table=false,$options=array()){

		if($use_review_table){
			$table_suffix = '_review';
		}else{
			$table_suffix = '';
		}
			
		//get form elements	
		$query  = "select element_id,element_type,element_constraint from `ap_form_elements` where form_id='$form_id' order by element_position asc";
		$result = do_query($query);
		$i=0;
		while($row = do_fetch_result($result)){
			$form_elements[$i]['element_id'] 		 = $row['element_id'];
			$form_elements[$i]['element_type'] 		 = $row['element_type'];
			$form_elements[$i]['element_constraint'] = $row['element_constraint'];
			$i++;
		}
		
		//get whole entry for current id
		$query  = "select * from `ap_form_{$form_id}{$table_suffix}` where id='$entry_id' limit 1";
		$result = do_query($query);
		
		//get actual field name from selected table
		$i = 0;
		$fields_num = mysql_num_fields($result);
		while($i < $fields_num){
			$meta = mysql_fetch_field($result, $i);
			$columns[$i] = $meta->name;
			$i++;
		}
		
		$row = do_fetch_result($result);
		//store the result into array
		for($i=0;$i<$fields_num;$i++){
			$column_name = $columns[$i];
			$entry_data[$column_name] = htmlspecialchars($row[$column_name],ENT_QUOTES);
		}
		
		
		//get form element options
		$query = "select element_id,option_id,`option` from ap_element_options where form_id='$form_id' and live=1";
		$result = do_query($query);
		while($row = do_fetch_result($result)){
			$element_id = $row['element_id'];
			$option_id  = $row['option_id'];
			
			$element_option_lookup[$element_id][$option_id] = true; //array index will hold option_id
		}
		
		
		//loop through each element to get the values
		foreach ($form_elements as $element){
			$element_type 		= $element['element_type'];
			$element_id   		= $element['element_id'];
			$element_constraint = $element['element_constraint'];
		
			if('simple_name' == $element_type){ //Simple Name - 2 elements
				$form_values['element_'.$element_id.'_1']['default_value'] = $entry_data['element_'.$element_id.'_1'];
				$form_values['element_'.$element_id.'_2']['default_value'] = $entry_data['element_'.$element_id.'_2'];
			}elseif ('name' == $element_type){ //Extended Name - 4 elements
				$form_values['element_'.$element_id.'_1']['default_value'] = $entry_data['element_'.$element_id.'_1'];
				$form_values['element_'.$element_id.'_2']['default_value'] = $entry_data['element_'.$element_id.'_2'];
				$form_values['element_'.$element_id.'_3']['default_value'] = $entry_data['element_'.$element_id.'_3'];
				$form_values['element_'.$element_id.'_4']['default_value'] = $entry_data['element_'.$element_id.'_4'];
			}elseif ('time' == $element_type){ //Time - 4 elements
				//convert into time and split into 4 elements
				if(!empty($entry_data['element_'.$element_id])){
					$time_value = $entry_data['element_'.$element_id];
					$time_value = date("h/i/s/A",strtotime($time_value));
					
					$exploded = array();
					$exploded = explode('/',$time_value);
					
					$form_values['element_'.$element_id.'_1']['default_value'] = $exploded[0];
					$form_values['element_'.$element_id.'_2']['default_value'] = $exploded[1];
					$form_values['element_'.$element_id.'_3']['default_value'] = $exploded[2];
					$form_values['element_'.$element_id.'_4']['default_value'] = $exploded[3];
				}
			}elseif ('address' == $element_type){ //Address - 6	 elements
				$form_values['element_'.$element_id.'_1']['default_value'] = $entry_data['element_'.$element_id.'_1'];
				$form_values['element_'.$element_id.'_2']['default_value'] = $entry_data['element_'.$element_id.'_2'];
				$form_values['element_'.$element_id.'_3']['default_value'] = $entry_data['element_'.$element_id.'_3'];
				$form_values['element_'.$element_id.'_4']['default_value'] = $entry_data['element_'.$element_id.'_4'];
				$form_values['element_'.$element_id.'_5']['default_value'] = $entry_data['element_'.$element_id.'_5'];
				$form_values['element_'.$element_id.'_6']['default_value'] = $entry_data['element_'.$element_id.'_6'];
			}elseif ('money' == $element_type){ //Price
				if($element_constraint == 'yen'){ //yen only has 1 element
					$form_values['element_'.$element_id]['default_value'] = $entry_data['element_'.$element_id];
				}else{ //other has 2 fields
					$exploded = array();
					$exploded = explode('.',$entry_data['element_'.$element_id]);
					
					$form_values['element_'.$element_id.'_1']['default_value'] = $exploded[0];
					$form_values['element_'.$element_id.'_2']['default_value'] = $exploded[1];
				}
						
			}elseif ('date' == $element_type){  //date with format MM/DD/YYYY
				if(!empty($entry_data['element_'.$element_id])){
					$date_value = $entry_data['element_'.$element_id];
					$date_value = date("m/d/Y",strtotime($date_value));
					
					$exploded = array();
					$exploded = explode('/',$date_value);
	
					$form_values['element_'.$element_id.'_1']['default_value'] = $exploded[0];
					$form_values['element_'.$element_id.'_2']['default_value'] = $exploded[1];
					$form_values['element_'.$element_id.'_3']['default_value'] = $exploded[2];
				}
				
			}elseif ('europe_date' == $element_type){  //date with format DD/MM/YYYY
				if(!empty($entry_data['element_'.$element_id])){
					$date_value = $entry_data['element_'.$element_id];
					$date_value = date("d/m/Y",strtotime($date_value));
					
					$exploded = array();
					$exploded = explode('/',$date_value);
	
					$form_values['element_'.$element_id.'_1']['default_value'] = $exploded[0];
					$form_values['element_'.$element_id.'_2']['default_value'] = $exploded[1];
					$form_values['element_'.$element_id.'_3']['default_value'] = $exploded[2];
				}
				
			}elseif ('phone' == $element_type){ //Phone - 3 elements
				
				$phone_value = $entry_data['element_'.$element_id];
				$phone_1 = substr($phone_value,0,3);
				$phone_2 = substr($phone_value,3,3);
				$phone_3 = substr($phone_value,-4);
				
				$form_values['element_'.$element_id.'_1']['default_value'] = $phone_1;
				$form_values['element_'.$element_id.'_2']['default_value'] = $phone_2;
				$form_values['element_'.$element_id.'_3']['default_value'] = $phone_3;
				
			}elseif ('checkbox' == $element_type){ //Checkbox - multiple elements
				$checkbox_childs = $element_option_lookup[$element_id];
				
				foreach ($checkbox_childs as $option_id=>$dumb){
					$form_values['element_'.$element_id.'_'.$option_id]['default_value'] = $entry_data['element_'.$element_id.'_'.$option_id];
				}
				
			}elseif ('file' == $element_type){ //File 
			
				$filename_value = $entry_data['element_'.$element_id];
				
				if(!empty($filename_value)){
					$file_1 	    =  substr($filename_value,strpos($filename_value,'-')+1);
					$filename_value = substr($file_1,strpos($file_1,'-')+1);
					
					//encode the long query string for more readibility
					$q_string = base64_encode("form_id={$form_id}&id={$entry_id}&el=element_{$element_id}");
	
								
					//special for file,just provide a markup to download or delete the file
					if($use_review_table == false){
						$form_values['element_'.$element_id]['default_value'] =<<<EOT
							<img src="images/icons/attach.gif" align="absmiddle" />&nbsp;<b>{$filename_value}</b>&nbsp;&nbsp; 
							<a style="font-size: 85%;" href="download.php?q={$q_string}">Download</a>&nbsp;| 
							<a style="font-size: 85%;" href="edit_entry.php?form_id={$form_id}&id={$entry_id}&delete_file={$element_id}">Delete</a>
EOT;
					}else{ //if using review table, only show attached filename and delete file
						if(strpos($_SERVER['REQUEST_URI'],'?') === false){
							$href_url = $_SERVER['REQUEST_URI']."?delete_file={$element_id}";
						}else{
							$href_url = $_SERVER['REQUEST_URI']."&delete_file={$element_id}";
						}
					
						$form_values['element_'.$element_id]['default_value'] =<<<EOT
							<img src="{$options['machform_path']}images/icons/attach.gif" align="absmiddle" />&nbsp;<b>{$filename_value}</b>&nbsp;&nbsp; 
							<a style="font-size: 85%;" href="{$href_url}">Delete</a>
EOT;
					}
				}
			}else{ //element with only 1 input
				$form_values['element_'.$element_id]['default_value'] = $entry_data['element_'.$element_id];
			}
			
		}
		
		
		return $form_values;	
					
		
	}

	//get an array containing values from respective table for certain id
	//similar to get_entry_values() function, but this one is higher level and include labels
	function get_entry_details($form_id,$entry_id,$options=array()){
		
		$admin_clause = '';
		if(!empty($options['review_mode'])){ //hide admin fields in review page
			$admin_clause = ' and element_is_private=0 ';
		}
		
		//get form elements	
		$query  = "select 
						 element_id,
						 element_type,
						 element_constraint,
						 element_title 
					 from 
					 	 `ap_form_elements` 
					where 
						 form_id='$form_id' and 
						 element_type <> 'section'
						 {$admin_clause} 
				 order by 
				 		 element_position asc";
		$result = do_query($query);
		$i=0;
		while($row = do_fetch_result($result)){
			$form_elements[$i]['element_id'] 		 = $row['element_id'];
			$form_elements[$i]['element_type'] 		 = $row['element_type'];
			$form_elements[$i]['element_constraint'] = $row['element_constraint'];
			
			//store element title into array for reference later
			$element_title_lookup[$row['element_id']] = $row['element_title'];
			
			$i++;
		}
		
		if(!empty($options['review_mode'])){
			$table_suffix = '_review';
		}else{
			$table_suffix = '';
		}
		
		//get whole entry for current id
		$query  = "select * from `ap_form_{$form_id}{$table_suffix}` where id='$entry_id' limit 1";
		$result = do_query($query);
		
		//get actual field name from selected table
		$i = 0;
		$fields_num = mysql_num_fields($result);
		while($i < $fields_num){
			$meta = mysql_fetch_field($result, $i);
			$columns[$i] = $meta->name;
			$i++;
		}
		
		$row = do_fetch_result($result);
		//store the result into array
		for($i=0;$i<$fields_num;$i++){
			$column_name = $columns[$i];
			$entry_data[$column_name] = htmlspecialchars($row[$column_name],ENT_QUOTES);
		}
		
		
		//get form element options
		$query = "select element_id,option_id,`option` from ap_element_options where form_id='$form_id' and live=1 order by position asc";
		$result = do_query($query);
		while($row = do_fetch_result($result)){
			$element_id = $row['element_id'];
			$option_id  = $row['option_id'];
			
			$element_option_lookup[$element_id][$option_id] = $row['option']; //array index will hold option_id
		}
		
		
		
		
		//loop through each element to get the values
		$i = 0;
		foreach ($form_elements as $element){
			$element_type 		= $element['element_type'];
			$element_id   		= $element['element_id'];
			$element_constraint = $element['element_constraint'];
		
			$entry_details[$i]['label'] = $element_title_lookup[$element_id];
			$entry_details[$i]['value'] = '&nbsp;'; //default value
			$entry_details[$i]['element_id'] 	= $element_id;
			$entry_details[$i]['element_type'] 	= $element_type;
			
			
			if('simple_name' == $element_type){ //Simple Name - 2 elements
				$simple_name_value = trim($entry_data['element_'.$element_id.'_1'].' '.$entry_data['element_'.$element_id.'_2']);
				if(!empty($simple_name_value)){
					$entry_details[$i]['value'] = $simple_name_value;
				}
			}elseif ('name' == $element_type){ //Extended Name - 4 elements
				$name_value = trim($entry_data['element_'.$element_id.'_1'].' '. $entry_data['element_'.$element_id.'_2'].' '.$entry_data['element_'.$element_id.'_3'].' '.$entry_data['element_'.$element_id.'_4']);
				if(!empty($name_value)){
					$entry_details[$i]['value'] = $name_value;
				}
			}elseif ('time' == $element_type){ //Time - 4 elements
				//convert into time and split into 4 elements
				if(!empty($entry_data['element_'.$element_id])){
					$time_value = $entry_data['element_'.$element_id];
					
					if($element_constraint == 'show_seconds'){
						$time_value = date("h:i:s A",strtotime($time_value));
					}else{
						$time_value = date("h:i A",strtotime($time_value));
					}
					
					$entry_details[$i]['value'] = $time_value;
				}
			}elseif ('address' == $element_type){ //Address - 6	 elements
								
				if(!empty($entry_data['element_'.$element_id.'_3'])){
					$entry_data['element_'.$element_id.'_3'] = $entry_data['element_'.$element_id.'_3'].',';
				}
				
				$entry_details[$i]['value'] = $entry_data['element_'.$element_id.'_1'].' '.$entry_data['element_'.$element_id.'_2'].'<br />'.$entry_data['element_'.$element_id.'_3'].' '.$entry_data['element_'.$element_id.'_4'].' '.$entry_data['element_'.$element_id.'_5'].'<br />'.$entry_data['element_'.$element_id.'_6'];
				
				//if empty, shows blank instead of breaks
				if(trim(str_replace("<br />","",$entry_details[$i]['value'])) == ""){
					$entry_details[$i]['value'] = '&nbsp;';
				}
											  
			}elseif ('money' == $element_type){ //Price
				switch ($element_constraint){
					case 'pound'  : $currency = '&#163;';break;
					case 'euro'   : $currency = '&#8364;';break;
					case 'yen' 	  : $currency = '&#165;';break;
					default : $currency = '$';break;	
				}
				
				if(!empty($entry_data['element_'.$element_id]) || $entry_data['element_'.$element_id] === 0 || $entry_data['element_'.$element_id] === '0'){
					$entry_details[$i]['value'] = $currency.$entry_data['element_'.$element_id];
				}
						
			}elseif ('date' == $element_type){  //date with format MM/DD/YYYY
				if(!empty($entry_data['element_'.$element_id])){
					$date_value = $entry_data['element_'.$element_id];
					$date_value = date("m/d/Y",strtotime($date_value));
					
					$entry_details[$i]['value'] = $date_value;
				}
				
			}elseif ('europe_date' == $element_type){  //date with format DD/MM/YYYY
				if(!empty($entry_data['element_'.$element_id])){
					$date_value = $entry_data['element_'.$element_id];
					$date_value = date("d/m/Y",strtotime($date_value));
					
					$entry_details[$i]['value'] = $date_value;
				}
				
			}elseif ('phone' == $element_type){ //Phone - 3 elements
				
				$phone_value = $entry_data['element_'.$element_id];
				$phone_1 = substr($phone_value,0,3);
				$phone_2 = substr($phone_value,3,3);
				$phone_3 = substr($phone_value,-4);
				
				if(!empty($phone_value)){
					$entry_details[$i]['value'] = "($phone_1) - $phone_2 - $phone_3";
				}
							
			}elseif ('checkbox' == $element_type){ //Checkbox - multiple elements
				$checkbox_childs = $element_option_lookup[$element_id];
								
				$checkbox_content = '';
				foreach ($checkbox_childs as $option_id=>$option_label){
					if(!empty($entry_data['element_'.$element_id.'_'.$option_id])){
						if(empty($options['strip_checkbox_image'])){
							$checkbox_content .= '<img src="'.$options['machform_path'].'images/icons/checkbox_16.gif" align="absmiddle" /> '.$option_label.'<br />';
						}else{
							$checkbox_content .= '- '.$option_label.'<br />';
						}
					}
				}
				
				if(!empty($checkbox_content)){
					$entry_details[$i]['value'] = $checkbox_content;
				}
			}elseif ('file' == $element_type){ //File 
			
				$filename_value = $entry_data['element_'.$element_id];
				
				if(!empty($filename_value)){
					$file_1 	    =  substr($filename_value,strpos($filename_value,'-')+1);
					$filename_value = substr($file_1,strpos($file_1,'-')+1);
					
					//encode the long query string for more readibility
					$q_string = base64_encode("form_id={$form_id}&id={$entry_id}&el=element_{$element_id}");
					
					if(!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')){
						$ssl_suffix = 's';
					}else{
						$ssl_suffix = '';
					}
						
					//special for file,just provide a markup to download or delete the file
					$entry_details[$i]['value'] = '<img src="'.$options['machform_path'].'images/icons/attach.gif" align="absmiddle" />&nbsp;<a class="entry_link" href="http'.$ssl_suffix.'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/download.php?q='.$q_string.'">'.$filename_value.'</a>';
					
					if(!empty($options['strip_download_link'])){
						$entry_details[$i]['value'] = $filename_value;
					}
					
					if(!empty($options['show_attach_image'])){
						$entry_details[$i]['value'] = '<img src="'.$options['machform_path'].'images/icons/attach.gif" align="absmiddle" />&nbsp;'.$filename_value;
					}
				}
			}elseif('select' == $element_type || 'radio' == $element_type){
				if(!empty($entry_data['element_'.$element_id])){
					$entry_details[$i]['value'] = $element_option_lookup[$element_id][$entry_data['element_'.$element_id]];
				}
			}elseif ('url' == $element_type){
				if(!empty($entry_data['element_'.$element_id])){
					$entry_details[$i]['value'] = "<a class=\"entry_link\" href=\"{$entry_data['element_'.$element_id]}\">{$entry_data['element_'.$element_id]}</a>";
				}
			}else{ //element with only 1 input
				if(isset($entry_data['element_'.$element_id])){
					$entry_details[$i]['value'] = $entry_data['element_'.$element_id];
				}
			}
			
			$i++;
		}
		
		return $entry_details;
	}
	
	
	//delete a file element from an entry
	function delete_file_entry($form_id,$entry_id,$element_id){
		//delete actual file
		$query = "select element_{$element_id} from `ap_form_{$form_id}` where id='$entry_id'";
		$result = do_query($query);
		$row = do_fetch_result($result);
		$filename = $row['element_'.$element_id];
		@unlink(UPLOAD_DIR."/form_{$form_id}/files/".$filename);
				
		//delete from table
		$query = "update `ap_form_{$form_id}` set element_{$element_id}=NULL where id='$entry_id'";
		do_query($query);
	}
	
	//delete a file element from an entry in review table
	function delete_review_file_entry($form_id,$entry_id,$element_id,$options=array()){
		//delete actual file
		$query = "select element_{$element_id} from `ap_form_{$form_id}_review` where id='$entry_id'";
		$result = do_query($query);
		$row = do_fetch_result($result);
		$filename = $row['element_'.$element_id].'.tmp';
		@unlink($options['machform_data_path'].UPLOAD_DIR."/form_{$form_id}/files/".$filename);
				
		//delete from table
		$query = "update `ap_form_{$form_id}_review` set element_{$element_id}=NULL where id='$entry_id'";
		do_query($query);
	}
	
	//delete multiple entries and related uploads (if any)
	function delete_entries($form_id,$entries_id){
		
		//delete from table
		$deleted_entry_id_joined = implode("','",$entries_id);
		do_query("delete from `ap_form_{$form_id}` where id in('{$deleted_entry_id_joined}')");
		
		
		//get the element id for file fields
		$query = "select element_id from ap_form_elements where element_type='file' and form_id='{$form_id}'";
		$result = do_query($query);
		while($row = do_fetch_result($result)){
			$file_element_id_array[] = $row['element_id'];
		}
		
		//delete the files from data folder
		if(!empty($file_element_id_array)){
			foreach ($entries_id as $entry_id){
				foreach ($file_element_id_array as $element_id){
					$filename = array();
					$filename = glob(UPLOAD_DIR."/form_{$form_id}/files/element_{$element_id}_*-{$entry_id}-*");
					@unlink($filename[0]);
				}
			}
		}
						
		return true;
	}
	
?>
