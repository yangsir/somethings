<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	ini_set("display_errors","1");

	if(!file_exists("config.php")){
		$state = 'show_error_no_config';
	}else{
		///check is machform already installed or not?
		@include("config.php");
		@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		$mysql_connect_error = mysql_error();
		
		@mysql_select_db(DB_NAME);
		$mysql_select_db_error = mysql_error();
		
		if(!empty($mysql_connect_error)){
			$mysql_select_db_error = "Unable to connect to MySQL";
		}
		
		@mysql_query("select count(*) from `ap_forms`");
		$error = mysql_error();
		
				
		if(!empty($error)){ //MachForm already installed, display a warning instead
			$state = 'show_error_no_previous_version';	
		}else{ //do an install or pre-installation check
			
			if(!empty($_REQUEST['run_upgrade'])){ //do upgrade
				//create table ap_column_preferences
				$query = "CREATE TABLE `ap_column_preferences` (
																  `acp_id` int(11) NOT NULL auto_increment,
																  `form_id` int(11) default NULL,
																  `element_name` varchar(255) NOT NULL default '',
																  `position` int(11) NOT NULL default '0',
																  PRIMARY KEY  (`acp_id`),
																  KEY `acp_position` (`form_id`,`position`)
																);";
				mysql_query($query);
				$error_1 = mysql_error();
				if(!empty($error_1)){
					$upgrade_errors[] = $error_1;
				}
				
				//add new columns to ap_forms table
				$query = "ALTER TABLE `ap_forms`
											  ADD COLUMN `form_review` int(11) NOT NULL default '0',
											  ADD COLUMN `esl_from_name` text,
											  ADD COLUMN `esl_from_email_address` varchar(255) default NULL,
											  ADD COLUMN `esl_subject` text,
											  ADD COLUMN `esl_content` mediumtext,
											  ADD COLUMN `esl_plain_text` int(11) NOT NULL default '0',
											  ADD COLUMN `esr_email_address` varchar(255) default NULL,
											  ADD COLUMN `esr_from_name` text,
											  ADD COLUMN `esr_from_email_address` varchar(255) default NULL,
											  ADD COLUMN `esr_subject` text,
											  ADD COLUMN `esr_content` mediumtext,
											  ADD COLUMN `esr_plain_text` int(11) NOT NULL default '0';";
				mysql_query($query);
				$error_2 = mysql_error();
				if(!empty($error_2)){
					$upgrade_errors[] = $error_2;
				}
				
				//change varchars colum field in ap_forms table
				$query = "ALTER TABLE `ap_forms`
											  CHANGE COLUMN `form_name` `form_name` text,
											  CHANGE COLUMN `form_description` `form_description` text,
											  CHANGE COLUMN `form_redirect` `form_redirect` text,
											  CHANGE COLUMN `form_success_message` `form_success_message` text;";
				mysql_query($query);
				$error_3 = mysql_error();
				if(!empty($error_3)){
					$upgrade_errors[] = $error_3;
				}
				
				//change varchars colum field in ap_element_options table
				$query = "ALTER TABLE `ap_element_options`
											  CHANGE COLUMN `option` `option` text;";
				mysql_query($query);
				$error_4 = mysql_error();
				if(!empty($error_4)){
					$upgrade_errors[] = $error_4;
				}
				
				//change varchars colum field in ap_form_elements table
				$query = "ALTER TABLE `ap_form_elements`
											  CHANGE COLUMN `element_title` `element_title` text,
											  CHANGE COLUMN `element_guidelines` `element_guidelines` text,
											  CHANGE COLUMN `element_default_value` `element_default_value` text;";
				mysql_query($query);
				$error_5 = mysql_error();
				if(!empty($error_5)){
					$upgrade_errors[] = $error_5;
				}
				
				//append new styles to all current css files
				//this one is not very important, so no need reporting for this
				if(is_writable(DATA_DIR)){
					
					$new_style =<<<EOT
/** Form Review **/
#machform_review_table tbody tr:hover
{
	background-color: #FFF7C0;
}
.alt{
	background: #efefef;
}
#machform_review_table td
{
	text-align: left;
	border-bottom:1px solid #DEDEDE;
	padding:5px 10px;
}
/** Integrated Form **/
.integrated *{
	font-family:"Lucida Grande", Tahoma, Arial, Verdana, sans-serif;
	color: #000; 
}

.integrated #top, .integrated #bottom, .integrated h1{
	display: none;
}

.integrated #form_container{
    border: none;
	width: 99%;
	background: none;
}

.integrated #footer{
	text-align: left;
	padding-left: 10px;
	width: 99%;
}

.integrated #footer.success{
	text-align: center;
}

.integrated form.appnitro
{
	margin:0px 0px 0;
	
}

.integrated form .section_break h3
{
	border: none !important;
}

.integrated #error_message h3
{
	border: none !important;
	
}
EOT;
					
					$query = "select form_id from ap_forms";
					$res = mysql_query($query);
					while($row = mysql_fetch_array($res)){
						$form_id = $row['form_id'];
						$target_filename = DATA_DIR."/form_{$form_id}/css/view.css";
						if(is_writable($target_filename)){
							$fp = fopen($target_filename,"a");
							fwrite($fp,$new_style);
							fclose($fp);
						}
					}
				}
				
				if(empty($upgrade_errors)){
					$install_message = "<b>Congratulations!</b> You have completed the upgrade process. MachForm version 2 is now installed.";
				}else{
					$install_message = "An error has occured during upgrade.";
				}
											
				$state = 'install_report';
			}else{
					
				//do a pre installation check
				$state = 'show_preinstall_check';
							
				//check for PHP version
				if(version_compare(PHP_VERSION,"4.3.0",">=")){
					$is_php_version_passed = true;
				}else{
					$is_php_version_passed = false;
					$pre_install_has_error = true;
				}
				
				//check for MySQL version
				if(version_compare(mysql_get_server_info(),"4.1.0",">=")){
					$is_mysql_version_passed = true;
				}else{
					$is_mysql_version_passed = false;
					$pre_install_has_error = true;
				}
							
				//check for MySQL user,password and database existance
				if(empty($mysql_connect_error)){
					$is_mysql_connect_passed = true;
				}else{
					$is_mysql_connect_passed = false;
					$pre_install_has_error = true;
				}
				
				if(empty($mysql_select_db_error)){
					$is_mysql_select_db_passed = true;
				}else{
					$is_mysql_select_db_passed = false;
					$pre_install_has_error = true;
				}
				
				//check for data folder permission
				if(is_writable(DATA_DIR)){
					$is_data_dir_writable = true;
				}else {
					$is_data_dir_writable = false;
					$pre_install_has_error = true;
					$data_dir_writable_error = "MachForm require <b>read</b> and <b>write</b> access to this folder. Please set the correct permission.";
				}
				
				//check for current tables, make sure that version 2 is not installed already
				$query = "select form_review from ap_forms limit 1";
				mysql_query($query);
				$error_msg = mysql_error();
				if(empty($error_msg)){
					$is_version2_already_installed = true;
					$pre_install_has_error = true;
					$version2_already_installed_error = "Your installation already has the most recent version (Version 2).";
				}else{
					$is_version2_already_installed = false;
				}
				
				if($pre_install_has_error){
					$pre_install_message = "Your system does not match the minimum requirements necessary. Please take the appropriate actions to correct the errors.";
				}else{
					$pre_install_message = "Your system passed the minimum requirements necessary. You can now start the upgrade process.";
				}
			}
		}
		
	}
		
	$hide_nav = true;
?>

<?php require('includes/header.php'); ?>

<div id="form_manager" style="padding-left: 50px;padding-top: 50px;padding-bottom: 50px">

<?php
	if($state == 'show_error_no_config'){
?>
<div class="info" style="width: 80%">
	<h2><img src="images/icons/package_applications.gif" align="absmiddle" /> MachForm Installer Error</h2>
	<p>Please fix the following error before you can continue the installation process:</p>
</div>

<div id="form_container" style="align: center">
		<form id="form_login" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul style="width: 80%;">
			<li class="error" style="padding-left: 15px;padding-top: 15px;">
				<label class="desc"><img src="images/icons/stop.gif" align="absmiddle" style="padding-bottom: 5px"/> Missing configuration file</label>
				<br />
				<div><p>
				There doesn't seem to be a <b>config.php</b> file. We need this before we can get started.
				</p>
				<br /><br />
				<p> In the base directory of your installation you'll find a file  called <strong>config-empty.php</strong>. Open this file and fill in the required database information.</p><br /><br />
				<p> After the details are filled in you <strong> must rename the file config.php </strong> before continuing. </p><br />
				</div>
			</li>
    		<li class="buttons" style="padding-left:0px">
		    	<input id="login" class="button_text" type="submit" name="submit" value="RETRY" style="padding: 8px" />
			</li>
		</ul>
		</form>	
</div><br />

<?php }elseif ($state == 'show_preinstall_check'){ ?>

<div class="info" style="width: 80%">
	<h2><img src="images/icons/package_applications.gif" align="absmiddle" /> MachForm 2.0 Pre-Upgrade Check</h2>
	<p><?php echo $pre_install_message; ?></p>
</div>

<div id="form_container" style="align: center">
		<form id="form_login" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul style="width: 80%;">
			<li class="<?php if($is_php_version_passed){ echo 'highlighted'; }else{ echo 'error'; }; ?>" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/<?php if($is_php_version_passed){ echo 'checkbox'; }else{ echo 'cross'; }; ?>_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;PHP Version &gt;= 4.3.0</label>
			</li>
			
			<li class="<?php if($is_mysql_version_passed){ echo 'highlighted'; }else{ echo 'error'; }; ?>" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/<?php if($is_mysql_version_passed){ echo 'checkbox'; }else{ echo 'cross'; }; ?>_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;MySQL Version &gt;= 4.1.0</label>
			</li>
			<li class="<?php if($is_mysql_connect_passed){ echo 'highlighted'; }else{ echo 'error'; }; ?>" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/<?php if($is_mysql_connect_passed){ echo 'checkbox'; }else{ echo 'cross'; }; ?>_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Correct User and Password for MySQL</label><?php echo $mysql_connect_error; ?>
			</li>
			<li class="<?php if($is_mysql_select_db_passed){ echo 'highlighted'; }else{ echo 'error'; }; ?>" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/<?php if($is_mysql_select_db_passed){ echo 'checkbox'; }else{ echo 'cross'; }; ?>_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Establishing a Connection to Database [<?php echo DB_NAME; ?>]</label><?php echo $mysql_select_db_error; ?>
			</li>
			<li class="<?php if($is_data_dir_writable){ echo 'highlighted'; }else{ echo 'error'; }; ?>" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/<?php if($is_data_dir_writable){ echo 'checkbox'; }else{ echo 'cross'; }; ?>_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Writable Data Folder [<?php echo DATA_DIR; ?>]</label><?php echo $data_dir_writable_error; ?>
			</li>
			<li class="<?php if(!$is_version2_already_installed){ echo 'highlighted'; }else{ echo 'error'; }; ?>" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/<?php if(!$is_version2_already_installed){ echo 'checkbox'; }else{ echo 'cross'; }; ?>_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Checking Installed Version</label><?php echo $version2_already_installed_error; ?>
			</li>
    		<li class="buttons" style="padding-left:0px">
		    	<?php if($pre_install_has_error){ ?>
    				<input id="login" class="button_text" type="submit" name="submit" value="Check Again" style="padding: 8px" />
    			<?php } else { ?>
    			    <input type="hidden" id="run_upgrade" name="run_upgrade" value="1" />
    				<input id="login" class="button_text" type="submit" name="submit" value="Run Upgrade Process" style="padding: 8px" />
    			<?php } ?>
			</li>
		</ul>
		</form>	
</div><br />

<?php }elseif ($state == 'show_error_no_previous_version'){ ?>

<div class="info" style="width: 80%">
	<h2><img src="images/icons/package_applications.gif" align="absmiddle" /> No Previous Version of MachForm Found </h2>
	<p>Please follow the step below to be able to continue:</p>
</div>

<div id="form_container" style="align: center">
		<form id="form_login" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul style="width: 80%;">
			<li class="error" style="padding-left: 15px;padding-top: 15px;">
				<label class="desc"><img src="images/icons/stop.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Can't find previous version of MachForm</label>
				<br />
				<div><p>
				In order to upgrade to version 2.0, you need to have version 1.2 installed.
				</p>
				<br />
				<p> Make sure to double check your config.php file for any error or typo.</p><br />
				<p> If this is the first time you use MachForm, then go to the <a href="installer.php">installer page</a> instead.</p><br />
				</div>
			</li>
    	</ul>
		</form>	
</div><br />

<?php }elseif ($state == 'install_report'){ ?>


<div class="info" style="width: 80%">
	<h2><img src="images/icons/package_applications.gif" align="absmiddle" /> MachForm Upgrade Report</h2>
	<p><?php echo $install_message; ?></p>
</div>

<div id="form_container" style="align: center">
		<form id="form_login" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul style="width: 80%;">
<?php if(empty($upgrade_errors)){ ?>			
			<li class="highlighted" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/checkbox_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Table <b>ap_column_preferences</b> created</label>
			</li>
			<li class="highlighted" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/checkbox_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Added new fields to <b>ap_forms</b> table</label>
			</li>
			<li class="highlighted" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/checkbox_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Table <b>ap_forms</b> upgraded</label>
			</li>
			<li class="highlighted" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/checkbox_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Table <b>ap_form_elements</b> upgraded</label>
			</li>
			<li class="highlighted" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/checkbox_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;Table <b>ap_element_options</b> upgraded</label>
			</li>
			<li class="highlighted" style="padding-left: 15px;padding-top: 10px;">
				<label class="desc"><img src="images/icons/checkbox_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;<b>Upgrade completed!</b></label>
			</li>
			<li style="padding-left: 0px;padding-top: 10px; margin-top: 5px">
				<img src="images/icons/information.gif" align="absmiddle" /> <b>Important:</b> Please delete this installer file (<b>upgrade.php</b>) now. <br /><br />After deleting this file, you can login to <a href="index.php">MachForm admin panel</a> 
			</li>
<?php } else{ ?>
			<li class="error" style="padding-left: 15px;padding-top: 15px;">
				<label class="desc"><img src="images/icons/stop.gif" align="absmiddle" style="padding-bottom: 5px"/> An error has occured during installation</label>
			</li>
			
			<?php
					foreach ($upgrade_errors as $error_msg){ ?>
						<li class="highlighted" style="padding-left: 15px;padding-top: 10px;">
							<label class="desc"><img src="images/icons/cross_16.gif" align="absmiddle" style="padding-bottom: 5px"/> &nbsp;<?php echo $error_msg; ?></label>
			</li>
			<?php	}
			?>

<?php } ?>
		    <?php if(!empty($upgrade_errors)){ ?>
		    <li class="buttons" style="padding-left:0px">
    			<input type="hidden" id="run_upgrade" name="run_upgrade" value="1" />
    			<input id="login" class="button_text" type="submit" name="submit" value="Try Again" style="padding: 8px" />
    		</li>	
    		<?php } ?>	
			
		</ul>
		</form>	
</div><br />

<?php } ?>

</div>
<?php require('includes/footer.php'); ?>
