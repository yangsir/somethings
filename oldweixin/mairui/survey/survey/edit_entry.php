<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	session_start();

	require('config.php');
	require('includes/language.php');
	require('includes/check-session.php');
	require('includes/db-core.php');
	require('includes/common-validator.php');
	require('includes/view-functions.php');
	require('includes/post-functions.php');
	require('includes/entry-functions.php');
	require('includes/filter-functions.php');
	require('includes/helper-functions.php');
	

	connect_db();
	
	$form_id  = (int) trim($_REQUEST['form_id']);
	$entry_id = (int) trim($_REQUEST['id']);
		
	$machform_path = '';
	
	if(!empty($_POST['submit'])){ //if form submitted
		$input_array   = ap_sanitize_input($_POST);
			
		$submit_result = process_form_update($input_array);
		
		if($submit_result['status'] === true){
			$_SESSION['AP_SUCCESS']['title'] = 'Success';
			$_SESSION['AP_SUCCESS']['desc']  = "Entry #{$input_array['edit_id']} has been updated.";
			unset($_SESSION['edit_entry']);
			
			$ssl_suffix = get_ssl_suffix();
			header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].get_dirname($_SERVER['PHP_SELF'])."/view_entry.php?form_id={$input_array['form_id']}&id={$input_array['edit_id']}");
			exit;
		}else{
			$old_values = $submit_result['old_values'];
			$custom_error = $submit_result['custom_error'];
			$error_elements = $submit_result['error_elements'];
						
			$markup = display_integrated_form($input_array['form_id'],$old_values,$error_elements,$custom_error,$input_array['edit_id'],$machform_path);
		}
			
		
	}else{
			
		if(empty($form_id) || empty($entry_id)){
				die('ID required.');
		}
		
		//check for delete parameter
		if(!empty($_GET['delete'])){
			
			delete_entries($form_id,array($entry_id));
			
			$_SESSION['AP_SUCCESS']['title'] = 'Entry deleted';
			$_SESSION['AP_SUCCESS']['desc']  = "Entry #{$entry_id} has been deleted.";
			
			$ssl_suffix = get_ssl_suffix();
			header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].get_dirname($_SERVER['PHP_SELF'])."/manage_entries.php?id={$form_id}");
			exit;
		}
		
		//check for delete file option
		if(!empty($_GET['delete_file'])){
			$element_id = (int) trim($_GET['delete_file']);
			delete_file_entry($form_id,$entry_id,$element_id);
			
			$_SESSION['AP_SUCCESS']['title'] = 'File Deleted';
			$_SESSION['AP_SUCCESS']['desc']  = "A file has been deleted from this entry.";
		}
		
		//set session value to override password protected form and captcha
		$_SESSION['user_authenticated'] = $form_id;
		
		//set session value to bypass unique checking
		$_SESSION['edit_entry']['form_id']  = $form_id;
		$_SESSION['edit_entry']['entry_id'] = $entry_id;
				
			
		//get initial form values
		$form_values = get_entry_values($form_id,$entry_id);
			
		$markup = display_integrated_form($form_id,$form_values,null,'',$entry_id,$machform_path);
		
	}
		
	
	
	
	/** Other small stuff queries **/
	
	//get form name
	$query = "select form_name from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
	
	
	//get ids for navigation buttons
	//older entry id
	$result = do_query("select id from ap_form_{$form_id} where id < $entry_id order by id desc limit 1");
	$row = do_fetch_result($result);
	$older_entry_id = $row['id'];
	
	//oldest entry id
	$result = do_query("select id from ap_form_{$form_id} order by id asc limit 1");
	$row = do_fetch_result($result);
	$oldest_entry_id = $row['id'];
	
	//newer entry id
	$result = do_query("select id from ap_form_{$form_id} where id > $entry_id order by id asc limit 1");
	$row = do_fetch_result($result);
	$newer_entry_id = $row['id'];
	
	//newest entry id
	$result = do_query("select id from ap_form_{$form_id} order by id desc limit 1");
	$row = do_fetch_result($result);
	$newest_entry_id = $row['id'];
	
	if(($entry_id == $newest_entry_id) && ($entry_id == $oldest_entry_id)){
		$nav_position = 'disabled';
	}elseif($entry_id == $newest_entry_id){
		$nav_position = 'newest';
	}elseif ($entry_id == $oldest_entry_id){
		$nav_position = 'oldest';
	}else{
		$nav_position = 'middle';
	}
	
?>

<?php require('includes/header.php'); ?>


<div id="form_manager">
<?php show_message(); ?>
<div class="info">
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> <a class="breadcrumb" href="manage_entries.php?id=<?php echo $form_id; ?>">Entries</a> <img src="images/icons/resultset_next.gif" align="bottom" /> #<?php echo $entry_id; ?> </h2>
	<p>Editing entry #<?php echo $entry_id; ?></p>
</div>


<div id="edit_entry">
<div id="ee_detail" >
<?php echo $markup; ?>
</div>
<?php
	if($nav_position == 'newest'){
		$img_new = '_grey';
	}elseif ($nav_position == 'oldest'){
		$img_old = '_grey';
	}elseif ($nav_position == 'disabled'){
		$img_new = '_grey';
		$img_old = '_grey';
	}
?>
<div id="ee_action_container">
<div style="text-align:center;">
<?php
	if(empty($img_new)){
?>
	<a href="edit_entry.php?<?php echo "form_id={$form_id}&id={$newest_entry_id}"; ?>" alt="Newest"><img src="images/icons/nav_start<?php echo $img_new; ?>.gif" title="Newest"/></a>&nbsp;
	<a href="edit_entry.php?<?php echo "form_id={$form_id}&id={$newer_entry_id}"; ?>" alt="Newer"><img src="images/icons/nav_prev<?php echo $img_new; ?>.gif" title="Newer"/></a>&nbsp;

<?php }else{ ?>

    <img src="images/icons/nav_start<?php echo $img_new; ?>.gif" title="Newest"/>&nbsp;
	<img src="images/icons/nav_prev<?php echo $img_new; ?>.gif" title="Newer"/>&nbsp;	
	
<?php } 

	if(empty($img_old)){
?>	

<a href="edit_entry.php?<?php echo "form_id={$form_id}&id={$older_entry_id}"; ?>" alt="Older"><img src="images/icons/nav_next<?php echo $img_old; ?>.gif" title="Older"/></a>&nbsp;
	<a href="edit_entry.php?<?php echo "form_id={$form_id}&id={$oldest_entry_id}"; ?>" alt="Oldest"><img src="images/icons/nav_end<?php echo $img_old; ?>.gif" title="Oldest"/></a>

<?php } else { ?>
	<img src="images/icons/nav_next<?php echo $img_old; ?>.gif" title="Older"/>&nbsp;
	<img src="images/icons/nav_end<?php echo $img_old; ?>.gif" title="Oldest"/>
<?php } ?>
</div>
<div style="font-size: 85%;color: #444;margin-top: 25px;padding-bottom: 5px; font-weight: bold">Entry Options</div>
<div id="ee_action">
<ul style="list-style-type: none;padding: 10px">
<li><img src="images/icons/search.gif" align="absmiddle"/> &nbsp;<a href="view_entry.php?form_id=<?php echo $form_id; ?>&id=<?php echo $entry_id; ?>" class="big_dotted_link">View</a></li>
<li><img src="images/icons/cross_22.gif" align="absmiddle"/> &nbsp;<a onclick="return confirm('Are you sure you want to delete this entry?');" href="edit_entry.php?form_id=<?php echo $form_id; ?>&id=<?php echo $entry_id; ?>&delete=1" class="big_dotted_link">Delete</a></li>
</ul>
</div>
</div>


</div>
<?php require('includes/footer.php'); ?>
