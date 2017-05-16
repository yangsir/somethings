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
	require('lib/pear/Compat/Function/file_put_contents.php');

	connect_db();
	
	$form_id = (int) trim($_REQUEST['id']);
	$css_filename = DATA_DIR."/form_{$form_id}/css/view.css";
	
	
	//handle form submit
	if(!empty($_POST['submit'])){
		//save to file and redirect to manage_entries
		$css_content = stripslashes($_POST['css_data']);
		if(is_writable($css_filename)){
			$_SESSION['AP_SUCCESS']['title'] = 'Success';
			$_SESSION['AP_SUCCESS']['desc']  = 'CSS file successfully updated.';
			
			file_put_contents($css_filename,$css_content);
			header("Location: manage_form.php?id={$form_id}");
			exit;
		}else{
			
			$_SESSION['AP_ERROR']['title']   = 'An error occured while saving';
			$_SESSION['AP_ERROR']['desc']    = 'Unable to write into CSS file. Please check your file permission.';
			
		}
	}
	
	
	//get form name
	$query = "select form_name from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
		
	$css_content = htmlspecialchars(file_get_contents($css_filename),ENT_QUOTES);	
	
?>

<?php require('includes/header.php'); ?>

<div id="form_manager">
<?php show_message(); ?>
<div class="info">
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> CSS File</h2>
	<p>Editing <b><?php echo $css_filename; ?></b></p>
</div>

<div id="form_container">
		<form id="form_edit_css" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul>
			<li class="highlighted">
				<label class="desc" for="css_data">File Content</label>
				<div>
					<textarea id="css_data" name="css_data" class="element textarea large"><?php echo $css_content; ?></textarea> 
				</div> 
			</li>
    		<li class="buttons">
		    	<input type="hidden" name="id" value="<?php echo $form_id; ?>" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Update File" />
			</li>
		</ul>
		</form>	
</div><br />

</div>
<?php require('includes/footer.php'); ?>
