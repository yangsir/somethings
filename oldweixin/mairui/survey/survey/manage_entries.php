<?php

	session_start();

	require('config.php');
	require('includes/check-session.php');
	require('includes/db-core.php');
	require('includes/db-functions.php');
	require('includes/helper-functions.php');
	require('includes/entry-functions.php');

	connect_db();
	
	$form_id = (int) trim($_REQUEST['id']);
	
		
	//handle form submit
	if(!empty($_POST['submit_del_selected'])){
		//get inputs
		$entries_id = $_POST['del_entry_id'];
		
		if(!empty($entries_id)){
			//do deletion
			delete_entries($form_id,$entries_id);
			
			$_SESSION['AP_SUCCESS']['title'] = 'Deletion Success';
			$_SESSION['AP_SUCCESS']['desc']  = 'Selected entries have been deleted.';
		}
		
		header("Location: manage_entries.php?id={$form_id}&pageno={$_REQUEST['pageno']}");
		exit;
			
	}elseif (!empty($_POST['submit_del_all'])){
		
		//empty table
		do_query("truncate `ap_form_{$form_id}`");
		
		//empty files folder
		@full_rmdir(UPLOAD_DIR."/form_{$form_id}/files");
		
		$old_mask = umask(0);
		mkdir(UPLOAD_DIR."/form_{$form_id}/files",0777);
		umask($old_mask);
		
		$_SESSION['AP_SUCCESS']['title'] = 'Deletion Success';
		$_SESSION['AP_SUCCESS']['desc']  = 'All entries have been deleted.';
		
		header("Location: manage_entries.php?id={$form_id}");
		exit;
	}
	
		
	
	/****Pagination *************/
	//get page number for pagination
	if (isset($_REQUEST['pageno'])) {
	   $pageno = $_REQUEST['pageno'];
	}else{
	   $pageno = 1;
	}
				
	//identify how many database rows are available
	$query = "select count(*) total_row from `ap_form_{$form_id}`";
	$result = do_query($query);
	$row = do_fetch_result($result);
	
	$numrows = $row['total_row'];
	$rows_per_page = 15;
	$lastpage      = ceil($numrows/$rows_per_page);
						
						
	//ensure that $pageno is within range
	//this code checks that the value of $pageno is an integer between 1 and $lastpage
	$pageno = (int)$pageno;
						
	if ($pageno < 1) { 
	   $pageno = 1;
	}
	elseif ($pageno > $lastpage){
		$pageno = $lastpage;
	}
						
	//construct the LIMIT clause for the sql SELECT statement
	if(!empty($numrows)){
		$limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
	}
	/*****End Pagination code *******/	
	
	
	//get form name
	$query = "select form_name from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
	
	
	$max_data_length = 80; //maximum length of column header and content
	
	//get form element options
	$query = "select element_id,option_id,`option` from ap_element_options where form_id='$form_id' and live=1 order by element_id,option_id asc";
	$result = do_query($query);
	while($row = do_fetch_result($result)){
		$element_id = $row['element_id'];
		$option_id  = $row['option_id'];
		
		//limit the data length
		if(strlen($row['option']) > $max_data_length){
			$element_option_lookup[$element_id][$option_id] = htmlspecialchars(substr($row['option'],0,$max_data_length).'...',ENT_QUOTES);
		}else{
			$element_option_lookup[$element_id][$option_id] = htmlspecialchars($row['option'],ENT_QUOTES);
		}
	}
		
	/******************************************************************************************/
	//prepare column header names lookup
	$query  = "select element_id,element_title,element_type,element_constraint from `ap_form_elements` where form_id='$form_id' order by element_position asc";
	$result = do_query($query);
	
	while($row = do_fetch_result($result)){
		$element_type = $row['element_type'];
		$element_constraint = $row['element_constraint'];
		
		//limit the title length
		if(strlen($row['element_title']) > $max_data_length){
			$row['element_title'] = substr($row['element_title'],0,$max_data_length).'...';
		}
		
		$row['element_title'] = htmlspecialchars($row['element_title']);
		
		if('address' == $element_type){ //address has 6 fields
			$column_name_lookup['element_'.$row['element_id'].'_1'] = $row['element_title'].' - Street Address';
			$column_name_lookup['element_'.$row['element_id'].'_2'] = 'Address Line 2';
			$column_name_lookup['element_'.$row['element_id'].'_3'] = 'City';
			$column_name_lookup['element_'.$row['element_id'].'_4'] = 'State/Province/Region';
			$column_name_lookup['element_'.$row['element_id'].'_5'] = 'Zip/Postal Code';
			$column_name_lookup['element_'.$row['element_id'].'_6'] = 'Country';
			
			$column_type_lookup['element_'.$row['element_id'].'_1'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_2'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_3'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_4'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_5'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_6'] = $row['element_type'];
			
		}elseif ('simple_name' == $element_type){ //simple name has 2 fields
			$column_name_lookup['element_'.$row['element_id'].'_1'] = $row['element_title'].' - First';
			$column_name_lookup['element_'.$row['element_id'].'_2'] = $row['element_title'].' - Last';
			
			$column_type_lookup['element_'.$row['element_id'].'_1'] = $row['element_type'];
			$column_type_lookup['element_'.$row['element_id'].'_2'] = $row['element_type'];
			
		}elseif ('name' == $element_type){ //name has 4 fields
			$column_name_lookup['element_'.$row['element_id'].'_1'] = $row['element_title'].' - Title';
			$column_name_lookup['element_'.$row['element_id'].'_2'] = $row['element_title'].' - First';
			$column_name_lookup['element_'.$row['element_id'].'_3'] = $row['element_title'].' - Last';
			$column_name_lookup['element_'.$row['element_id'].'_4'] = $row['element_title'].' - Suffix';
			
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
	$column_name_lookup['id'] 			= '<input type="checkbox" id="col_select" name="col_select" value="1" onclick="toggle_select()" />';
	$column_name_lookup['row_num'] 		= '#';
	$column_name_lookup['date_created'] = 'Date Created';
	$column_name_lookup['date_updated'] = 'Date Updated';
	$column_name_lookup['ip_address'] 	= 'IP Address';
	
	$column_type_lookup['id'] 			= 'number';
	$column_type_lookup['row_num']		= 'number';
	$column_type_lookup['date_created'] = 'text';
	$column_type_lookup['date_updated'] = 'text';
	$column_type_lookup['ip_address'] 	= 'text';
	
	/******************************************************************************************/
	//get columns preference
	$query 	= "select element_name from ap_column_preferences where form_id='{$form_id}' order by position asc";
	$result = do_query($query);
	while($row = do_fetch_result($result)){
		$column_prefs[] = $row['element_name'];
	}
	
	//if empty display 6 first columns
	if(empty($column_prefs)){
		$query  = "select * from `ap_form_{$form_id}` limit 1";
		$result = do_query($query);
		
		$i = 0;
		$j = 0;
		$fields_num = mysql_num_fields($result);
		while(($i < $fields_num) && ($j <= 5)){
			$meta = mysql_fetch_field($result, $i);
			
			if(!in_array($i,array(0,2,3))){ //don't display id (0), date_updated (2) and ip_address (3) as default
				$column_prefs[$j] = $meta->name;
				$j++;
			}
			
			$i++;
		}
	}
	
	$field_list = implode(',',$column_prefs);
	$query  = "select id,id as row_num,{$field_list} from `ap_form_{$form_id}` order by id desc $limit";
	$result = do_query($query);
	
	//get actual field name from selected table
	$i = 0;
	$fields_num = mysql_num_fields($result);
	while($i < $fields_num){
		$meta = mysql_fetch_field($result, $i);
		$columns[$i] = $meta->name;
		$i++;
	}
	
	$i=0;	
	$c=0;
	$column_label = array();
	$first_row_number = ($pageno -1) * $rows_per_page + 1;
	$last_row_number  = $first_row_number;
	
	
	while($row = do_fetch_result($result)){
		
		for($j=0;$j<$fields_num;$j++){
			
						
			$column_name = $columns[$j];
			
			
			$form_data[$i][$j] = '';
			
			//limit the data length, unless for file element
			if($column_type_lookup[$column_name] != 'file'){
				if(strlen($row[$column_name]) > $max_data_length){
					$row[$column_name] = substr($row[$column_name],0,$max_data_length).'...';
				}
			}
			
			if($column_type_lookup[$column_name] == 'time'){
				if(!empty($row[$column_name])){
					$form_data[$i][$j] = date("h:i:s A",strtotime($row[$column_name]));
				}else {
					$form_data[$i][$j] = '';
				}
			}elseif($column_type_lookup[$column_name] == 'time_noseconds'){ 
				if(!empty($row[$column_name])){
					$form_data[$i][$j] = date("h:i A",strtotime($row[$column_name]));
				}else {
					$form_data[$i][$j] = '';
				}
			}elseif(in_array($column_type_lookup[$column_name],array('dollar','euro','pound','yen'))){ //set column formatting for money fields
				$column_type = $column_type_lookup[$column_name];
				
				switch ($column_type){
					case 'dollar' : $currency = '$';break;	
					case 'pound'  : $currency = '&#163;';break;
					case 'euro'   : $currency = '&#8364;';break;
					case 'yen' 	  : $currency = '&#165;';break;
				}
				
				if(!empty($row[$column_name])){
					$form_data[$i][$j] = '<div class="me_right_div">'.$currency.$row[$column_name].'</div>';
				}else{
					$form_data[$i][$j] = '';
				}
			}elseif($column_type_lookup[$column_name] == 'date'){ //date with format MM/DD/YYYY
				if(!empty($row[$column_name]) && ($row[$column_name] != '0000-00-00')){
					$exploded_value = array();
					$exploded_value = explode("-",$row[$column_name]);
					$form_data[$i][$j]  = "{$exploded_value[0]}/{$exploded_value[1]}/{$exploded_value[2]}";
				}
			}elseif($column_type_lookup[$column_name] == 'europe_date'){ //date with format DD/MM/YYYY
				if(!empty($row[$column_name]) && ($row[$column_name] != '0000-00-00')){
					$exploded_value = array();
					$exploded_value = explode("-",$row[$column_name]);
					$form_data[$i][$j]  = "{$exploded_value[0]}/{$exploded_value[1]}/{$exploded_value[2]}";
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
					$form_data[$i][$j]  = '<div class="me_center_div"><img src="images/icons/checkbox_16.gif" align="absmiddle" /></div>';
				}else{
					$form_data[$i][$j]  = '';
				}
				
			}elseif(in_array($column_type_lookup[$column_name],array('phone','simple_phone'))){ 
				$form_data[$i][$j] = $row[$column_name];
			}elseif($column_type_lookup[$column_name] == 'file'){
				if(!empty($row[$column_name])){
					//encode the long query string for more readibility
					$q_string = base64_encode("form_id={$form_id}&id={$row['id']}&el={$column_name}");
					$form_data[$i][$j] = "<img src=\"images/icons/attach.gif\" align=\"absmiddle\" /> &nbsp;<a href=\"download.php?q={$q_string}\">Download file</a>";
				}
			}else{
				$form_data[$i][$j] = htmlspecialchars(str_replace("\r","",str_replace("\n"," ",$row[$column_name])),ENT_QUOTES);
				
				if($column_name == 'date_created' || $column_name == 'date_updated'){
					$form_data[$i][$j] = short_relative_date($form_data[$i][$j]);
				}
			}
			
			if(count($column_label) < $fields_num){
				$column_label[$c]['name']  = $column_name;
				$column_label[$c]['label'] = str_replace("\r","",str_replace("\n"," ",$column_name_lookup[$column_name])); 
				$c++;
			}
		}
		
		$last_row_number++;
		$i++;			
	}
	
	$last_row_number--;
	/******************************************************************************************/
		
	
	$header_data =<<<EOT
	<script src="js/jquery/jquery-core.js"></script>  
<script src="js/jquery/jquery-columnhover.js"></script>
<script>
	  $(document).ready(function(){
	    $('#entries_table').columnHover(); 
	  });
	  
	  function toggle_select(){
	    var main_checkbox = $('#col_select').attr("checked");
	  	$('#form_manage_entries').find("input[@type$='checkbox']").not('#col_select').each(function(){
			this.checked = main_checkbox;
		});
	  }
	  
</script>
<style type="text/css">
td.hover, #entries_table tbody tr:hover
{
	background-color: LemonChiffon;
}
</style>    
EOT;
	
		
?>

<?php require('includes/header.php'); ?>


<div id="form_manager">
<?php show_message(); ?>
<div class="info">
<?php if(!empty($form_data)){ ?>
	<div class="export">导出调查结果: <a href="export_entries.php?id=<?php echo $form_id; ?>&type=xls"><img src="images/icons/page_excel.gif" align="absmiddle"/> Excel File</a><a href="export_entries.php?id=<?php echo $form_id; ?>&type=csv"><img src="images/icons/page_white_code.gif" align="absmiddle"/> CSV File</a></div>
    <?php } ?>	
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> 调查结果</h2>
	<p>编辑，管理您的调查结果</p>
</div>

<?php if(!empty($form_data)){ ?>
<div id="manage_entries" style="width: 100%">
<form id="form_manage_entries" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div style="width: 100%; overflow:hidden; margin-bottom: 5px">
<div style="float: left;padding-bottom: 5px;padding-left: 8px"><img src="images/icons/arrow_turn_left.gif" align="absmiddle"/>&nbsp;
     <input name="submit_del_selected" type="submit" id="submit_del_selected" value="删除所选项" onclick="return confirm('删除所选项?');" /> or
	 <input name="submit_del_all" type="submit" id="submit_del_all" value="删除所有选项" onclick="return confirm('删除所有选项?');"/>
</div>
<div id="me_choose_col" style="padding-top: 5px">
<img src="images/icons/show_table_column.gif" style="margin-top: -2px" align="absmiddle"/> <a href="columns_preference.php?id=<?php echo $form_id; ?>">选择列</a>
</div>
</div>

<table id="entries_table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <thead>
  <tr>

<?php
  	//print table header
	foreach ($column_label as $table_header){
		if($table_header['name'] == 'id'){
			echo '<th scope="col" class="me_action">'.$table_header['label'].'</th>';
		}elseif ($table_header['name'] == 'row_num'){
			echo '<th scope="col" class="me_number">'.$table_header['label'].'</th>';
		}else{
			echo '<th scope="col"><div>'.$table_header['label'].'</div></th>';
		}
  		
		
  	}
?>

  </tr>
  </thead>
  <tbody>
<?php  
	$toggle = false;
 	foreach ($form_data as $row_data){
		if($toggle){
			$toggle = false;
			$row_style = 'class="alt"';
		}else{
			$toggle = true;
			$row_style = '';
		}
 		
 		echo "<tr {$row_style}>";
			
		foreach ($row_data as $key=>$column_data){
			if($key == 0){
				echo '<td class="me_action"><input type="checkbox" name="del_entry_id[]" value="'.$column_data.'" /> <a href="view_entry.php?form_id='.$form_id.'&id='.$column_data.'"><img src="images/icons/magnifier.gif" align="absmiddle" /></a></td>';
			}elseif ($key == 1){
				echo '<td class="me_number">'.$column_data.'</td>';
			}else{
				echo '<td><div>'.$column_data.'</div></td>';
			}
		}
			
		echo '</tr>';			
	}
?>
  </tbody>
</table>
<input type="hidden" name="id" value="<?php echo $form_id; ?>" />
<input type="hidden" name="pageno" value="<?php echo $pageno; ?>" />
</form>
</div>

<?php }else{
		echo "<div style=\"height: 200px; text-align: center;padding-top: 70px\"><h2 style=\"font-size: 155%\">无任何调查结果</h2></div>";
	}	
?>


<!-- start paging div -->
<div id="paging">
<?php      
      if(!empty($lastpage))
		{
			
			
			if ($pageno != 1) 
			{
			   
			   if($lastpage > 19){	
			   	echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=1'>&lt;&lt;First</a> ";
			   }
			   $prevpage = $pageno-1;
							   
			   
			} 
			
			//middle navigation
			if($pageno == 1){
				$i=1;
				while(($i<=19) && ($i<=$lastpage)){
					if($i != 1){
							$active_style = '';
						}else{
							$active_style = 'class="active_page"';
					}
					 echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=$i' $active_style>$i</a> ";
					$i++;
				}
				if($lastpage > $i){
					echo ' ... ';
				}
			}elseif ($pageno == $lastpage){
				
				if(($lastpage - 19) > 1){
					echo ' ... ';
					$i=1;
					$j=$lastpage - 18;
					while($i<=19){
						if($j != $lastpage){
							$active_style = '';
						}else{
							$active_style = 'class="active_page"';
						}
						 echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=$j' $active_style>$j</a> ";
						$i++;
						$j++;
					}
				}else{
					$i=1;
					while(($i<=19) && ($i<=$lastpage)){
						if($i != $lastpage){
							$active_style = '';
						}else{
							$active_style = 'class="active_page"';
						}
						 echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=$i' $active_style>$i</a> ";
						$i++;
					}
				}
				
			}else{
				$next_pages = false;
				$prev_pages = false;
				
				if(($lastpage - ($pageno + 9)) >= 1){
					$next_pages = true;
				}
				if(($pageno - 9) > 1){
					$prev_pages = true;
				}
				
				if($prev_pages){ //if there are previous pages
					echo ' ... ';
					if($next_pages){ //if there are next pages
						$i=1;
						$j=$pageno - 9;
						while($i<=19){
							if($j != $pageno){
								$active_style = '';
							}else{
								$active_style = 'class="active_page"';
							}
							echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=$j' $active_style>$j</a> ";
							$i++;
							$j++;
						}
						echo ' ... ';
					}else{
						
						$i=1;
						$j=$pageno - 9;
						while(($i<=19) && ($j <= $lastpage)){
							if($j != $pageno){
								$active_style = '';
							}else{
								$active_style = 'class="active_page"';
							}
							 echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=$j' $active_style>$j</a> ";
							$i++;
							$j++;
						}
					}	
				}else{ //if there aren't previous pages
				
					$i=1;
  					while(($i<=19) && ($i <= $lastpage)){
  						if($i != $pageno){
								$active_style = '';
						}else{
							$active_style = 'class="active_page"';
						}
						echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=$i' $active_style>$i</a> ";
						$i++;
						
					}
					if($next_pages){
						echo ' ... ';
					}
					
				}
				
				
			}
				
			if ($pageno != $lastpage) 
			{
			   $nextpage = $pageno+1;
			   if($lastpage > 19){
			   	echo " <a href='{$_SERVER['PHP_SELF']}?id={$form_id}&pageno=$lastpage'>Last&gt;&gt;</a> ";
			   }
			}
			
			
			//next we inform the user of his current position in the sequence of available pages
			?>
			<div class="footer">
				Viewing <b><?php echo $first_row_number.'</b>-<b>'.$last_row_number; ?></b> of <b><?php echo $numrows; ?></b> entries
			</div>
			<?php
		}
?>
</div>
<!-- end paging div - -->



</div>
<?php require('includes/footer.php'); ?>
