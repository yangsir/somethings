<?php
	/** ap_forms table ************************************/
	function ap_forms_insert($data){
		
		/** start validation **/
		//1.common validation
		$rules['form_id']['required'] = true;
					
		$error_message = validate_rules($data,$rules);
		if(is_array($error_message)){
			return $error_message;
		}
				
		/** end validation **/
		
		$field_list = '';
		$field_values = '';
		
		//dynamically create the field list and field values, based on the input given
		foreach ($data as $key=>$value){
			$value		    = mysql_real_escape_string($value);
			$field_list    .= "`$key`,";
			$field_values  .= "'$value',";
		}
		
		$field_list   = substr($field_list,0,-1);
		$field_values = substr($field_values,0,-1);
		
		$query = "INSERT INTO `ap_forms` ($field_list) VALUES ($field_values);"; 
		$result = mysql_query($query);
		if(!$result){
			$error_message = 'Query failed! Error code: '.mysql_errno().' - '.mysql_error().' - Query: '.$query;
			return $error_message;
		}
		
		return true;
	}
	
	function ap_forms_update($id,$data){
		
		/** start validation **/
		//1.common validation
		$rules['form_name']['max'] = 50;
		
			
		$error_message = validate_rules($data,$rules);
		if(is_array($error_message)){
			return $error_message;
		}
				
		/** end validation **/
		
		$update_values = '';
		
		//dynamically create the sql update string, based on the input given
		foreach ($data as $key=>$value){
			$value = mysql_real_escape_string($value);
			$update_values .= "`$key`='$value',";
		}
		$update_values = substr($update_values,0,-1);
		
		$query = "UPDATE `ap_forms` set 
									$update_values
							  where 
						  	  		form_id='$id';";
		
		$result = mysql_query($query);
		if(!$result){
			$error_message = 'Query failed! Error code: '.mysql_errno().' - '.mysql_error().' - Query: '.$query;
			return $error_message;
		}
		
		return true;
	}
	
	/** ap_form_elements table ************************************/
	function ap_form_elements_insert($data){
		
		/** start validation **/
		//1.common validation
		$rules['form_id']['required'] = true;
		$rules['element_id']['required'] = true;
					
		$error_message = validate_rules($data,$rules);
		if(is_array($error_message)){
			return $error_message;
		}
				
		/** end validation **/
		
		$field_list = '';
		$field_values = '';
		
		//dynamically create the field list and field values, based on the input given
		foreach ($data as $key=>$value){
			$value		    = mysql_real_escape_string($value);
			$field_list    .= "`$key`,";
			$field_values  .= "'$value',";
		}
		
		$field_list   = substr($field_list,0,-1);
		$field_values = substr($field_values,0,-1);
		
		$query = "INSERT INTO `ap_form_elements` ($field_list) VALUES ($field_values);"; 
		$result = mysql_query($query);
		if(!$result){
			$error_message = 'Query failed! Error code: '.mysql_errno().' - '.mysql_error().' - Query: '.$query;
			return $error_message;
		}
		
		return true;
	}
	
	function ap_form_elements_update($form_id,$element_id,$data){
		
		/** start validation **/
		//1.common validation
		$rules['form_id']['required'] = true;
		$rules['element_id']['required'] = true;
		
			
		$error_message = validate_rules($data,$rules);
		if(is_array($error_message)){
			return $error_message;
		}
				
		/** end validation **/
		
		$update_values = '';
		
		//dynamically create the sql update string, based on the input given
		foreach ($data as $key=>$value){
			$value = mysql_real_escape_string($value);
			$update_values .= "`$key`='$value',";
		}
		$update_values = substr($update_values,0,-1);
		
		$query = "UPDATE `ap_form_elements` set 
									$update_values
							  where 
						  	  		form_id='$form_id' and element_id='$element_id';";
		
		$result = mysql_query($query);
		if(!$result){
			$error_message = 'Query failed! Error code: '.mysql_errno().' - '.mysql_error().' - Query: '.$query;
			return $error_message;
		}
		
		return true;
	}
	
	/** ap_element_options table ************************************/
	function ap_element_options_insert($data){
		
		/** start validation **/
		//1.common validation
		$rules['form_id']['required'] = true;
		$rules['element_id']['required'] = true;
		$rules['option_id']['required'] = true;
					
		$error_message = validate_rules($data,$rules);
		if(is_array($error_message)){
			return $error_message;
		}
				
		/** end validation **/
		
		$field_list = '';
		$field_values = '';
		
		//dynamically create the field list and field values, based on the input given
		foreach ($data as $key=>$value){
			$value		    = mysql_real_escape_string($value);
			$field_list    .= "`$key`,";
			$field_values  .= "'$value',";
		}
		
		$field_list   = substr($field_list,0,-1);
		$field_values = substr($field_values,0,-1);
		
		$query = "INSERT INTO `ap_element_options` ($field_list) VALUES ($field_values);"; 
		
		$result = mysql_query($query);
		if(!$result){
			$error_message = 'Query failed! Error code: '.mysql_errno().' - '.mysql_error().' - Query: '.$query;
			return $error_message;
		}
		
		return true;
	}
	
	function ap_element_options_update($form_id,$element_id,$option_id,$data){
		
		/** start validation **/
		//1.common validation
		$rules['form_id']['required'] = true;
		$rules['element_id']['required'] = true;
		$rules['option_id']['required'] = true;
					
		$error_message = validate_rules($data,$rules);
		if(is_array($error_message)){
			return $error_message;
		}
				
		/** end validation **/
		
		$update_values = '';
		
		//dynamically create the sql update string, based on the input given
		foreach ($data as $key=>$value){
			$value = mysql_real_escape_string($value);
			$update_values .= "`$key`='$value',";
		}
		$update_values = substr($update_values,0,-1);
		
		$query = "UPDATE `ap_element_options` set 
									$update_values
							  where 
						  	  		form_id='$form_id' and element_id='$element_id' and option_id='$option_id';";
		
		$result = mysql_query($query);
		if(!$result){
			$error_message = 'Query failed! Error code: '.mysql_errno().' - '.mysql_error().' - Query: '.$query;
			return $error_message;
		}
		
		return true;
	}
?>
