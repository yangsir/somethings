<?php

	session_start();

	require('config.php');
	require('includes/check-session.php');
	require('includes/db-core.php');
	require('includes/db-functions.php');
	require('includes/helper-functions.php');

	define('MAX_TITLE_LENGTH',100);
	
	connect_db();
	
	$form_id = (int) trim($_REQUEST['id']);
	
	
	//handle form submit
	if(!empty($_POST['submit'])){
		//get inputs
		$total_field = (int) trim($_POST['total_col']);
		$new_column_prefs = array();
		
		for ($i=1;$i<=$total_field;$i++){
			$element_name = trim($_POST['col_pref_'.$i]);
			if(!empty($element_name)){
				$new_column_prefs[$i] = $element_name;
			}
		}
				
		if(!empty($new_column_prefs)){
			//delete previous entries
			do_query("delete from `ap_column_preferences` where form_id='{$form_id}'");
			
			//insert new preferences
			foreach ($new_column_prefs as $position=>$element_name){
				do_query("insert into `ap_column_preferences`(form_id,element_name,position) values('{$form_id}','{$element_name}','{$position}')");
			}
			
			$_SESSION['AP_SUCCESS']['title'] = '成功';
			$_SESSION['AP_SUCCESS']['desc']  = '已保存';
						
			header("Location: manage_entries.php?id={$form_id}");
			exit;
		}else{
			$_SESSION['AP_ERROR']['title']   = '错误';
			$_SESSION['AP_ERROR']['desc']    = '请至少选择一列.';
		}
	}
	
	//get form name
	$query = "select form_name from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
	
	//get form element options
	$query = "select element_id,option_id,`option` from ap_element_options where form_id='$form_id' and live=1 order by element_id,option_id asc";
	$result = do_query($query);
	while($row = do_fetch_result($result)){
		$element_id = $row['element_id'];
		$option_id  = $row['option_id'];
		
		//limit the data length
		if(strlen($row['option']) > MAX_TITLE_LENGTH){
			$element_option_lookup[$element_id][$option_id] = htmlspecialchars(substr($row['option'],0,MAX_TITLE_LENGTH),ENT_QUOTES);
		}else{
			$element_option_lookup[$element_id][$option_id] = htmlspecialchars($row['option'],ENT_QUOTES);
		}
	}
	
	/******************************************************************************************/
	//prepare column header names lookup
	$query  = "select element_id,element_title,element_type,element_constraint from `ap_form_elements` where form_id='$form_id' and element_type != 'section' order by element_position asc";
	$result = do_query($query);
	
	$column_name_lookup['date_created'] = 'Date Created';
	$column_name_lookup['date_updated'] = 'Date Updated';
	$column_name_lookup['ip_address'] 	= 'IP Address';
	
	while($row = do_fetch_result($result)){
		$element_type = $row['element_type'];
		$element_constraint = $row['element_constraint'];
		
		//limit the title length
		if(strlen($row['element_title']) > MAX_TITLE_LENGTH){
			$row['element_title'] = substr($row['element_title'],0,MAX_TITLE_LENGTH);
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
		}else{ //for other elements with only 1 field
			$column_name_lookup['element_'.$row['element_id']] = $row['element_title'];
			$column_type_lookup['element_'.$row['element_id']] = $row['element_type'];
		}
	}
	/******************************************************************************************/
	
	//get values from ap_column_preferences table
	$query = "select element_name from ap_column_preferences where form_id='{$form_id}'";
	$result = do_query($query);
	
	$column_prefs = array();
	while($row = do_fetch_result($result)){
		$column_prefs[] = $row['element_name'];
	}
	
	$header_data =<<<EOT
<script src="js/jquery/jquery-core.js"></script>  
<script src="js/jquery/jquery-columnhover.js"></script>
<script>
	  $(document).ready(function(){
	    $('#cp_list_table').columnHover(); 
	  	$('#form_columns_preference').find("input[@type$='checkbox']").not('#col_select').click(function(){
	  		if(this.checked == true){
	  			$('#img_' + this.value).attr("src",'images/icons/checkbox_16.gif');
	  		}else{
	  			$('#img_' + this.value).attr("src",'images/icons/cross_16.gif');
	  		}
	  	});
	  });
	  
	  
	  function toggle_select(){
	    var main_checkbox = $('#col_select').attr("checked");
	  	$('#form_columns_preference').find("input[@type$='checkbox']").not('#col_select').each(function(){
			this.checked = main_checkbox;
		});
		
		var image_src = '';
		if(main_checkbox == true){
			image_src = 'images/icons/checkbox_16.gif';
		}else{
			image_src = 'images/icons/cross_16.gif';
		}
		
		$('#form_columns_preference').find(".cb_images").each(function(){
			this.src = image_src;
		});
		
	  }
</script>
<style type="text/css">
#cp_list_table tbody tr:hover
{
	background-color: LemonChiffon;
}
.cp_small{
	width: 30px;
	padding: 0px !important;
}
.cp_checkbox{
	width: 15px !important;
	text-align: center !important;
	padding-right: 2px !important;
	
}
</style>    
EOT;

?>

<?php require('includes/header.php'); ?>


<div id="form_manager">
<?php show_message(); ?>
<div class="info">
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> <a class="breadcrumb" href="manage_entries.php?id=<?php echo $form_id; ?>">调查结果</a> <img src="images/icons/resultset_next.gif" align="bottom" /> 选择显示的列</h2>
	<p>选择您要在调查结果表中显示的列</p>
</div>


<div id="columns_preference">
<div id="cp_list">
<form id="form_columns_preference" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table id="cp_list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <thead>
  <tr>
    <th scope="col" style="border-right: none" class="cp_checkbox"><input type="checkbox" id="col_select" name="col_select" value="1" onclick="toggle_select()" /></th>
  	<th scope="col" style="border-right: none">选择要显示的列:</th>
  	<th scope="col" class="cp_small">&nbsp;</th>
  </tr>
  </thead>
  <tbody>
<?php
	$toggle = false;
	$i = 1;
	foreach ($column_name_lookup as $element_name=>$element_label){
		if($toggle){
			$toggle = false;
			$row_style = 'class="alt"';
		}else{
			$toggle = true;
			$row_style = '';
		}
		
		if(in_array($element_name,$column_prefs)){
			$checked  = 'checked="checked"';
			$img_name = 'checkbox_16.gif';
		}else{
			$checked  = '';
			$img_name = 'cross_16.gif';
		}
		
$table_row =<<<EOT
  	<tr {$row_style}>
  	    <td class="cp_checkbox"><input type="checkbox" name="col_pref_{$i}" value="{$element_name}" {$checked} /></td>
  		<td><div>{$element_label}</div></td>
  		<td class="cp_small"><img id="img_{$element_name}" class="cb_images" align="absmiddle" src="images/icons/{$img_name}"/></td>
  	</tr>
EOT;
		echo $table_row;
		$i++;
	} 
?>  	
  	
  </tbody>
</table><br />
<input type="hidden" name="id" value="<?php echo $form_id; ?>" />
<input type="hidden" name="total_col" value="<?php echo --$i; ?>" />
<input id="saveForm" class="button_text" type="submit" name="submit" value="保存" />
</form>
</div>



</div>
<?php require('includes/footer.php'); ?>
