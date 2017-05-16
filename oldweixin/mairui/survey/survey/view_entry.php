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
	require('includes/entry-functions.php');

	connect_db();
	
	$form_id  = (int) trim($_REQUEST['form_id']);
	$entry_id = (int) trim($_REQUEST['id']);
	
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
		
	//get form name
	$query = "select form_name from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
	
	$entry_details = get_entry_details($form_id,$entry_id);
	
	//get entry timestamp
	$query = "select date_created,date_updated,ip_address from `ap_form_{$form_id}` where id='$entry_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	
	$date_created = $row['date_created'];
	if(!empty($row['date_update'])){
		$date_updated = $row['date_updated'];
	}else{
		$date_updated = '&nbsp;';
	}
	$ip_address   = $row['ip_address'];
	
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
	
	
	$header_data =<<<EOT
<script src="js/jquery/jquery-core.js"></script>  
<script src="js/jquery/jquery-columnhover.js"></script>
<script src="js/jquery/jquery-simplemodal.js"></script>
<script src='js/email_entry.js' type='text/javascript'></script>

<link type='text/css' href='css/email_entry.css' rel='stylesheet' media='screen' />

<!--[if lt IE 7]>
<link type='text/css' href='css/email_entry_ie.css' rel='stylesheet' media='screen' />
<![endif]-->

<link rel="stylesheet" type="text/css" href="css/entry_print.css" media="print">
EOT;

	
?>

<?php require('includes/header.php'); ?>


<div id="form_manager">
<?php show_message(); ?>
<div class="info">
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> <a id="ve_a_entries" class="breadcrumb" href="manage_entries.php?id=<?php echo $form_id; ?>">Entries</a> <img id="ve_a_next" src="images/icons/resultset_next.gif" align="bottom" /> #<?php echo $entry_id; ?> </h2>
	<p>Viewing entry #<?php echo $entry_id; ?></p>
</div>


<div id="view_entry">
<div id="ve_detail" >
<table id="ve_detail_table" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>

<?php 
		$toggle = false;
		
		foreach ($entry_details as $data){ 
			if($toggle){
				$toggle = false;
				$row_style = 'class="alt"';
			}else{
				$toggle = true;
				$row_style = '';
			}	
?>  
  	<tr <?php echo $row_style; ?>>
  	    <td width="40%"><strong><?php echo $data['label']; ?></strong> </td>
  		<td width="60%"><?php echo nl2br($data['value']); ?></td>
  	</tr>
<?php } ?>  	
  	
  		  	
  </tbody>
</table>
<table id="ve_table_info" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody>		
		<tr>
  	    <td style="font-size: 85%;color: #444; font-weight: bold"><img src="images/icons/date.gif"/> Entry Info</td>
  		<td >&nbsp; </td>
  		</tr> 
		
		<tr class="alt">
  	    <td width="40%"><strong>Date Created </strong></td>
  		<td width="60%"><?php echo $date_created; ?></td>
  		</tr>  	<tr >
  	    <td ><strong>Date Updated </strong></td>

  		<td><?php echo $date_updated; ?></td>
  		</tr>  	
		<tr class="alt">
  	    <td ><strong>IP Address  </strong></td>
  		<td><?php echo $ip_address; ?></td>
  	</tr>
  </tbody>
</table>
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
<div id="ve_action_container">
<div style="text-align:center;">
<?php
	if(empty($img_new)){
?>
	<a href="view_entry.php?<?php echo "form_id={$form_id}&id={$newest_entry_id}"; ?>" alt="Newest"><img src="images/icons/nav_start<?php echo $img_new; ?>.gif" title="Newest"/></a>&nbsp;
	<a href="view_entry.php?<?php echo "form_id={$form_id}&id={$newer_entry_id}"; ?>" alt="Newer"><img src="images/icons/nav_prev<?php echo $img_new; ?>.gif" title="Newer"/></a>&nbsp;

<?php }else{ ?>

    <img src="images/icons/nav_start<?php echo $img_new; ?>.gif" title="Newest"/>&nbsp;
	<img src="images/icons/nav_prev<?php echo $img_new; ?>.gif" title="Newer"/>&nbsp;	
	
<?php } 

	if(empty($img_old)){
?>	

<a href="view_entry.php?<?php echo "form_id={$form_id}&id={$older_entry_id}"; ?>" alt="Older"><img src="images/icons/nav_next<?php echo $img_old; ?>.gif" title="Older"/></a>&nbsp;
	<a href="view_entry.php?<?php echo "form_id={$form_id}&id={$oldest_entry_id}"; ?>" alt="Oldest"><img src="images/icons/nav_end<?php echo $img_old; ?>.gif" title="Oldest"/></a>

<?php } else { ?>
	<img src="images/icons/nav_next<?php echo $img_old; ?>.gif" title="Older"/>&nbsp;
	<img src="images/icons/nav_end<?php echo $img_old; ?>.gif" title="Oldest"/>
<?php } ?>
</div>
<div style="font-size: 85%;color: #444;margin-top: 25px;padding-bottom: 5px; font-weight: bold">Entry Options</div>
<div id="ve_action">
<ul style="list-style-type: none;padding: 10px">
<li><img src="images/icons/kate.gif" align="absmiddle"/> &nbsp;<a href="edit_entry.php?form_id=<?php echo $form_id; ?>&id=<?php echo $entry_id; ?>" class="big_dotted_link">Edit</a></li>
<li><img src="images/icons/fileprint.gif" align="absmiddle"/> &nbsp;<a href="javascript:window.print()" class="big_dotted_link">Print</a></li>
<li><img src="images/icons/mail_generic2.gif" align="absmiddle"/> &nbsp;<a id="email_entry" href="javascript: email_entry(<?php echo $form_id.','.$entry_id; ?>);" class="big_dotted_link">Email</a></li>
<li><img src="images/icons/cross_22.gif" align="absmiddle"/> &nbsp;<a onclick="return confirm('Are you sure you want to delete this entry?');" href="view_entry.php?form_id=<?php echo $form_id; ?>&id=<?php echo $entry_id; ?>&delete=1" class="big_dotted_link">Delete</a></li>
</ul>
</div>
</div>


</div>
<?php require('includes/footer.php'); ?>
