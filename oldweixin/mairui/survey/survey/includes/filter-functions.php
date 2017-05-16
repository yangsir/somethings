<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/	
	//filters input array
	function ap_sanitize_input($input,$escape_mysql=false,$sanitize_html=false,$sanitize_special_chars=false,$allowable_tags=''){
		unset($input['submit']); //we use 'submit' variable for all of our form
				
		$input_array = $input;
		
		//array is not referenced when passed into foreach
		//this is why we create another exact array
		foreach ($input as $key=>$value){
			if(!empty($value)){
				
				//stripslashes added by magic quotes
				if(get_magic_quotes_gpc()){
					$input_array[$key] = stripslashes($input_array[$key]);
				}	
				
				if($sanitize_html){
					$input_array[$key] = strip_tags($input_array[$key],$allowable_tags);
				}
				
				if($sanitize_special_chars){
					$input_array[$key] = htmlspecialchars($input_array[$key]);
				}				
				
				if($escape_mysql){
					$input_array[$key] = mysql_real_escape_string($input_array[$key]);
				}
			}
		}
		
		return $input_array;
	
	}

?>
