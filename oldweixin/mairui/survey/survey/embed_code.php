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

		
	//get form detail
	$query = "select form_name,form_frame_height,form_captcha from `ap_forms` where form_id='$form_id'";
	$result = do_query($query);
	$row = do_fetch_result($result);
	$form_name = $row['form_name'];
	
	if(empty($row['form_captcha'])){
		$frame_height = $row['form_frame_height'] + 80;
	}else{
		$frame_height = $row['form_frame_height'] + 250;
	}
	
	$page_name = 'embed_code';
	
	$ssl_suffix = get_ssl_suffix();

	//construct standard form code
	$standard_form_code = '<iframe height="'.$frame_height.'" allowTransparency="true" frameborder="0" scrolling="no" style="width:100%;border:none" src="http'.$ssl_suffix.'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/embed.php?id='.$form_id.'" title="'.$form_name.'"><a href="http'.$ssl_suffix.'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/view.php?id='.$form_id.'" title="'.$form_name.'">'.$form_name.'</a></iframe>';	
	
	$form_link_code = '<a href="http'.$ssl_suffix.'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/view.php?id='.$form_id.'" title="'.$form_name.'">'.$form_name.'</a>';
	
	
	$current_dir 	  = rtrim(dirname($_SERVER['PHP_SELF']));
	if($current_dir == "/" || $current_dir == "\\"){
		$current_dir = '';
	}
	
	$absolute_dir_path = rtrim(dirname($_SERVER['SCRIPT_FILENAME'])); 
	
	$advanced_form_code =<<<EOT
<?php
	require("{$absolute_dir_path}/machform.php");
	\$mf_param['form_id'] = {$form_id};
	\$mf_param['base_path'] = 'http{$ssl_suffix}://{$_SERVER['HTTP_HOST']}{$current_dir}/';
	display_machform(\$mf_param);
?>
EOT;

	$header_data =<<<EOT
	 <script type="text/javascript" src="js/jquery/jquery-core.js"></script>
	 <link rel="stylesheet" href="css/jquery-tabs.css" type="text/css" media="screen">
	 <script type="text/javascript" src="js/jquery/jquery-tabs.js"></script>
	 <script>
	  $(document).ready(function(){
	    $("#embed_code_tabs > ul").tabs({fx: {opacity: 'toggle' }});
	  });
	 </script>
EOT;
?>

<?php require('includes/header.php'); ?>

<div id="form_manager" style="padding-bottom: 50px;">
<div class="info">
	<h2><a class="breadcrumb" href="manage_form.php?id=<?php echo $form_id; ?>"><?php echo $form_name; ?></a> <img src="images/icons/resultset_next.gif" align="bottom" /> Embed Code</h2>
	<p>Copy and paste one of the code below into your website</p>
</div>
 		<div id="embed_code_tabs" class="flora">
            <ul>

                <li><a href="#fragment-1"><span>Standard Form Code</span></a></li>
                <li><a href="#fragment-2"><span>Advanced Form Code</span></a></li>
                <li><a href="#fragment-3"><span>Simple Link</span></a></li>
            </ul>
            <div id="fragment-1">
                <img align="bottom" src="images/icons/resultset_down.gif" style="position: relative; margin-top: -10px; left: 60px;"/>
                <form id="form_edit_css" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<ul>
					<li class="highlighted">
						<label class="desc" for="css_data">Standard Form Code:</label>
						<div>
							<textarea id="css_data" name="css_data" class="element textarea small" onclick="javascript: this.select()"><?php echo $standard_form_code; ?></textarea> 
							<div style="padding-bottom: 10px;padding-top:10px; color: #444444;font-size:95%">
							<img src="images/icons/flag_green.gif" align="absmiddle" /> Use this code to embed your form to any page of your website or blog.<br /><br />
							<img src="images/icons/information.gif" align="absmiddle" /> <b>Instructions</b><br />
							<span style="color:#999999">
							<p style="padding-top: 5px;">1. Copy and paste the code into your web page.</p>
							<p style="padding-top: 5px;">2. Publish the file on your server.</p>
							<p style="padding-top: 5px;">3. Adjust the "height" variable of the iframe as needed.</p>
							<p style="padding-top: 5px;">4. Adjust your form CSS file to match your website style as needed.</p>
							</span>
							</div>
						</div> 
					</li>
		    	</ul>
				</form>	

            </div>
            <div id="fragment-2">
               <img align="bottom" src="images/icons/resultset_down.gif" style="position: relative; margin-top: -10px; left: 220px;"/>
               <form id="form_edit_css" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<ul>
					<li class="highlighted">
						<label class="desc" for="css_data">Advanced Form Code:</label>
						<div>
							<textarea id="css_data" name="css_data" class="element textarea small" onclick="javascript: this.select()"><?php echo $advanced_form_code; ?></textarea> 
							<div style="padding-bottom: 10px;padding-top:10px; color: #444444;font-size:95%">
							<img src="images/icons/flag_red.gif" align="absmiddle" /> This is for advanced user only. Use this code to integrate your form into your *.php pages without using iframe. Some PHP and CSS knowledge might be required.<br /><br />
							<img src="images/icons/information.gif" align="absmiddle" /> <b>Instructions</b><br />
							<span style="color:#999999">
							<p style="padding-top: 5px;">1. Copy and paste the code into your PHP page.</p>
							<p style="padding-top: 5px;">2. Publish the file on your server.</p>
							<p style="padding-top: 5px;">3. Adjust the CSS of your form to match your current page.</p>
							<p style="padding-top: 5px;">4. This code might not work on certain PHP page, thus it's not guaranteed to work on all pages. In case of failure, use Standard Form Code instead.</p>
							</span>
						</div> 
					</li>
		    	</ul>
				</form> 
            </div>
            <div id="fragment-3">
               <img align="bottom" src="images/icons/resultset_down.gif" style="position: relative; margin-top: -10px; left: 345px;"/>
               <form id="form_edit_css" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<ul>
					<li class="highlighted">
						<label class="desc" for="css_data">Simple Link:</label>
						<div>
							<textarea id="css_data" name="css_data" class="element textarea small" onclick="javascript: this.select()"><?php echo $form_link_code; ?></textarea> 
							<div style="padding-bottom: 10px;padding-top:10px; color: #444444;font-size:95%">
							<img src="images/icons/flag_green.gif" align="absmiddle" /> Use this code to provide a link to your complete form.<br /><br />
							<img src="images/icons/information.gif" align="absmiddle" /> <b>Instructions</b><br />
							<span style="color:#999999">
							<p style="padding-top: 5px;">1. Copy and paste the code into your PHP page.</p>
							<p style="padding-top: 5px;">2. Publish the file on your server.</p>
							</span>
						</div> 
					</li>
		    	</ul>
				</form>	<br /><br />
            </div>
        </div>


</div>

<?php require('includes/footer.php'); ?>
