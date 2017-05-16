<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	
	//this function accept 'YYYY-MM-DD HH:MM:SS'
	function relative_date($input_date) {
	    
	    $tz = 0;    // change this if your web server and weblog are in different timezones
	           			        
	    $posted_date = str_replace(array('-',' ',':'),'',$input_date);            
	    $month = substr($posted_date,4,2);
	    
	    if ($month == "02") { // february
	    	// check for leap year
	    	$leapYear = isLeapYear(substr($posted_date,0,4));
	    	if ($leapYear) $month_in_seconds = 2505600; // leap year
	    	else $month_in_seconds = 2419200;
	    }
	    else { // not february
	    // check to see if the month has 30/31 days in it
	    	if ($month == "04" or 
	    		$month == "06" or 
	    		$month == "09" or 
	    		$month == "11")
	    		$month_in_seconds = 2592000; // 30 day month
	    	else $month_in_seconds = 2678400; // 31 day month;
	    }
	  
	    $in_seconds = strtotime(substr($posted_date,0,8).' '.
	                  substr($posted_date,8,2).':'.
	                  substr($posted_date,10,2).':'.
	                  substr($posted_date,12,2));
	    $diff = time() - ($in_seconds + ($tz*3600));
	    $months = floor($diff/$month_in_seconds);
	    $diff -= $months*2419200;
	    $weeks = floor($diff/604800);
	    $diff -= $weeks*604800;
	    $days = floor($diff/86400);
	    $diff -= $days*86400;
	    $hours = floor($diff/3600);
	    $diff -= $hours*3600;
	    $minutes = floor($diff/60);
	    $diff -= $minutes*60;
	    $seconds = $diff;
	
	    $relative_date = '';
	    if ($months>0) {
	        // over a month old, just show date ("Month, Day Year")
	        if(!empty($input_date)){
	        	return date('F jS, Y',strtotime($input_date));
	        }else{
	        	return 'N/A';
	        }
	    } else {
	        if ($weeks>0) {
	            // weeks and days
	            $relative_date .= ($relative_date?', ':'').$weeks.' week'.($weeks>1?'s':'');
	            $relative_date .= $days>0?($relative_date?', ':'').$days.' day'.($days>1?'s':''):'';
	        } elseif ($days>0) {
	            // days and hours
	            $relative_date .= ($relative_date?', ':'').$days.' day'.($days>1?'s':'');
	            $relative_date .= $hours>0?($relative_date?', ':'').$hours.' hour'.($hours>1?'s':''):'';
	        } elseif ($hours>0) {
	            // hours and minutes
	            $relative_date .= ($relative_date?', ':'').$hours.' hour'.($hours>1?'s':'');
	            $relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':''):'';
	        } elseif ($minutes>0) {
	            // minutes only
	            $relative_date .= ($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':'');
	        } else {
	            // seconds only
	            $relative_date .= ($relative_date?', ':'').$seconds.' second'.($seconds>1?'s':'');
	        }
	        
	        // show relative date and add proper verbiage
	    	return $relative_date.' ago';
	    }
	    
	}
	
	//this function accept 'YYYY-MM-DD HH:MM:SS'
	function short_relative_date($input_date) {
	    
	    $tz = 0;    // change this if your web server and weblog are in different timezones
	           			        
	    $posted_date = str_replace(array('-',' ',':'),'',$input_date);            
	    $month = substr($posted_date,4,2);
	    $year  = substr($posted_date,0,4);
	    
	    if ($month == "02") { // february
	    	// check for leap year
	    	$leapYear = isLeapYear($year);
	    	if ($leapYear) $month_in_seconds = 2505600; // leap year
	    	else $month_in_seconds = 2419200;
	    }
	    else { // not february
	    // check to see if the month has 30/31 days in it
	    	if ($month == "04" or 
	    		$month == "06" or 
	    		$month == "09" or 
	    		$month == "11")
	    		$month_in_seconds = 2592000; // 30 day month
	    	else $month_in_seconds = 2678400; // 31 day month;
	    }
	  
	    $in_seconds = strtotime(substr($posted_date,0,8).' '.
	                  substr($posted_date,8,2).':'.
	                  substr($posted_date,10,2).':'.
	                  substr($posted_date,12,2));
	    $diff = time() - ($in_seconds + ($tz*3600));
	    $months = floor($diff/$month_in_seconds);
	    $diff -= $months*2419200;
	    $weeks = floor($diff/604800);
	    $diff -= $weeks*604800;
	    $days = floor($diff/86400);
	    $diff -= $days*86400;
	    $hours = floor($diff/3600);
	    $diff -= $hours*3600;
	    $minutes = floor($diff/60);
	    $diff -= $minutes*60;
	    $seconds = $diff;
	
	    $relative_date = '';
	    if ($months>0) {
	    	
	        // over a month old
	        if(!empty($input_date)){
	        	if($year < date('Y')){ //over a year, show international date
	        		return date('Y-m-d',strtotime($input_date));
	        	}else{ //less than a year
	        		return date('M j',strtotime($input_date));
	        	}
	        	
	        }else{
	        	return '';
	        }
	    } else {
	        if ($weeks>0) {
	            // weeks and days
	            $relative_date .= ($relative_date?', ':'').$weeks.' week'.($weeks>1?'s':'');
	            //$relative_date .= $days>0?($relative_date?', ':'').$days.' day'.($days>1?'s':''):'';
	        } elseif ($days>0) {
	            // days and hours
	            $relative_date .= ($relative_date?', ':'').$days.' day'.($days>1?'s':'');
	            //$relative_date .= $hours>0?($relative_date?', ':'').$hours.' hour'.($hours>1?'s':''):'';
	        } elseif ($hours>0) {
	            // hours and minutes
	            $relative_date .= ($relative_date?', ':'').$hours.' hour'.($hours>1?'s':'');
	            //$relative_date .= $minutes>0?($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':''):'';
	        } elseif ($minutes>0) {
	            // minutes only
	            $relative_date .= ($relative_date?', ':'').$minutes.' minute'.($minutes>1?'s':'');
	        } else {
	            // seconds only
	            $relative_date .= ($relative_date?', ':'').$seconds.' second'.($seconds>1?'s':'');
	        }
	        
	        // show relative date and add proper verbiage
	    	return $relative_date.' ago';
	    }
	    
	}
	
	function isLeapYear($year) {
	        return $year % 4 == 0 && ($year % 400 == 0 || $year % 100 != 0);
	}
	
	//remove a folder and all it's content
	function full_rmdir($dirname){
        if ($dirHandle = opendir($dirname)){
            $old_cwd = getcwd();
            chdir($dirname);

            while ($file = readdir($dirHandle)){
                if ($file == '.' || $file == '..') continue;

                if (is_dir($file)){
                    if (!full_rmdir($file)) return false;
                }else{
                    if (!unlink($file)) return false;
                }
            }

            closedir($dirHandle);
            chdir($old_cwd);
            if (!rmdir($dirname)) return false;

            return true;
        }else{
            return false;
        }
    }

    //show success or error messages
    function show_message(){
    	
    	if(!empty($_SESSION['AP_ERROR'])){
    		if(!empty($_SESSION['AP_ERROR']['desc'])){
    			$error_desc = '<p id="error_message_desc"><b>'.$_SESSION['AP_ERROR']['desc'].'</b></p>';
    		}else{
    			$error_desc = '';
    		}
    		
    		$ul_markup =<<<EOT
			<ul class="global_message">
				<li id="error_message">
					<h3 id="error_message_title">{$_SESSION['AP_ERROR']['title']} &nbsp;<img src="images/icons/stop.gif" align="top" style="padding-top: 2px"/></h3>
					{$error_desc}
				</li>	
			</ul>
EOT;

    		echo $ul_markup;
    		
    		$_SESSION['AP_ERROR'] = array();
    		unset($_SESSION['AP_ERROR']);
    	}
    	
    	if(!empty($_SESSION['AP_SUCCESS'])){
    		if(!empty($_SESSION['AP_SUCCESS']['desc'])){
    			$success_desc = '<p id="success_message_desc"><b>'.$_SESSION['AP_SUCCESS']['desc'].'</b></p>';
    		}else{
    			$success_desc = '';
    		}
    		
    		$ul_markup =<<<EOT
			<ul class="global_message">
				<li id="success_message">
					<h3 id="success_message_title"><span>{$_SESSION['AP_SUCCESS']['title']}&nbsp;<img src="images/icons/checkbox.gif" align="absmiddle" /></span></h3>
					{$success_desc}
				</li>	
			</ul>
EOT;

    		echo $ul_markup;
    		
			$_SESSION['AP_SUCCESS'] = array();
    		unset($_SESSION['AP_SUCCESS']);
		}
	    	
    }
    
    //send notification email
    //$to_emails is a comma separated list of email address or {element_x} field
    function send_notification($form_id,$entry_id,$to_emails,$email_param){
    	    	
    	$from_name  = $email_param['from_name'];
    	$from_email = $email_param['from_email'];
    	$subject 	= $email_param['subject'];
    	$content 	= nl2br($email_param['content']);
    	$as_plain_text 		= $email_param['as_plain_text']; //if set to 'true' the email content will be a simple plain text
    	$target_is_admin 	= $email_param['target_is_admin']; //if set to 'false', the download link for uploaded file will be removed
    	
    	    	
    	//get data for the particular entry id
    	if($target_is_admin === false){
    		$options['strip_download_link'] = true;
    	}
    	
    	$options['strip_checkbox_image'] = true;
		$entry_details = get_entry_details($form_id,$entry_id,$options);
    	
    	//populate field values to template variables
    	$i=0;
    	foreach ($entry_details as $data){
    		$template_variables[$i] = '{element_'.$data['element_id'].'}';
    		$template_values[$i]	= $data['value'];
    		
    		if($data['element_type'] == 'textarea'){
				$template_values[$i] = nl2br($data['value']);
			}elseif ($data['element_type'] == 'file'){
				if($target_is_admin === false){
					$template_values[$i] = strip_tags($data['value']);
				}else{
					$template_values[$i] = strip_tags($data['value'],'<a>');
				}
			}else{
				$template_values[$i]	= $data['value'];
			}
    		    		
    		$i++;
    	}
    	
    	//get entry timestamp
		$query = "select date_created,ip_address from `ap_form_{$form_id}` where id='$entry_id'";
		$result = do_query($query);
		$row = do_fetch_result($result);
		
		$date_created = $row['date_created'];
		$ip_address   = $row['ip_address'];
    	    	
    	//get form name
		$query 	= "select form_name	from `ap_forms` where form_id='$form_id'";
		$result = do_query($query);
		$row 	= do_fetch_result($result);
		$form_name  = $row['form_name'];
    	
    	
		$template_variables[$i] = '{date_created}';
		$template_values[$i]	= $date_created;
		$i++;
		$template_variables[$i] = '{ip_address}';
		$template_values[$i]	= $ip_address;
		$i++;
		$template_variables[$i] = '{form_name}';
		$template_values[$i]	= $form_name;
		$i++;
		$template_variables[$i] = '{entry_no}';
		$template_values[$i]	= $entry_id;
		$i++;
		$template_variables[$i] = '{form_id}';
		$template_values[$i]	= $form_id;
		
		
		//compose {entry_data} based on 'as_plain_text' preferences
		$email_body = '';
    	if(!$as_plain_text){
			//compose html format
			$email_body = '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family:Lucida Grande,Tahoma,Arial,Verdana,sans-serif;font-size:12px;text-align:left">'."\n";
			
			$toggle = false;
			foreach ($entry_details as $data){
				//0 should be displayed, empty string don't
				if((empty($data['value']) || $data['value'] == '&nbsp;') && $data['value'] !== 0 && $data['value'] !== '0'){
					continue;
				}				
				
				if($toggle){
					$toggle = false;
					$row_style = 'style="background-color:#F3F7FB"';
				}else{
					$toggle = true;
					$row_style = '';
				}	
			
				if($data['element_type'] == 'textarea'){
					$data['value'] = nl2br($data['value']);
				}elseif ($data['element_type'] == 'file'){
					
					if($target_is_admin === false){
						$data['value'] = strip_tags($data['value']);
					}else{
						$data['value'] = strip_tags($data['value'],'<a>');
					}
				}
				
				$email_body .= "<tr {$row_style}>\n";
				$email_body .= '<td width="40%" style="border-bottom:1px solid #DEDEDE;padding:5px 10px;"><strong>'.$data['label'].'</strong> </td>'."\n";
				$email_body .= '<td width="60%" style="border-bottom:1px solid #DEDEDE;padding:5px 10px;">'.$data['value'].'</td>'."\n";
				$email_body .= '</tr>'."\n";	
					
				$i++;
			}
			$email_body .= "</table>\n";
		}else{
			
			//compose text format
			foreach ($entry_details as $data){
				
				//0 should be displayed, empty string don't
				if((empty($data['value']) || $data['value'] == '&nbsp;') && $data['value'] !== 0 && $data['value'] !== '0'){
					continue;
				}
								
				if($data['element_type'] == 'textarea'){
					$email_body .= "<b>{$data['label']}</b> <br />".nl2br($data['value'])."<br /><br />\n";
				}elseif ($data['element_type'] == 'checkbox' || $data['element_type'] == 'address'){
					$email_body .= "<b>{$data['label']}</b> <br />".$data['value']."<br /><br />\n";
				}elseif ($data['element_type'] == 'file'){
					if($target_is_admin === false){
						$data['value'] = strip_tags($data['value']);
						$email_body .= "<b>{$data['label']}</b> - {$data['value']} <br />\n";
					}else{
						$data['value'] = strip_tags($data['value'],'<a>');
						$email_body .= "<b>{$data['label']}</b> <br />".$data['value']."<br /><br />\n";
					}
				}else{
					$email_body .= "<b>{$data['label']}</b> - {$data['value']} <br />\n";
				}
				
				
			}
		}
		
		$i = count($template_variables);
		$template_variables[$i] = '{entry_data}';
		$template_values[$i]	= $email_body;
		
		$mail = new PHPMailer();
		$mail->CharSet  = 'UTF-8';
		$mail->Host     = "127.0.0.1";
		$mail->IsHTML(true);
		$mail->Mailer   = "mail"; 
		$mail->SMTPAuth = false;
		
		if(USE_SMTP === true){
			$mail->Mailer   = "smtp";
			$mail->Host		= SMTP_HOST;
			$mail->Port		= SMTP_PORT;
			 
			if(SMTP_AUTH === true){
				$mail->SMTPAuth = true; 
				$mail->Username = SMTP_USERNAME;
				$mail->Password = SMTP_PASSWORD;
			}
			
			if(SMTP_SECURE === true){
				$mail->SMTPSecure = 'tls';
			}
		}
		
		//parse from_name template
    	if(!empty($from_name)){
    		$from_name = str_replace($template_variables,$template_values,$from_name);
			$mail->FromName = str_replace('&nbsp;','',$from_name);
		}elseif (NOTIFICATION_MAIL_FROM_NAME != ''){
			$mail->FromName = utf8_encode(str_replace('&nbsp','',str_replace($template_variables,$template_values,NOTIFICATION_MAIL_FROM_NAME)));
		}else{
			$mail->FromName = 'MachForm';
		}
    	
		//decode any html entity
		$mail->FromName = html_entity_decode($mail->FromName,ENT_QUOTES);
		
    	//parse from_email_address template
    	if(!empty($from_email)){
    		$from_email = str_replace($template_variables,$template_values,$from_email);
			$mail->From = $from_email;
		}elseif(NOTIFICATION_MAIL_FROM != ''){
			$mail->From = utf8_encode(str_replace($template_variables,$template_values,NOTIFICATION_MAIL_FROM));
		}else{
			$domain = str_replace('www.','',$_SERVER['SERVER_NAME']);
			$mail->From = "no-reply@{$domain}";
		}
    	
    	//parse subject template
    	if(!empty($subject)){
    		$subject = str_replace($template_variables,$template_values,$subject);
			$mail->Subject = str_replace('&nbsp;','',$subject);
		}elseif (NOTIFICATION_MAIL_SUBJECT != ''){
			$mail->Subject = utf8_encode(str_replace('&nbsp;','',str_replace($template_variables,$template_values,NOTIFICATION_MAIL_SUBJECT)));
		}else{
			if($target_is_admin){
				$mail->Subject = utf8_encode("{$form_name} [#{$entry_id}]");
			}else{
				$mail->Subject = utf8_encode("{$form_name} - Receipt");
			}
		}
		//decode any html entity
		$mail->Subject = html_entity_decode($mail->Subject,ENT_QUOTES);
		
		
    	//parse content template
    	$email_content = str_replace($template_variables,$template_values,$content);
    	
    	//add footer
    	$email_content .= "<br /><br /><br /><br /><br /><b style=\"font-family:Lucida Grande,Tahoma,Arial,Verdana,sans-serif;font-size:12px\">Powered by <a href=\"http://www.nulledscriptz.com\">MachForm</a></b>";
    	
    	//enclose with container div
    	$email_content = '<div style="font-family:Lucida Grande,Tahoma,Arial,Verdana,sans-serif;font-size:12px">'.$email_content.'</div>';
    	
    	$mail->Body = $email_content;
    	
    	//send email
    	$to_emails 		= str_replace('&nbsp;','',str_replace($template_variables,$template_values,$to_emails));
    	$email_address 	= explode(',',$to_emails);
		
    	$has_email = false;    	
    	foreach ($email_address as $email){
			$email = trim($email);
			if(!empty($email)){
				$mail->AddAddress($email);
				$has_email = true;
			}
		}
		
		if($has_email){
			$send_status = $mail->Send();
	    	$mail->ClearAddresses();
	    	
	    	if($send_status !== true){
	    		echo "Error sending email: ".$mail->ErrorInfo;
	    	}
		}
    }
    
    function get_ssl_suffix(){
    	if(!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')){
			$ssl_suffix = 's';
		}else{
			$ssl_suffix = '';
		}
		
		return $ssl_suffix;
    }
    
    function get_dirname($path){
    	$current_dir = dirname($path);
    	
    	if($current_dir == "/" || $current_dir == "\\"){
			$current_dir = '';
		}
		
		return $current_dir;
    }
    
?>
