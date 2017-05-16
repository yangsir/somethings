<?php

	session_start();
	
	require('config.php');
	require('includes/check-session.php');
	require('includes/db-core.php');
	require('includes/db-functions.php');
	require('includes/helper-functions.php');

	connect_db();
	
	$rows_per_page = 10;
	
	//check for id parameter, if exist, populate the pageno automatically
	if(!empty($_GET['id'])){
		$form_id = (int) trim($_GET['id']);
		
		$query  = "select form_id from `ap_forms` order by form_id asc";
		$result = do_query($query);
		
		while($row = do_fetch_result($result)){
			$form_id_lookup[] = (int) $row['form_id'];
		}
		$form_id_chunks = array_chunk($form_id_lookup,$rows_per_page);
		
		foreach ($form_id_chunks as $key=>$value){
			$index_key = array_search($form_id,$value,true);
			if($index_key !== false){
				$active_tab_auto = $index_key;
				$pageno_auto	 = $key + 1;
			}
		}
	}
	
	
	//check for form delete parameter
	if(!empty($_GET['delete'])){
		$deleted_form_id = (int) trim($_GET['delete']);
		delete_form($deleted_form_id);
	}
	
	//check for form duplicate parameter
	if(!empty($_GET['duplicate'])){
		$target_form_id = (int) trim($_GET['duplicate']);
		$result_form_id = duplicate_form($target_form_id);
		
		if(!empty($result_form_id)){
			$_SESSION['AP_SUCCESS']['title'] = 'Success';
			$_SESSION['AP_SUCCESS']['desc']  = 'Form duplicated.';
				
			header("Location: manage_form.php?id={$result_form_id}");
			exit;
		}
	}
	
	
	/****Pagination *************/
	//get page number for pagination
	if(!empty($pageno_auto)){
		$pageno = $pageno_auto;
	}elseif(!empty($_GET['pageno'])) {
	   $pageno = $_GET['pageno'];
	}else{
	   $pageno = 1;
	}
				
	//identify how many database rows are available
	$query = "select count(*) total_row from `ap_forms`";
	$result = do_query($query);
	$row = do_fetch_result($result);
	
	$numrows = $row['total_row'];
				
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
	
	
	
	//get form list
	$query = "select form_id,form_name,form_active,form_email from `ap_forms` order by form_id asc $limit";
	$result = do_query($query);
	$i=0;
	$form_id_array = array();
	while($row = do_fetch_result($result)){
		$form_list[$i]['form_id']     = $row['form_id'];
		$form_list[$i]['form_name']   = $row['form_name'];
		$form_list[$i]['form_active'] = $row['form_active']; 
		if(!empty($row['form_email'])){
			$form_list[$i]['form_email']  = $row['form_email']; 
		}else{
			$form_list[$i]['form_email']  = 'nobody'; 
		}
		$form_id_array[] = $row['form_id'];
		$i++;
	}
	
	//get total entries for each form
	foreach ($form_id_array as $form_id){
		//get all time entries
		$query = "select count(*) total_entry from `ap_form_{$form_id}`";
		$result = do_query($query);
		$row = do_fetch_result($result);
		$entries[$form_id]['total'] = $row['total_entry'];
	
		//get todays entries
		$query = "select count(*) today_entry from `ap_form_{$form_id}` where date_created >= date_format(curdate(),'%Y-%m-%d 00:00:00') ";
		$result = do_query($query);
		$row = do_fetch_result($result);
		$entries[$form_id]['today'] = $row['today_entry'];
		
		//get latest entry timing
		$query = "select date_created from `ap_form_{$form_id}` order by id desc limit 1";
		$result = do_query($query);
		$row = do_fetch_result($result);
		$entries[$form_id]['latest_entry'] = relative_date($row['date_created']);
	}
	
	$header_data =<<<EOT
	<script type="text/javascript" src="js/base.js"></script>
	<script type="text/javascript" src="js/rico.js"></script>
	<style>
		.accordionTabTitle {
		background-color:#6B79A5;
		border-bottom:1px solid #182052;
		border-style:solid none;
		border-top:1px solid #BDC7E7;
		border-width:1px 0px;
		color:#CED7EF;
		font-size:12px;
		height:30px;
		padding-left:5px;
		vertical-align: middle;
		}
	</style>
	<script language="javascript" type="text/javascript">
	
	function disable_form(form_id){
		var progress_image = $('progress_image_' + form_id);
		progress_image.style.visibility = "visible";

		$('activation_link_' + form_id).innerHTML = 'Processing ...';
		$('activation_link_' + form_id).href = '#';
			
		var url = 'ajax_form_activation.php';
		var pars = 'form_id=' + form_id + '&operation=disable';
		
		var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: showResponseDisabled });
		
	}
	
	function showResponseDisabled(originalRequest){
		var response = originalRequest.responseText;
		
		$('form_status_' + response).innerHTML = '未激活';
		$('image_status_' + response).src = 'images/icons/disabled.gif';
		$('activation_link_' + response).innerHTML = '激活';
		$('activation_link_' + response).href = 'javascript: enable_form(' + response + ')';
		
		var progress_image = $('progress_image_' + response);
		progress_image.style.visibility = "hidden";

		new Effect.Highlight('form_status_' + response,{duration:1});
		
	}
	
	function enable_form(form_id){
		var progress_image = $('progress_image_' + form_id);
		progress_image.style.visibility = "visible";
		
		$('activation_link_' + form_id).innerHTML = 'Processing ...';
		$('activation_link_' + form_id).href = '#';
						
		var url = 'ajax_form_activation.php';
		var pars = 'form_id=' + form_id + '&operation=enable';
		
		var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: showResponseEnabled });
		
	}
	
	function showResponseEnabled(originalRequest){
		var response = originalRequest.responseText;
		
		$('form_status_' + response).innerHTML = '启用';
		$('image_status_' + response).src = 'images/icons/checkbox.gif';
		$('activation_link_' + response).innerHTML = '禁用该表单';
		$('activation_link_' + response).href = 'javascript: disable_form(' + response + ')';
		
		var progress_image = $('progress_image_' + response);
		progress_image.style.visibility = "hidden";
		
		new Effect.Highlight('form_status_' + response,{duration:1});
				
	}
		
	</script>
	

	
EOT;

	//delete a form, definition, entries and uploaded files
	function delete_form($form_id){
		//remove from ap_forms 
		$query = "delete from `ap_forms` where form_id='$form_id'";
		do_query($query);
		
		//remove from ap_form_elements
		$query = "delete from `ap_form_elements` where form_id='$form_id'";
		do_query($query);
		
		//remove from ap_element_options
		$query = "delete from `ap_element_options` where form_id='$form_id'";
		do_query($query);
		
		//remove from ap_column_preferences
		$query = "delete from `ap_column_preferences` where form_id='$form_id'";
		do_query($query);
		
		//remove review table
		$query = "drop table if exists `ap_form_{$form_id}_review`";
		do_query($query);
		
		//remove the actual table
		$query = "drop table if exists `ap_form_{$form_id}`";
		do_query($query);
		
		//remove form folder 
		@full_rmdir(UPLOAD_DIR."/form_{$form_id}");
		if(DATA_DIR != UPLOAD_DIR){
			@full_rmdir(DATA_DIR."/form_{$form_id}");
		}
		return true;
	}

	//duplicate a form
	function duplicate_form($form_id){
		
		//set the new name
		$query 	= "select form_name,form_review from `ap_forms` where form_id='$form_id'";
		$result = do_query($query);
		$row 	= do_fetch_result($result);
		$form_review = $row['form_review'];
		$form_name 	 = trim($row['form_name']);
		$form_name .= " Copy";
		
		//get new form_id
		$query = "select max(form_id)+1 new_form_id from `ap_forms`";
		$result = do_query($query);
		$row 	= do_fetch_result($result);
		$new_form_id = trim($row['new_form_id']);
			
		//insert into ap_forms table
		$query = "select * from `ap_forms` where form_id='{$form_id}'";
		$result = do_query($query);
		
		$i = 0;
		$columns = array();
		$fields_num = mysql_num_fields($result);
		while($i < $fields_num){
			$meta = mysql_fetch_field($result, $i);
			
			if(($meta->name != 'form_id') && ($meta->name != 'form_name')){
				$columns[] = $meta->name;
			}
			
			$i++;
		}
		
		
		//build the query string
		$columns_joined = implode(",",$columns);
		$form_name = mysql_real_escape_string($form_name);	
		
		//insert to ap_forms
		$query = "insert into 
							`ap_forms`(form_id,form_name,{$columns_joined}) 
					   select 
							{$new_form_id},'{$form_name}',{$columns_joined} 
						from 
							`ap_forms` 
						where 
							form_id='$form_id'";
		do_query($query);
		
		//create the new table
		do_query("create table `ap_form_{$new_form_id}` like `ap_form_{$form_id}`");
		
				
		//copy ap_form_elements table
		$query = "select * from `ap_form_elements` limit 1";
		$result = do_query($query);
		
		$i = 0;
		$columns = array();
		$fields_num = mysql_num_fields($result);
		while($i < $fields_num){
			$meta = mysql_fetch_field($result, $i);
			
			if($meta->name != 'form_id'){
				$columns[] = $meta->name;
			}
			
			$i++;
		}
		$columns_joined = implode("`,`",$columns);
		$query = "insert into 
							`ap_form_elements`(form_id,`{$columns_joined}`) 
					   select 
							{$new_form_id},`{$columns_joined}` 
						from 
							`ap_form_elements` 
						where 
							form_id='$form_id'";
		do_query($query);
		
		//copy ap_element_options table
		$query = "select * from `ap_element_options` limit 1";
		$result = do_query($query);
		
		$i = 0;
		$columns = array();
		$fields_num = mysql_num_fields($result);
		while($i < $fields_num){
			$meta = mysql_fetch_field($result, $i);
			
			if(($meta->name != 'form_id') && ($meta->name != 'aeo_id')){
				$columns[] = $meta->name;
			}
			
			$i++;
		}
		$columns_joined = implode("`,`",$columns);
		$query = "insert into 
							`ap_element_options`(form_id,`{$columns_joined}`) 
					   select 
							{$new_form_id},`{$columns_joined}` 
						from 
							`ap_element_options` 
						where 
							form_id='$form_id'";
		do_query($query);
		
		
		//copy ap_column_preferences table
		$query = "select * from `ap_column_preferences` limit 1";
		$result = do_query($query);
		
		$i = 0;
		$columns = array();
		$fields_num = mysql_num_fields($result);
		while($i < $fields_num){
			$meta = mysql_fetch_field($result, $i);
			
			if(($meta->name != 'form_id') && ($meta->name != 'acp_id')){
				$columns[] = $meta->name;
			}
			
			$i++;
		}
		$columns_joined = implode("`,`",$columns);
		$query = "insert into 
							`ap_column_preferences`(form_id,`{$columns_joined}`) 
					   select 
							{$new_form_id},`{$columns_joined}` 
						from 
							`ap_column_preferences` 
						where 
							form_id='$form_id'";
		do_query($query);
		
		//copy ap_form_xx_review table if exists
		if(!empty($form_review)){
			do_query("CREATE TABLE `ap_form_{$new_form_id}_review` like `ap_form_{$form_id}_review`");
		}
		
		//create form folder
		$old_mask = umask(0);
		mkdir(DATA_DIR."/form_{$new_form_id}",0777);
		mkdir(DATA_DIR."/form_{$new_form_id}/css",0777);
		if(DATA_DIR != UPLOAD_DIR){
			mkdir(UPLOAD_DIR."/form_{$new_form_id}",0777);
		}
		mkdir(UPLOAD_DIR."/form_{$new_form_id}/files",0777);
		umask($old_mask);
		
		//copy css file	
		copy(DATA_DIR."/form_{$form_id}/css/view.css",DATA_DIR."/form_{$new_form_id}/css/view.css");
		
		return $new_form_id;
	}
	
?>

<?php require('includes/header.php'); ?>
<div id="form_manager">
<?php show_message(); ?>
<div class="info">

	<div class="create_new_form">
		<a href="edit_form.php"><img src="images/create_new_form.gif" align="absmiddle"/></a>
	</div>
	<h2>表单管理</h2>
	<p>创建，编辑，管理表单</p>
</div>




<?php 
	$i=($pageno -1) * $rows_per_page + 1;
	$first_row_number = $i;
	if(!empty($form_list)){

	echo '<div id="accordionDiv">';	
		
	foreach ($form_list as $data){ 
		
		 if(!empty($data['form_active'])){ 
		 	$form_status 	 = '启用'; 
		 	$image_status 	 = 'checkbox.gif';
		 	$activation_text = '禁用该表单';
		 	$activation_link = 'disable_form';
		 }else{ 
		 	$form_status 	 = '未激活'; 
		 	$image_status 	 = 'disabled.gif';
		 	$activation_text = '启用该表单';
		 	$activation_link = 'enable_form';
		 }; 
		 
		 
?> 
   <div id="overviewPanel">
     <div id="overviewHeader" class="accordionTabTitle">
       <h3><?php echo "$i.&nbsp; {$data['form_name']}" ?></h3>
      </div>
      <div id="panel1Content">
	      <table width="99%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;">
			  <tr align="center" valign="middle">
			    <td width="8%"><a href="<?php echo "manage_entries.php?id={$data['form_id']}"; ?>"><img src="images/icons/entries_32.gif" /></a></td>
			    <td width="8%"><a href="<?php echo "edit_form.php?id={$data['form_id']}"; ?>"><img src="images/icons/edit_form_32.gif" /></a></td>
			    <td width="8%"><a href="<?php echo "edit_css.php?id={$data['form_id']}"; ?>"><img src="images/icons/colorize_32.gif" /></a></td>
			    <td width="8%"><a href="<?php echo "email_settings.php?id={$data['form_id']}"; ?>"><img src="images/icons/mail_forward_32.gif" /></a></td>
			    
			    <td width="8%">&nbsp;</td>
			    <td width="8%"><a href="view.php?id=<?php echo $data['form_id']; ?>" target="_blank"><img src="images/icons/view_form_32.gif" /></a></td>
			    <td width="30%%">&nbsp;</td>
			    <td width="8%"><a href="<?php echo "manage_form.php?duplicate={$data['form_id']}"; ?>"><img src="images/icons/copy_32.gif" /></a></td>
			    <td width="6%"><a href="manage_form.php?pageno=<?php echo $pageno; ?>&delete=<?php echo $data['form_id']; ?>" onclick="javascript: return confirm('Are you sure you want to delete this form and all associated data?');"><img src="images/icons/cross.gif" /></a></td>
			  </tr>
			  <tr align="center" >
			    <td ><a href="<?php echo "manage_entries.php?id={$data['form_id']}"; ?>">调查结果</a></td>
			    <td><a href="<?php echo "edit_form.php?id={$data['form_id']}"; ?>">编辑表单</a></td>
			    <td nowrap><a href="<?php echo "edit_css.php?id={$data['form_id']}"; ?>"> CSS编辑</a></td>
			    <td nowrap><a href="<?php echo "email_settings.php?id={$data['form_id']}"; ?>">Emails</a></td>
			   
			    <td>&nbsp;</td>
			    <td><a href="view.php?id=<?php echo $data['form_id']; ?>" target="_blank">浏览表单</a></td>
			    <td>&nbsp;</td>
			    <td><a href="<?php echo "manage_form.php?duplicate={$data['form_id']}"; ?>">复制</a></td>
			    <td><a href="manage_form.php?pageno=<?php echo $pageno; ?>&delete=<?php echo $data['form_id']; ?>" onclick="javascript: return confirm('你确定要删除表单和所有数据吗?');">删除</a></td>
			  </tr>
			</table> 
      		<div style="clear:both; padding-left: 10px; padding-top: 10px">
				 今天的调查: <strong><?php echo $entries[$data['form_id']]['today']; ?></strong><br>
				总的调查: <strong><?php echo $entries[$data['form_id']]['total']; ?></strong><br><br>
				最新的调查: <strong><?php echo $entries[$data['form_id']]['latest_entry']; ?></strong><br>
						
				<div id="edit_form_email_text_<?php echo $data['form_id']; ?>" style="margin-top: 15px;display:block">
					发送通知给: <strong><span id="form_email_name_box_<?php echo $data['form_id']; ?>"><?php echo $data['form_email']; ?></span></strong>
				</div>
								
				
				Form Status: 
				<strong><span id="form_status_<?php echo $data['form_id']; ?>"><?php echo $form_status; ?></span></strong>&nbsp;&nbsp;<img id="image_status_<?php echo $data['form_id']; ?>" align="absmiddle" src="images/icons/<?php echo $image_status; ?>" />&nbsp;&nbsp;&nbsp;<a id="activation_link_<?php echo $data['form_id']; ?>" href="javascript: <?php echo $activation_link; ?>(<?php echo $data['form_id']; ?>)" style="text-decoration: none; border-bottom: 1px dotted #000"><?php echo $activation_text; ?></a>
				&nbsp;&nbsp;<img id="progress_image_<?php echo $data['form_id']; ?>" align="absmiddle" src="images/loader-red.gif" style="visibility: hidden"/>
				
				
			</div>
		</div>
   </div>
<?php 
		$i++;
	} 
	
	$last_row_number = --$i;
	
	if(!empty($active_tab_auto)){
		$active_tab = $active_tab_auto;
	}else{
		$active_tab = 0;
	}
	
	echo '</div>';
	echo '<script type="text/javascript">';
	echo 'new Rico.Accordion( $(\'accordionDiv\'), {panelHeight:200,expandedBg: \'#4B75B3\',collapsedBg: \'#4B75B3\',hoverBg: \'#3661A1\', onLoadShowTab: '.$active_tab.'} );';
	echo '</script>'; 

	}else{
		echo "<div style=\"height: 200px; text-align: center;padding-top: 70px\"><h2>You have no form yet. Go create one!</h2></div>";
	}
?>   
   




<!-- start paging div -->
<div id="paging" >
<br>
<?php      
      if(!empty($lastpage))
		{
			
			
			if ($pageno != 1) 
			{
			   
			   if($lastpage > 19){	
			   	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=1'>&lt;&lt;First</a> ";
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
					 echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$i' $active_style>$i</a> ";
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
						 echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$j' $active_style>$j</a> ";
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
						 echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$i' $active_style>$i</a> ";
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
							echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$j' $active_style>$j</a> ";
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
							 echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$j' $active_style>$j</a> ";
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
						echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$i' $active_style>$i</a> ";
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
			   	echo " <a href='{$_SERVER['PHP_SELF']}?pageno=$lastpage'>Last&gt;&gt;</a> ";
			   }
			}
			
			
			//next we inform the user of his current position in the sequence of available pages
			?>
			<div class="footer">
				Viewing <strong><?php echo $first_row_number.'-'.$last_row_number; ?></strong> of <strong><?php echo $numrows; ?></strong> forms
			</div>
			<?php
		}
?>
</div>
<!-- end paging div - -->



</div>
<?php require('includes/footer.php'); ?>