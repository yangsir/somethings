<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	session_start();
	
	ini_set("include_path", './lib/pear/'.PATH_SEPARATOR.ini_get("include_path"));
	ini_set("max_execution_time",1800);
	
	
	require('config.php');
	require('includes/language.php');
	require('includes/check-session.php');
	require('includes/db-core.php');
	
	connect_db();

	$form_id = (int) trim($_GET['id']);
	$type    = trim($_GET['type']);
	
	if(empty($form_id)){
		die("Invalid form ID.");
	}
	
	
		
	//get form name
	$query = "select form_name from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
		
	//get form element options
	$query = "select element_id,option_id,`option` from ap_element_options where form_id='$form_id' and live=1";
	$result = do_query($query);
	while($row = do_fetch_result($result)){
		$element_id = $row['element_id'];
		$option_id  = $row['option_id'];
		
		$element_option_lookup[$element_id][$option_id] = $row['option'];
	}
		
	/******************************************************************************************/
	//prepare column header names lookup
	$query  = "select element_id,element_title,element_type,element_constraint from `ap_form_elements` where form_id='$form_id' order by element_position asc";
	$result = do_query($query);
	
	while($row = do_fetch_result($result)){
		$element_type = $row['element_type'];
		$element_constraint = $row['element_constraint'];
				
		
		if('address' == $element_type){ //address has 6 fields
			$column_name_lookup['element_'.$row['element_id'].'_1'] = $row['element_title'].' - '.$lang['address_street'];
			$column_name_lookup['element_'.$row['element_id'].'_2'] = $lang['address_street2'];
			$column_name_lookup['element_'.$row['element_id'].'_3'] = $lang['address_city'];
			$column_name_lookup['element_'.$row['element_id'].'_4'] = $lang['address_state'];
			$column_name_lookup['element_'.$row['element_id'].'_5'] = $lang['address_zip'];
			$column_name_lookup['element_'.$row['element_id'].'_6'] = $lang['address_country'];
			
			$column_type_lookup['element_'.$row['element_id'].'_1'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_2'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_3'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_4'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_5'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_6'] = $row['element_type'];
			
		}elseif ('simple_name' == $element_type){ //simple name has 2 fields
			$column_name_lookup['element_'.$row['element_id'].'_1'] = $row['element_title'].' - '.$lang['name_first'];
			$column_name_lookup['element_'.$row['element_id'].'_2'] = $row['element_title'].' - '.$lang['name_last'];
			
			$column_type_lookup['element_'.$row['element_id'].'_1'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_2'] = $row['element_type'];
			
		}elseif ('name' == $element_type){ //name has 4 fields
			$column_name_lookup['element_'.$row['element_id'].'_1'] = $row['element_title'].' - '.$lang['name_title'];
			$column_name_lookup['element_'.$row['element_id'].'_2'] = $row['element_title'].' - '.$lang['name_first'];
			$column_name_lookup['element_'.$row['element_id'].'_3'] = $row['element_title'].' - '.$lang['name_last'];
			$column_name_lookup['element_'.$row['element_id'].'_4'] = $row['element_title'].' - '.$lang['name_suffix'];
			
			$column_type_lookup['element_'.$row['element_id'].'_1'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_2'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_3'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_4'] = $row['element_type'];
			
		}elseif('money' == $element_type){//money format
			$column_name_lookup['element_'.$row['element_id']] = $row['element_title'];
			if(!empty($element_constraint)){
				$column_type_lookup['element_'.$row['element_id']] = $element_constraint; //euro, pound, yen
			}else{
				$column_type_lookup['element_'.$row['element_id']] = 'dollar'; //default is dollar
			}
		}elseif('checkbox' == $element_type){ //checkboxes, get childs elements
						
			$this_checkbox_options = $element_option_lookup[$row['element_id']];
			foreach ($this_checkbox_options as $option_id=>$option){
				$column_name_lookup['element_'.$row['element_id'].'_'.$option_id] = $option;
				$column_type_lookup['element_'.$row['element_id'].'_'.$option_id] = $row['element_type'];
			}
		}elseif ('time' == $element_type){
			if($element_constraint == 'show_seconds'){
				$column_type_lookup['element_'.$row['element_id']] = $row['element_type'];
			}else{
				$column_type_lookup['element_'.$row['element_id']] = 'time_noseconds';
			}
			
			$column_name_lookup['element_'.$row['element_id']] = $row['element_title'];
		}else{ //for other elements with only 1 field
			$column_name_lookup['element_'.$row['element_id']] = $row['element_title'];
			$column_type_lookup['element_'.$row['element_id']] = $row['element_type'];
		}
	}
	/******************************************************************************************/
	

	
	
	//set column properties for basic fields
	$column_name_lookup['id'] 			= $lang['export_num'];
	$column_name_lookup['date_created'] = $lang['date_created'];
	$column_name_lookup['date_updated'] = $lang['date_updated'];
	$column_name_lookup['ip_address'] 	= $lang['ip_address'];
	
	$column_type_lookup['id'] 			= 'number';
	$column_type_lookup['date_created'] = 'text';
	$column_type_lookup['date_updated'] = 'text';
	$column_type_lookup['ip_address'] 	= 'text';


	/******************************************************************************************/
	//prepare headers
	if($type == 'xls'){
		require('Spreadsheet/Excel/Writer.php');
		
		// Creating a workbook
		$workbook = new Spreadsheet_Excel_Writer();
		
		$workbook->setTempDir(DATA_DIR);
		
		// sending HTTP headers
		$clean_form_name = ereg_replace("[^a-zA-Z0-9_-]", "",$form_name);
		$workbook->send("{$clean_form_name}.xls");
		
		if(function_exists('iconv')){
			$workbook->setVersion(8); 
		}
		
		// Creating a worksheet
		$clean_form_name = substr($clean_form_name,0,30); //must be less than 31 characters
		$worksheet =& $workbook->addWorksheet($clean_form_name);
		
		$format_bold =& $workbook->addFormat();
		$format_bold->setBold();
		$format_bold->setFgColor(22);
		$format_bold->setPattern(1);
		$format_bold->setBorder(1);
						
		if(function_exists('iconv')){
			$worksheet->setInputEncoding('UTF-8');
		}
		
	}elseif ($type == 'csv'){
		require('Compat/Function/fputcsv.php');
		
		$clean_form_name = ereg_replace("[^a-zA-Z0-9_-]", "",$form_name);
		
		//Prepare headers
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public", false);
        header("Content-Description: File Transfer");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"{$clean_form_name}.csv\"");
        
        $out = fopen('php://output', 'w');
	}
	
	/******************************************************************************************/
	
	//get column labels, order in the correct position
	$query  = "select * from `ap_form_{$form_id}` limit 1"; //limit 1 being used so that we could get the headers
	$result = do_query($query);
	
	//get actual field name from selected table
	$i = 0;
	$fields_num = mysql_num_fields($result);
	while($i < $fields_num){
		$meta = mysql_fetch_field($result, $i);
		$columns[$i] = $meta->name;
		$i++;
	}
	
	
	$temp_columns = array_slice($columns,0,4);
		
	//first, reorder columns into the correct position
    $query = "select 
    				element_id,
    				element_total_child,
    				element_type 
    			from 
    				`ap_form_elements`
    		   where 
    		   		form_id='$form_id' and element_type != 'section'
    		order by 
    				element_position asc";
    $result = do_query($query);
			
    while($row = do_fetch_result($result)){
		$element_id   = $row['element_id'];
		$total_child  = $row['element_total_child'];
		$element_type = $row['element_type'];
				
		if($total_child > 0){
					
			$max = $total_child + 1;
			for($i=0;$i<=$max;$i++){
				if($i == 0){
					if(in_array('element_'.$element_id,$columns)){
						$temp_columns[] = 'element_'.$element_id;
					}
				}else{
							
					if(in_array('element_'.$element_id.'_'.$i,$columns)){
						$temp_columns[] = 'element_'.$element_id.'_'.$i;
					}
				}
			}
		}else{
			
			if($element_type != 'checkbox'){
				$temp_columns[] = 'element_'.$element_id;
			}else{
				$temp_columns[] = 'element_'.$element_id.'_1';
			}
		}
	}
    	
	
	
	
	$columns = array();
	$columns = $temp_columns;
	

	/******************************************************************************************/
	//get form entries
	$query  = "select * from `ap_form_{$form_id}` order by id asc";
	$result = do_query($query);
					
	$i=0;	
	$i_file=0;	
	$c=0;
	$row_num = 1;
	$column_label = array();
	$column_label_has_printed = false;
	
	while($row = do_fetch_result($result)){
		
		for($j=0;$j<$fields_num;$j++){
									
			$column_name = $columns[$j];
			
			if(count($column_label) < $fields_num){
				$column_label[$c] = $column_name_lookup[$column_name];
				$c++;
			}
			
			//initialize with empty value, so it won't break our grid
			$form_data[$i][$j] = '';
						
			if($column_type_lookup[$column_name] == 'time'){
				$form_data[$i][$j] = date("h:i:s A",strtotime($row[$column_name]));
			}elseif($column_type_lookup[$column_name] == 'time_noseconds'){ 
				if(!empty($row[$column_name])){
					$form_data[$i][$j] = date("h:i A",strtotime($row[$column_name]));
				}
			}elseif(in_array($column_type_lookup[$column_name],array('dollar','euro','pound','yen'))){ //set column formatting for money fields
				$form_data[$i][$j] = $row[$column_name];	
			}elseif($column_type_lookup[$column_name] == 'date'){ //date with format MM/DD/YYYY
				if(!empty($row[$column_name]) && ($row[$column_name] != '0000-00-00')){
					$form_data[$i][$j] = date("Y/m/d",strtotime($row[$column_name]));
				}
			}elseif($column_type_lookup[$column_name] == 'europe_date'){ //date with format MM/DD/YYYY
				if(!empty($row[$column_name]) && ($row[$column_name] != '0000-00-00')){
					$form_data[$i][$j] = date("Y/m/d",strtotime($row[$column_name]));
				}
			}elseif($column_type_lookup[$column_name] == 'number'){ 
				$form_data[$i][$j] = $row[$column_name];
			}elseif (in_array($column_type_lookup[$column_name],array('radio','select'))){ //multiple choice or dropdown
				$exploded = array();
				$exploded = explode('_',$column_name);
				$this_element_id = $exploded[1];
				$this_option_id  = $row[$column_name];
				
				$form_data[$i][$j] = $element_option_lookup[$this_element_id][$this_option_id];
			}elseif($column_type_lookup[$column_name] == 'checkbox'){
				if(!empty($row[$column_name])){
					$form_data[$i][$j]  			= $column_label[$j];
				}
			}elseif(in_array($column_type_lookup[$column_name],array('phone','simple_phone'))){ 
				$form_data[$i][$j] = $row[$column_name];
			}elseif($column_type_lookup[$column_name] == 'file'){
				if(!empty($row[$column_name])){
					$filename_only = $row[$column_name];
		
					//remove the element_x-xx- suffix we added to all uploaded files
					$file_1 	   = substr($filename_only,strpos($filename_only,'-')+1);
					$filename_only = substr($file_1,strpos($file_1,'-')+1);
					
					$form_data[$i][$j] = $filename_only;
					
					$q_string = base64_encode("form_id={$form_id}&id={$row['id']}&el={$column_name}");
					$file_url[$i_file][$j] = "http://".$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\')."/download.php?q={$q_string}";
				}
		             
			}else{
				$form_data[$i][$j] = $row[$column_name];
			}
			
			if($j == 0){
				$form_data[$i][$j] = $row_num;
			}
			
			
		}
		$i_file++;  
		//print_r($file_url);
					
		if($type == 'xls'){
			//print column header
			if(!$column_label_has_printed){
				$i=0;
				foreach ($column_label as $label){
					$worksheet->write(0, $i, $label,$format_bold);
					$i++;
				}
				$column_label_has_printed = true;
			}
					
			//print column data
			
			foreach ($form_data as $row_data){
				$col_num = 0;
				foreach ($row_data as $data){
					//echo "rownum -1: ".($row_num - 1)."\n";
					//echo "colnum: ".$col_num."\n";
					if(empty($file_url[$row_num-1][$col_num])){
						$worksheet->write($row_num, $col_num, $data);
					}else{
						$worksheet->writeUrl($row_num,$col_num,$file_url[$row_num-1][$col_num],$data);
					}
					$col_num++;	
				}
				
			}
		}elseif ($type == 'csv'){
			if(!$column_label_has_printed){
				fputcsv($out, $column_label);
				$column_label_has_printed = true;
			}
			
			foreach ($form_data as $row_data){
        		fputcsv($out, $row_data);
        	}
		}
		
		$row_num++;
		unset($form_data); 
        unset($file_url);
	}
	
	
	/******************************************************************************************/
	
	//start exporting data
	if($type == 'xls'){
		// Let's send the file
		$workbook->close();
	
	}elseif ($type == 'csv'){
		fclose($out);
	}
	
?>
