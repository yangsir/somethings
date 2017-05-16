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
	require('includes/db-functions.php');
	require('includes/helper-functions.php');
	require('includes/filter-functions.php');
	
	connect_db();
	
	$form_id = (int) trim($_REQUEST['id']);
		
	//get form name and properties
	$query = "select 
					form_name,
					form_email,
					esl_from_name,
					esl_from_email_address,
					esl_subject,
					esl_content,
					esl_plain_text,
					esr_email_address,
					esr_from_name,
					esr_from_email_address,
					esr_subject,
					esr_content,
					esr_plain_text 
				from 
					`ap_forms` 
			   where 
					form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	
	$form_name 		= htmlspecialchars($row['form_name']);
	$form_email 	= htmlspecialchars($row['form_email']);
	$esl_from_name 	= htmlspecialchars($row['esl_from_name']);
	$esl_from_email_address	= htmlspecialchars($row['esl_from_email_address']);
	$esl_subject 	= htmlspecialchars($row['esl_subject']);
	$esl_content 	= htmlspecialchars($row['esl_content'],ENT_NOQUOTES);
	$esl_plain_text	= htmlspecialchars($row['esl_plain_text']);
	$esr_email_address = htmlspecialchars($row['esr_email_address']);
	$esr_from_name 	= htmlspecialchars($row['esr_from_name']);
	$esr_from_email_address	= htmlspecialchars($row['esr_from_email_address']);
	$esr_subject 	= htmlspecialchars($row['esr_subject']);
	$esr_content 	= htmlspecialchars($row['esr_content'],ENT_QUOTES);
	$esr_plain_text	= htmlspecialchars($row['esr_plain_text']);
	
	//get email fields for this form
	$query = "select 
					element_id,
					element_title 
				from 
					`ap_form_elements` 
			   where 
			   		form_id='{$form_id}' and element_type='email' and element_is_private=0 
			order by 
					element_position asc";
	$result = do_query($query);
	$i=0;
	$email_fields = array();
	while($row = do_fetch_result($result)){
		$email_fields[$i]['label'] = $row['element_title'];
		$email_fields[$i]['value'] = $row['element_id'];
		$i++;
	}
	
	
	//start left form handler --------
	if(NOTIFICATION_MAIL_FROM != ''){
		$esl_email_fields[0]['label'] = NOTIFICATION_MAIL_FROM;
		$esl_email_fields[0]['value'] = NOTIFICATION_MAIL_FROM;
	}else{
		$domain = str_replace('www.','',$_SERVER['SERVER_NAME']);
		$esl_email_fields[0]['label'] = "no-reply@{$domain}";
		$esl_email_fields[0]['value'] = "no-reply@{$domain}";
	}
		
	$esl_email_fields = array_merge($esl_email_fields,$email_fields);
	$esl_valid_email = true;
	
	if(!empty($_POST['esl_submit'])){
		unset($_POST['esl_submit']);
		$input_array  = ap_sanitize_input($_POST);
		
		//validate for valid email address
		if(!empty($input_array['esl_email_address'])){
			
			$esl_emails = explode(',',$input_array['esl_email_address']);
			$regex  = '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
			foreach ($esl_emails as $email){
				$email = trim($email);
				$result = preg_match($regex, $email);
				
				if(empty($result)){
					$esl_valid_email = false;
					break;
				}
			}
		}
		
		//if passed, store into database
		if($esl_valid_email){
			$esl_input['form_email'] 				= $input_array['esl_email_address'];
			$esl_input['esl_from_name'] 			= $input_array['esl_from_name'];
			$esl_input['esl_from_email_address'] 	= $input_array['esl_from_email_address'];
			$esl_input['esl_subject'] 				= $input_array['esl_subject'];
			$esl_input['esl_content'] 				= $input_array['esl_content'];
			$esl_input['esl_plain_text'] 			= $input_array['esl_plain_text'];
			
			if(empty($esl_input['esl_plain_text'])){
				$esl_input['esl_plain_text'] = 0;
			}
			
			//create the sql update string
			foreach ($esl_input as $key=>$value){
				$value = mysql_real_escape_string($value);
				$update_values .= "`$key`='$value',";
			}
			$update_values = substr($update_values,0,-1);
			
			$query = "UPDATE `ap_forms` set 
										$update_values
								  where 
							  	  		form_id='{$form_id}';";
			
			do_query($query);
			
			$_SESSION['AP_SUCCESS']['title'] = 'Success';
			$_SESSION['AP_SUCCESS']['desc']  = 'Notification settings have been saved.';
			
			header("Location: email_settings.php?id={$form_id}");
			exit;
		}else{
			$_SESSION['AP_ERROR']['title']   = $lang['error_title'];
			$_SESSION['AP_ERROR']['desc']    = $lang['error_desc'];
		}
		
	}else{
		//populate current values or default values
		$_POST['esl_email_address'] = $form_email;
		
		if(!empty($esl_from_name)){
			$_POST['esl_from_name'] = $esl_from_name;
		}elseif (NOTIFICATION_MAIL_FROM_NAME != ''){
			$_POST['esl_from_name'] = NOTIFICATION_MAIL_FROM_NAME;
		}else{
			$_POST['esl_from_name'] = 'MachForm';
		}
				
		$_POST['esl_from_email_address'] = $esl_from_email_address;
		
		if(!empty($esl_subject)){
			$_POST['esl_subject'] = $esl_subject;
		}elseif (NOTIFICATION_MAIL_SUBJECT != ''){
			$_POST['esl_subject'] = NOTIFICATION_MAIL_SUBJECT;
		}else{
			$_POST['esl_subject'] = '{form_name} [#{entry_no}]';
		}
		
		if(!empty($esl_content)){
			$_POST['esl_content'] = $esl_content;
		}else{
			$_POST['esl_content'] = '{entry_data}';
		}
		
		$_POST['esl_plain_text'] = $esl_plain_text;
		$_POST['esl_options_expand'] = 0;
	}
	//end left form handler --------
	
	//start right form handler --------
	$esr_email_fields = $email_fields;
	$esr_valid_email = true;
	
	if(!empty($_POST['esr_submit'])){
		unset($_POST['esr_submit']);
		$input_array  = ap_sanitize_input($_POST);
		
		//validate for valid email address
		if(!empty($input_array['esr_from_email_address'])){
			$regex  = '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
			$email = trim($input_array['esr_from_email_address']);
			$result = preg_match($regex, $email);
				
			if(empty($result)){
				$esr_valid_email = false;
			}
			
		}
		
		//if passed, store into database
		if($esr_valid_email){
			$esr_input['esr_email_address'] 		= $input_array['esr_email_address'];
			$esr_input['esr_from_name'] 			= $input_array['esr_from_name'];
			$esr_input['esr_from_email_address'] 	= $input_array['esr_from_email_address'];
			$esr_input['esr_subject'] 				= $input_array['esr_subject'];
			$esr_input['esr_content'] 				= $input_array['esr_content'];
			$esr_input['esr_plain_text'] 			= $input_array['esr_plain_text'];
			
			if(empty($esr_input['esr_plain_text'])){
				$esr_input['esr_plain_text'] = 0;
			}
			
			//create the sql update string
			foreach ($esr_input as $key=>$value){
				$value = mysql_real_escape_string($value);
				$update_values .= "`$key`='$value',";
			}
			$update_values = substr($update_values,0,-1);
			
			$query = "UPDATE `ap_forms` set 
										$update_values
								  where 
							  	  		form_id='{$form_id}';";
			
			do_query($query);
			
			$_SESSION['AP_SUCCESS']['title'] = 'Success';
			$_SESSION['AP_SUCCESS']['desc']  = 'Notification settings have been saved.';
			
			header("Location: email_settings.php?id={$form_id}");
			exit;
		}else{
			$_SESSION['AP_ERROR']['title']   = $lang['error_title'];
			$_SESSION['AP_ERROR']['desc']    = $lang['error_desc'];
			$_POST['esr_options_expand'] = 1;
		}
	}else{
		//populate current values or default values
		$_POST['esr_email_address'] = $esr_email_address;
			
		if(!empty($esr_from_name)){
			$_POST['esr_from_name'] = $esr_from_name;
		}elseif (NOTIFICATION_MAIL_FROM_NAME != ''){
			$_POST['esr_from_name'] = NOTIFICATION_MAIL_FROM_NAME;
		}else{
			$_POST['esr_from_name'] = 'MachForm';
		}
		
		if(!empty($esr_from_email_address)){
			$_POST['esr_from_email_address'] = $esr_from_email_address;
		}elseif(NOTIFICATION_MAIL_FROM != ''){
			$_POST['esr_from_email_address'] = NOTIFICATION_MAIL_FROM;
		}else{
			$domain = str_replace('www.','',$_SERVER['SERVER_NAME']);
			$_POST['esr_from_email_address'] = "no-reply@{$domain}";
		}
		
		if(!empty($esr_subject)){
			$_POST['esr_subject'] = $esr_subject;
		}else{
			$_POST['esr_subject'] = '{form_name} - Receipt';
		}
		
		if(!empty($esr_content)){
			$_POST['esr_content'] = $esr_content;
		}else{
			$_POST['esr_content'] = '{entry_data}';
		}
		
		$_POST['esr_plain_text'] = $esr_plain_text;
		$_POST['esr_options_expand'] = 0;
		
	}
	//end right form handler ---------
	
	if(!empty($_POST['esl_options_expand'])){
		$esl_style = '<style>.esl_options{ display: block; } </style>';
	}
	
	if(!empty($_POST['esr_options_expand'])){
		$esr_style = '<style>.esr_options{ display: block; } </style>';
	}
	
	$header_data =<<<EOT
	<link rel="stylesheet" href="email_settings.css" type="text/css">
	<script type="text/javascript" src="js/view.js"></script>
	<script type="text/javascript" src="js/jquery/jquery-core.js"></script>
	<script>
		function toggle_esl_options(){
			if($("#esl_toggle").text() == "more options"){
				$(".esl_options").slideDown("slow");
				$("#esl_toggle").text("hide options");
				$("#esl_toggle_img").attr("src","images/icons/resultset_up.gif");
				$("#esl_options_expand").val("1");
			}else{
				$(".esl_options").fadeOut("slow");
				$("#esl_toggle").text("more options");
				$("#esl_toggle_img").attr("src","images/icons/resultset_next.gif");
				$("#esl_options_expand").val("0");
			}
		}
		
		function toggle_esr_options(){
			if($("#esr_toggle").text() == "more options"){
				$(".esr_options").slideDown("slow");
				$("#esr_toggle").text("hide options");
				$("#esr_toggle_img").attr("src","images/icons/resultset_up.gif");
				$("#esr_options_expand").val("1");
			}else{
				$(".esr_options").fadeOut("slow");
				$("#esr_toggle").text("more options");
				$("#esr_toggle_img").attr("src","images/icons/resultset_next.gif");
				$("#esr_options_expand").val("0");
			}
		}
	</script>
	{$esl_style}
	{$esr_style}
EOT;
?>

<?php require('includes/header.php'); ?>

<div id="form_manager">
<?php show_message(); ?>
<div class="info">
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> Emails</h2>
	<p>Configure your email notification settings</b></p>
</div>
<h2 style="font-size: 150%;margin-bottom: 10px;">Send notification emails to <img src="images/icons/arrow_right.gif" align="absmiddle" /></h2> 
<div style="width: 49%;float: left;">
<h2 style="font-size: 150%;"><img src="images/icons/edit_user_48.gif" align="absmiddle" />You</h2>
<div id="es_left">

<!-- start left form -->
<div id="esl_main_body" class="integrated">
		
	<div id="esl_form_container">
		<form id="esl_form" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul >
		<li id="esl_li_1" <?php if(!$esl_valid_email){ echo 'class="error"'; } ?>>
			<label class="description" for="esl_email_address">Your Email Address </label>
			<div>
				<input id="esl_email_address" name="esl_email_address" class="element text medium" type="text" value="<?php echo $_POST['esl_email_address']; ?>"/> 
			</div> 
			<?php if(!$esl_valid_email){ echo "<p class=\"error\">{$lang['val_email']}</p>"; }; ?>
		</li>
		<li id="esl_li_2" class="section_break esl_options">
			  <h3><img src="images/icons/agt_utilities.gif" align="absmiddle" /> &nbsp;Email Header</h3>
		</li>				
		<li id="esl_li_3" class="esl_options">
			<label class="description" for="esl_from_name">From Name </label>
			<div>
				<input id="esl_from_name" name="esl_from_name" class="element text medium" type="text" value="<?php echo $_POST['esl_from_name']; ?>"/> 
			</div> 
		</li>		
		<li id="esl_li_4" class="esl_options">
			<label class="description" for="esl_from_email_address">From Email address </label>
			<div>
			<select class="element select medium" id="esl_from_email_address" name="esl_from_email_address"> 
				<?php
					foreach ($esl_email_fields as $data){
						if($_POST['esl_from_email_address'] == $data['value']){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
						echo "<option value=\"{$data['value']}\" {$selected}>{$data['label']}</option>";
					}
				?>
			</select>
			</div> 
		</li>		
		<li id="esl_li_5" class="section_break esl_options">
			<h3><img src="images/icons/mail_reply_16.gif" align="absmiddle"/> &nbsp;Email Content</h3>
		</li>		
		<li id="esl_li_6" class="esl_options">
			<label class="description" for="esl_subject">Subject </label>
			<div>
				<input id="esl_subject" name="esl_subject" class="element text large" type="text" value="<?php echo $_POST['esl_subject']; ?>"/> 
			</div> 
			
		</li>		
		<li id="esl_li_7"  class="esl_options">
			<label class="description" for="esl_content">Content </label>
			<div>
				<textarea id="esl_content" name="esl_content" class="element textarea medium"><?php echo $_POST['esl_content']; ?></textarea> 
			</div> 
		</li>		
		<li id="esl_li_8" class="esl_options">
			<label class="description" for="esl_plain_text"> </label>
			<span>
				<input id="esl_plain_text" name="esl_plain_text" class="element checkbox" type="checkbox" value="1" <?php if($_POST['esl_plain_text'] == 1){ echo 'checked="checked"'; } ?>/>
				<label class="choice" for="esl_plain_text">Send emails in simple text format</label>
			</span>
			<div style="clear: both;font-size: 95%;padding-top: 10px;margin-bottom: 10px">
			<img align="absmiddle" src="images/icons/information.gif"/> <b style="color: #444">Info</b><br/> You can insert <a href="template_variables.php?id=<?php echo $_REQUEST['id']; ?>" style="color: blue; border-bottom: 1px dotted #000;text-decoration: none">template variables</a> into the above fields. 
			</div>
		</li>
		<li id="esl_li_9" style="background-color: #fff">
		
			<div style="text-align: right">
				<a href="javascript: toggle_esl_options()" style="color: blue; border-bottom: 1px dotted #000;text-decoration: none" id="esl_toggle"><?php if(!empty($_POST['esl_options_expand'])){ echo 'hide options';} else { echo 'more options';} ?></a> <img align="absmiddle" src="images/icons/resultset_next.gif" id="esl_toggle_img"/>
			</div> 
		</li>
		<li class="buttons" style="background-color:#fff; padding-left: 0px; padding-top: 0px;">
			<input type="hidden" name="id" name="id" value="<?php echo $_REQUEST['id']; ?>"/>
			<input type="hidden" name="esl_options_expand" id="esl_options_expand" value="<?php echo $_POST['esl_options_expand']; ?>" />	
			<input id="esl_submit" class="button_text" type="submit" name="esl_submit" value="Save Changes"  />
			
		</li>		
		</ul>
		</form>	
	</div>

</div>
<!-- end left form -->

</div>

</div>
<div style="width: 50%;float: left; border-left: 2px dashed #ccc">
<h2 style="font-size: 150%; margin-left: 20px"><img src="images/icons/agt_family_48.gif" align="absmiddle" /> Your Users</h2>
<div id="es_right">

<!-- start right form  -->
<div id="esr_main_body" class="integrated">
		
	<div id="esr_form_container">
		<form id="esr_form" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul >
		
		<li id="esr_li_1" >
			<label class="description" for="esr_email_address">Send To </label>
			<div>
				<select class="element select medium" id="esr_email_address" name="esr_email_address" <?php if(empty($esr_email_fields)){ echo 'disabled="disabled"'; } ?>>
				<option value=""></a> 
				<?php
					foreach ($esr_email_fields as $data){
						if($_POST['esr_email_address'] == $data['value']){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
						echo "<option value=\"{$data['value']}\" {$selected}>{$data['label']}</option>";
					}
				?>
				</select> 
			</div> 
		</li>
		<?php if(empty($esr_email_fields)){ ?>
		<li id="esr_li_1" >
			<label class="description" for="esr_email_address"><img src="images/icons/warning.gif" align="top" /> No email field found</label>
			<div>
				To enable sending email to your users, an Email field is required on the form.
			</div> 
		</li>
		<?php } ?>
		<li class="section_break esr_options">
			  <h3><img src="images/icons/agt_utilities.gif" align="absmiddle" /> &nbsp;Email Header</h3>
		</li>		
		<li id="esr_li_2" class="esr_options">
			<label class="description" for="esr_from_name">From Name </label>
			<div>
				<input id="esr_from_name" name="esr_from_name" class="element text medium" type="text" value="<?php echo $_POST['esr_from_name']; ?>"/> 
			</div> 
		</li>		
		<li id="esr_li_3" class="esr_options<?php if(!$esr_valid_email){ echo ' error'; } ?>" >
			<label class="description" for="esr_from_email_address">From Email address </label>
			<div>
				<input id="esr_from_email_address" name="esr_from_email_address" class="element text medium" type="text" value="<?php echo $_POST['esr_from_email_address']; ?>"/> 
			</div> 
			<?php if(!$esr_valid_email){ echo "<p class=\"error\">{$lang['val_email']}</p>"; }; ?>
		</li>
		<li class="section_break esr_options">
			<h3><img src="images/icons/mail_forward_16.gif" align="absmiddle"/> &nbsp;Email Content</h3>
		</li>		
		<li id="esr_li_4" class="esr_options">
			<label class="description" for="esr_subject">Subject </label>
			<div>
				<input id="esr_subject" name="esr_subject" class="element text large" type="text" value="<?php echo $_POST['esr_subject']; ?>"/> 
			</div> 
		</li>		
		<li id="esr_li_5" class="esr_options">
			<label class="description" for="esr_content">Content </label>
			<div>
				<textarea id="esr_content" name="esr_content" class="element textarea medium"><?php echo $_POST['esr_content']; ?></textarea> 
			</div> 
		</li>		
		<li id="esr_li_6" class="esr_options">
			<label class="description" for="esr_plain_text"> </label>
			<span>
				<input id="esr_plain_text" name="esr_plain_text" class="element checkbox" type="checkbox" value="1" <?php if($_POST['esr_plain_text'] == 1){ echo 'checked="checked"'; } ?>/>
				<label class="choice" for="esr_plain_text">Send emails in simple text format</label>
			</span>
			<div style="clear: both;font-size: 95%;padding-top: 10px;margin-bottom: 10px">
			<img align="absmiddle" src="images/icons/information.gif"/> <b style="color: #444">Info</b><br/> You can insert <a href="template_variables.php?id=<?php echo $_REQUEST['id']; ?>" style="color: blue; border-bottom: 1px dotted #000;text-decoration: none">template variables</a> into the above fields. 
			</div> 
		</li>
		<li id="esr_li_7" style="background-color: #fff">
			<div style="text-align: right">
				<?php if(!empty($esr_email_fields)){ ?>
				<a href="javascript: toggle_esr_options()" style="color: blue; border-bottom: 1px dotted #000;text-decoration: none" id="esr_toggle"><?php if(!empty($_POST['esr_options_expand'])){ echo 'hide options';} else { echo 'more options';} ?></a> <img align="absmiddle" src="images/icons/resultset_next.gif" id="esr_toggle_img"/>
				<?php } ?>
			</div> 
		</li>
		<li class="buttons" style="background-color:#fff; padding-left: 0px; padding-top: 0px">	
			<input type="hidden" name="id" name="id" value="<?php echo $_REQUEST['id']; ?>"/>
			<input type="hidden" name="esr_options_expand" id="esr_options_expand" value="<?php echo $_POST['esr_options_expand']; ?>" />	
			<input id="esr_submit" class="button_text" type="submit" name="esr_submit" value="Save Changes"  <?php if(empty($esr_email_fields)){ echo 'disabled="disabled"'; } ?>/>
		</li>		
		</ul>
		</form>	
	</div>

</div>
<!-- end right form -->

</div>
</div>
<div style="clear: both; height: 20px"></div>
</div>
<?php require('includes/footer.php'); ?>
