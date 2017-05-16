<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/	
	
	/* Constants for validation error message */
	define('VAL_REQUIRED',$lang['val_required']);
	define('VAL_REQUIRED_FILE',$lang['val_required_file']);
	define('VAL_UNIQUE',$lang['val_unique']);
	define('VAL_INTEGER',$lang['val_integer']);
	define('VAL_FLOAT',$lang['val_float']);
	define('VAL_NUMERIC',$lang['val_numeric']);
	define('VAL_MIN',$lang['val_min']);		
	define('VAL_MAX',$lang['val_max']);
	define('VAL_RANGE',$lang['val_range']);
	define('VAL_EMAIL',$lang['val_email']);
	define('VAL_WEBSITE',$lang['val_website']);
	define('VAL_USERNAME',$lang['val_username']);
	define('VAL_EQUAL',$lang['val_equal']);
	define('VAL_DATE',$lang['val_date']);
	define('VAL_TIME',$lang['val_time']);
	define('VAL_PHONE',$lang['val_phone']);
	define('VAL_FILETYPE',$lang['val_filetype']);
	
	//validation for required field
	function validate_required($value){
		
		$value = $value[0]; 
		if(empty($value) && (($value != 0) || ($value != '0'))){ //0  and '0' should not considered as empty
			return VAL_REQUIRED;
		}else{
			return true;
		}
	}	
	
	//validation for unique checking on db table
	function validate_unique($value){
		
		$input_value  = $value[0]; 
	
		$exploded = explode('#',$value[1]);
		$form_id  = $exploded[0];
		$element_name = $exploded[1];
		
		if(!empty($_SESSION['edit_entry']) && ($_SESSION['edit_entry']['form_id'] == $form_id)){
			//if admin is editing through edit_entry.php, bypass the unique checking if the new entry is the same as previous
			$result = do_query("select count($element_name) total from ap_form_{$form_id} where $element_name='$input_value' and `id` != '{$_SESSION['edit_entry']['entry_id']}'");
			$row = do_fetch_result($result);
		}else{
			$result = do_query("select count($element_name) total from ap_form_{$form_id} where $element_name='$input_value' ");
			$row = do_fetch_result($result);
		}
		
		if(!empty($row['total'])){ 
			return VAL_UNIQUE;
		}else{
			return true;
		}
	}	
	
		
	//validation for integer
	function validate_integer($value){
		
		$error_message = VAL_INTEGER;
		
		$value = $value[0];
		if(is_int($value)){
			return true; //it's integer
		}else if(is_float($value)){
			return $error_message; //it's float
		}else if(is_numeric($value)){
			$result = strpos($value,'.');
			if($result !== false){
				return $error_message; //it's float
			}else{
				return true; //it's integer
			}
		}else{
			return $error_message; //it's not even a number!
		}
	}
	
	//validation for float aka double
	function validate_float($value){
		
		$error_message = VAL_FLOAT;
		
		$value = $value[0];
		if(is_int($value)){
			return $error_message; //it's integer
		}else if(is_float($value)){
			return true; //it's float
		}else if(is_numeric($value)){
			$result = strpos($value,'.');
			if($result !== false){
				return true; //it's float
			}else{
				return $error_message; //it's integer
			}
		}else{
			return $error_message; //it's not even a number!
		}
	}
	
	//validation for numeric
	function validate_numeric($value){
		
		$error_message = VAL_NUMERIC;
				
		$value = $value[0];
		if(is_numeric($value)){
			return true;
		}else{
			return $error_message;
		}
		
	}
	
	//validation for phone (###) ### ####
	function validate_phone($value){
		
		$error_message = VAL_PHONE;
		
		if(!empty($value[0])){
			$regex  = '/^[1-9][0-9]{9}$/';
			$result = preg_match($regex, $value[0]);
			
			if(empty($result)){
				return $error_message;
			}else{
				return true;
			}
		}else{
			return true;
		}
		
	}
	
	//validation for simple phone, international phone
	function validate_simple_phone($value){
		
		$error_message = VAL_PHONE;
		
		if($value[0]{0} == '+'){
			$test_value = substr($value[0],1);
		}else{
			$test_value = $value[0];
		}
		
		if(is_numeric($test_value) && (strlen($test_value) > 3)){
			return true;
		}else{
			return $error_message;
		}
	}
	
	//validation for minimum value
	function validate_min($value){
		$error_message = VAL_MIN;
		
		if(strlen($value[0]) < $value[1]){
			return sprintf($error_message,$value[1]);
		}else{
			return true;
		}
	}
	
	//validation for maximum value
	function validate_max($value){
		
		$error_message = VAL_MAX;
		
		if(strlen($value[0]) > $value[1]){
			return sprintf($error_message,$value[1]);
		}else{
			return true;
		}
	}
	
	//validation for range value
	function validate_range($value){
		$error_message = VAL_RANGE;
		
		//$value[0] is the entered value to be compared
		//$value[1] contains the value range. eg '2-10'
		
		$exp_array = explode('-',$value[1]);
		$min = $exp_array[0];
		$max = $exp_array[1];
		
		
		if(!((strlen($value[0]) >= $min) && (strlen($value[0]) <= $max))){
			return sprintf($error_message,'%s',$min,$max);
		}else{
			return true;
		}
	}
	
	//validation to check email address format
	function validate_email($value) {
		$error_message = VAL_EMAIL;
		
		if(!empty($value[0])){
			$regex  = '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]*\.[A-z0-9]{2,6}$/';
			$result = preg_match($regex, $value[0]);
			
			if(empty($result)){
				return sprintf($error_message,'%s',$value[0]);
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	
	//validation to check URL format
	function validate_website($value) {
		$error_message = VAL_WEBSITE;
		$value[0] = $value[0].'/';
		
		if(!empty($value[0]) && ($value[0] != '/')){
			$regex  = '/^https?:\/\/([a-z0-9]([-a-z0-9]*[a-z0-9])?\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])(\/)(.*)$/i';
			$result = preg_match($regex, $value[0]);
			
			if(empty($result)){
				return sprintf($error_message,'%s',$value[0]);
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
	
	//validation to allow only a-z 0-9 and underscores 
	function validate_username($value){
		
		$error_message = VAL_USERNAME;
		
		if(!preg_match("/^[a-z0-9][\w]*$/i",$value[0])){
			return sprintf($error_message,'%s',$value[0]);
		}else{
			return true;
		}
	}
	
	
	
	//validation to check two variable equality. usefull for checking password 
	function validate_equal($value){
		$error_message = VAL_EQUAL;
		
		if($value[0] != $value[2][$value[1]]){
			return $error_message;
		}else{
			return true;
		}
	}
	
	//validate date format
	//currently only support this format: mm/dd/yyyy or mm-dd-yyyy, yyyy/mm/dd or yyyy-mm-dd
	function validate_date($value) {
		$error_message = VAL_DATE;
		
		if(!empty($value[0])){
			if($value[1] == 'yyyy/mm/dd'){
				$regex = "/^([1-9][0-9])\d\d[-\/](0?[1-9]|1[012])[-\/](0?[1-9]|[12][0-9]|3[01])$/";
			}elseif($value[1] == 'mm/dd/yyyy'){
				$regex = "/^(0[1-9]|1[012])[-\/](0[1-9]|[12][0-9]|3[01])[-\/](19|20)\d\d$/";
			}
			
			$result = preg_match($regex, $value[0]);
		}
		
		
		if(empty($result)){
			return sprintf($error_message,'%s',$value[1]);
		}else{
			return true;
		}
	}
	
	//validation to check valid time format 
	function validate_time($value){
		
		$error_message = VAL_TIME;
		
		$timestamp = strtotime($value[0]);
		
		if($timestamp == -1 || $timestamp === false){
			return $error_message;
		}else{
			return true;
		}
	}
	
	
	//validation for required file
	function validate_required_file($value){
		$error_message = VAL_REQUIRED_FILE;
		$element_file = $value[0];
		
		if($_FILES[$element_file]['size'] > 0){
			return true;
		}else{
			return $error_message;
		}
	}
	
	//validation for file upload filetype
	function validate_filetype($value){
		
		$error_message = VAL_FILETYPE;
		$value = $value[0];
		
		$ext = pathinfo(strtolower($_FILES[$value]['name']), PATHINFO_EXTENSION);
		
		if(defined('UPLOAD_FILETYPE_ALLOW') && (UPLOAD_FILETYPE_ALLOW != '')){
			//only allow these filetypes
			$allowed_filetypes = explode(';',strtolower(UPLOAD_FILETYPE_ALLOW));
			if(!in_array($ext,$allowed_filetypes)){
				return $error_message;
			}			
			
		}elseif(defined('UPLOAD_FILETYPE_DENY') && (UPLOAD_FILETYPE_DENY != '')){
			//disallow these filetypes
			$blacklisted_filetypes = explode(';',strtolower(UPLOAD_FILETYPE_DENY));
			if(in_array($ext,$blacklisted_filetypes)){
				return $error_message;
			}			
		}
		
		return true;
	}
	
	/*********************************************************
	* This is main validation function
	* This function will call sub function, called validate_xx
	* Each sub function is specific for one rule
	*
	* Syntax: $rules[field_name][validation_type] = value
	* validation_type: required,integer,float,min,max,range,email,username,equal,date
	* Example rules:
	*
	* $rules['author_id']['required'] = true; //author_id is required
	* $rules['author_id']['integer']  = true; //author_id must be an integer
	* $rules['author_id']['range']    = '2-10'; //author_id length must be between 2 - 10 characters
	*
	**********************************************************/
	function validate_rules($input,$rules){
		
		//traverse for each input, check for rules to be applied
		foreach ($input as $key=>$value){
			$current_rules = @$rules[$key];
			$error_message = array();
			
			if(!empty($current_rules)){
				//an input can be validated by many rules, check that here
				foreach ($current_rules as $key2=>$value2){
					$argument_array = array($value,$value2,$input);
					$result = call_user_func('validate_'.$key2,$argument_array);
					
					if($result !== true){ //if we got error message, break the loop
						$error_message = $result;
						break;
					}
				}
			}
			if(count($error_message) > 0){
				$total_error_message[$key] = $error_message;
			}
		}
		
		if(@is_array($total_error_message)){
			return $total_error_message;
		}else{
			return true;
		}
	}
	
	//similar as function above, but this is specific for validating form inputs, with only one error message per input
	function validate_element($input,$rules){
		
		//traverse for each input, check for rules to be applied
		foreach ($input as $key=>$value){
			$current_rules = @$rules[$key];
			$error_message = array();
			
			if(!empty($current_rules)){
				//an input can be validated by many rules, check that here
				foreach ($current_rules as $key2=>$value2){
					$argument_array = array($value,$value2,$input);
					$result = call_user_func('validate_'.$key2,$argument_array);
					
					if($result !== true){ //if we got error message, break the loop
						$error_message = $result;
						break;
					}
				}
			}
			if(count($error_message) > 0){
				$last_error_message = $error_message;
				break;
			}
		}
		
		if(!empty($last_error_message)){
			return $last_error_message;
		}else{
			return true;
		}
	}
?>
