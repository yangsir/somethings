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
	require('includes/helper-functions.php');

	define('MAX_TITLE_LENGTH',100);
	
	connect_db();
	
	$form_id = (int) trim($_REQUEST['id']);
	
		
	//get form name
	$query = "select form_name from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
	
	
	//get the element list
	$query  = "select element_id,element_title from`ap_form_elements` where form_id='$form_id' and element_type != 'section' order by element_position asc";
	$result = do_query($query);
	
	$column_list = array();
	$i=0;
	while($row = do_fetch_result($result)){
		
		//limit the title length
		if(strlen($row['element_title']) > MAX_TITLE_LENGTH){
			$row['element_title'] = substr($row['element_title'],0,MAX_TITLE_LENGTH);
		}

		$row['element_title'] = htmlspecialchars($row['element_title']);
		$column_list[$i]['field_name'] 		  = $row['element_title'];
		$column_list[$i]['template_variable'] = '{element_'.$row['element_id'].'}';
		$i++;
	}
	/******************************************************************************************/
	$column_list[$i]['field_name'] = '&nbsp;';
	$column_list[$i]['template_variable'] = '&nbsp;';
	$i++;
	$column_list[$i]['field_name'] = 'Entry No.';
	$column_list[$i]['template_variable'] = '{entry_no}';
	$i++;
	$column_list[$i]['field_name'] = 'Date Created';
	$column_list[$i]['template_variable'] = '{date_created}';
	$i++;
	$column_list[$i]['field_name'] = 'IP Address';
	$column_list[$i]['template_variable'] = '{ip_address}';
	$i++;
	$column_list[$i]['field_name'] = 'Form ID';
	$column_list[$i]['template_variable'] = '{form_id}';
	$i++;
	$column_list[$i]['field_name'] = 'Form Name';
	$column_list[$i]['template_variable'] = '{form_name}';
	$i++;
	$column_list[$i]['field_name'] = 'Complete Entry';
	$column_list[$i]['template_variable'] = '{entry_data}';
	
	$header_data =<<<EOT
<script src="js/jquery/jquery-core.js"></script>  
<script src="js/jquery/jquery-columnhover.js"></script>
<script>
	  $(document).ready(function(){
	    $('#tv_list_table').columnHover(); 
	  });
</script>
<style type="text/css">
td.hover, #tv_list_table tbody tr:hover
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
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> <a class="breadcrumb" href="email_settings.php?id=<?php echo $form_id; ?>">Emails</a> <img src="images/icons/resultset_next.gif" align="bottom" /> Template Variables</h2>
	<p>A list of available template variables for your email content</p>
</div>


<div id="columns_preference">
<div id="tv_list">

<table id="tv_list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <thead>
  <tr>
   	<th scope="col" style="border-right:1px dotted #FFFFFF;" width="60%">Field</th>
  	<th scope="col" style="border-right: none" width="40%">Template Variable</th>
  </tr>
  </thead>
  <tbody>
<?php
	$toggle = false;
	$i = 1;
	foreach ($column_list as $data){
		if($toggle){
			$toggle = false;
			$row_style = 'class="alt"';
		}else{
			$toggle = true;
			$row_style = '';
		}
		
$table_row =<<<EOT
  	<tr {$row_style}>
  	    <td><div>{$data['field_name']}</div></td>
  		<td><div><strong>{$data['template_variable']}</strong></div></td>
  	</tr>
EOT;
		echo $table_row;
		$i++;
	} 
?>  	
  	
  </tbody>
</table><br />

</div>
<div style="background-color:#FFFFCC;float: left;width: 40%;margin-left: 30px; padding: 20px">
<img align="absmiddle" src="images/icons/information.gif"/> <b style="color: #444">What is a Template Variable?</b>
<p style="padding-top: 5px">A Template Variable is a special identifier that is automatically replaced with data typed in by a user.</p><br />
<img align="absmiddle" src="images/icons/information.gif"/> <b style="color: #444">How are they used?</b>
<p style="padding-top: 5px">Copy the variable name on the left (including curly braces) to your email template.</p><br/>
<img align="absmiddle" src="images/icons/information.gif"/> <b style="color: #444">Where can Template Variables be used?</b>
<p style="padding-top: 5px">Template Variables can be used in <span style="border-bottom: 1px dotted #000">From Name</span>, <span style="border-bottom: 1px dotted #000">Subject</span> and <span style="border-bottom: 1px dotted #000">Content</span> fields.</p>
</div>

</div>
<?php require('includes/footer.php'); ?>
