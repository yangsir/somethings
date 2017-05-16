<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	session_start();	

	require('config.php');
	require('includes/db-core.php');
	require('includes/db-functions.php');
	require('includes/helper-functions.php');
	
	$ssl_suffix = get_ssl_suffix();
	
	if(file_exists("installer.php")){
		header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].get_dirname($_SERVER['PHP_SELF'])."/installer.php");
		exit;
	}
	
	//redirect to form manager if already logged-in
	if(!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] == true){
		header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].get_dirname($_SERVER['PHP_SELF'])."/manage_form.php");
		exit;
	}
	
	if(!empty($_POST['submit'])){
		$username = trim($_POST['admin_username']);
		$password = trim($_POST['admin_password']);
		if(($username != ADMIN_USER) || ($password != ADMIN_PASSWORD)){
			$_SESSION['AP_LOGIN_ERROR'] = 'Please enter the correct user and password!';
		}else{
			$_SESSION['logged_in'] = true;
			
			if(!empty($_SESSION['prev_referer'])){
				$next_page = $_SESSION['prev_referer'];
				
				unset($_SESSION['prev_referer']);
				header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].$next_page);
				
				exit;
			}else{
				header("Location: http{$ssl_suffix}://".$_SERVER['HTTP_HOST'].get_dirname($_SERVER['PHP_SELF'])."/manage_form.php");
				exit;
			}
		}
	}
	
	if(!empty($_GET['from'])){
		$_SESSION['prev_referer'] = base64_decode($_GET['from']);
	}
	
	$hide_nav = true;
	
?>

<?php require('includes/header.php'); ?>

<div id="form_manager" style="padding-left: 50px;padding-top: 50px;padding-bottom: 50px">
<?php show_message(); ?>
<div class="info" style="width: 40%">
	<h2><img src="images/icons/lock.gif" align="absmiddle" /> Admin Panel Login</h2>
	<p>Please login below to access Admin Panel</p>
</div>

<div id="form_container" style="align: center">
		<form id="form_login" class="appnitro"  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<ul style="width: 40%;">
		<?php if(!empty($_SESSION['AP_LOGIN_ERROR'])){ ?>    
			<li class="error" style="padding-top:20px;padding-bottom:20px;text-align: center">
				<label class="desc">				
				<?php echo $_SESSION['AP_LOGIN_ERROR']; ?>
				</label>
			</li>	
		<?php 
				unset($_SESSION['AP_LOGIN_ERROR']);	
			} 
		?>	
			<li class="highlighted"  style="padding-left: 30px;padding-top: 30px;padding-right: 30px">
								
				<label class="desc" for="admin_username"><img src="images/icons/edit_user.gif" align="absmiddle" style="padding-bottom: 5px"/> Username</label>
				<div>
					<input id="admin_username" name="admin_username" class="element text large" type="text" maxlength="255" value=""/> 
				</div>
				
			</li>		
			<li class="highlighted" style="padding-left: 30px;padding-bottom: 30px;padding-right: 30px">
				<label class="desc" for="admin_password"><img src="images/icons/decrypted.gif" align="absmiddle" style="padding-bottom: 5px"/> Password </label>
				<div>
					<input id="admin_password" name="admin_password" class="element text large" type="password" maxlength="255" value=""/> 
				</div> 
			</li>
    		<li class="buttons" style="padding-left:0px">
		    	<input id="login" class="button_text" type="submit" name="submit" value="Login" style="padding: 8px" />
			</li>
		</ul>
		</form>	
</div><br />

</div>
<?php require('includes/footer.php'); ?>
