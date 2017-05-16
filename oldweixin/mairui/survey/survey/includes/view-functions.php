<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	//Single Line Text
	function display_text($element){
		
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		//check for populated value, if exist, use it instead default_value
		if(isset($element->populated_value['element_'.$element->id]['default_value'])){
			$element->default_value = $element->populated_value['element_'.$element->id]['default_value'];
		}
	
				
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<div>
			<input id="element_{$element->id}" name="element_{$element->id}" class="element text {$element->size}" type="text" value="{$element->default_value}" /> 
		</div>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	
	//Paragraph Text
	function display_textarea($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		//check for populated value, if exist, use it instead default_value
		if(isset($element->populated_value['element_'.$element->id]['default_value'])){
			$element->default_value = $element->populated_value['element_'.$element->id]['default_value'];
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<div>
			<textarea id="element_{$element->id}" name="element_{$element->id}" class="element textarea {$element->size}" rows="8" cols="90">{$element->default_value}</textarea> 
		</div>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	//File Upload
	function display_file($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}

		//check for populated value (this is being used for edit_entry.php only)
		if(!empty($element->populated_value)){
			$file_option = $element->populated_value['element_'.$element->id]['default_value']; //this should be contain html markup to download or delete current file
		}
				
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<div>
			<input id="element_{$element->id}" name="element_{$element->id}" class="element file" type="file" /> 
		</div>{$file_option} {$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	//Website
	function display_url($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		//check for default value
		if(empty($element->default_value)){
			$element->default_value = 'http://';
		}
		
		//check for populated value, if exist, use it instead default_value
		if(!empty($element->populated_value['element_'.$element->id]['default_value'])){
			$element->default_value = $element->populated_value['element_'.$element->id]['default_value'];
		}
			
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<div>
			<input id="element_{$element->id}" name="element_{$element->id}" class="element text {$element->size}" type="text"  value="{$element->default_value}" /> 
		</div>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	//Email
	function display_email($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		//check for populated value, if exist, use it instead default_value
		if(!empty($element->populated_value['element_'.$element->id]['default_value'])){
			$element->default_value = $element->populated_value['element_'.$element->id]['default_value'];
		}
					
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<div>
			<input id="element_{$element->id}" name="element_{$element->id}" class="element text {$element->size}" type="text" maxlength="255" value="{$element->default_value}" /> 
		</div>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	//Phone - Extended
	function display_phone($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		//check default value
		if(!empty($element->default_value)){
			//split into (xxx) xxx - xxxx
			$default_value_1 = substr($element->default_value,0,3);
			$default_value_2 = substr($element->default_value,3,3);
			$default_value_3 = substr($element->default_value,6,4);
		}
		
		if(!empty($element->populated_value['element_'.$element->id.'_1']['default_value']) || 
		   !empty($element->populated_value['element_'.$element->id.'_2']['default_value']) ||
		   !empty($element->populated_value['element_'.$element->id.'_3']['default_value'])
		){
			$default_value_1 = '';
			$default_value_2 = '';
			$default_value_3 = '';
			$default_value_1 = $element->populated_value['element_'.$element->id.'_1']['default_value'];
			$default_value_2 = $element->populated_value['element_'.$element->id.'_2']['default_value'];
			$default_value_3 = $element->populated_value['element_'.$element->id.'_3']['default_value'];
		}
		
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			<input id="element_{$element->id}_1" name="element_{$element->id}_1" class="element text" size="3" maxlength="3" value="{$default_value_1}" type="text" /> -
			<label for="element_{$element->id}_1">(###)</label>
		</span>
		<span>
			<input id="element_{$element->id}_2" name="element_{$element->id}_2" class="element text" size="3" maxlength="3" value="{$default_value_2}" type="text" /> -
			<label for="element_{$element->id}_2">###</label>
		</span>
		<span>
	 		<input id="element_{$element->id}_3" name="element_{$element->id}_3" class="element text" size="4" maxlength="4" value="{$default_value_3}" type="text" />
			<label for="element_{$element->id}_3">####</label>
		</span>
		{$guidelines} {$error_message}
		</li>
EOT;
		

		return $element_markup;
	}
	
	//Phone - Simple
	function display_simple_phone($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
				
		//check for populated value
		if(!empty($element->populated_value['element_'.$element->id]['default_value'])){
			$element->default_value = $element->populated_value['element_'.$element->id]['default_value'];
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<div>
			<input id="element_{$element->id}" name="element_{$element->id}" class="element text medium" type="text" maxlength="255" value="{$element->default_value}"/> 
		</div>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	
	//Date - Normal
	function display_date($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		$machform_path = '';
		if(!empty($element->machform_path)){
			$machform_path = $element->machform_path;
		}
			
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			<input id="element_{$element->id}_1" name="element_{$element->id}_1" class="element text" size="2" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_1']['default_value']}" type="text" /> /
			<label for="element_{$element->id}_1">{$lang['date_mm']}</label>
		</span>
		<span>
			<input id="element_{$element->id}_2" name="element_{$element->id}_2" class="element text" size="2" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_2']['default_value']}" type="text" /> /
			<label for="element_{$element->id}_2">{$lang['date_dd']}</label>
		</span>
		<span>
	 		<input id="element_{$element->id}_3" name="element_{$element->id}_3" class="element text" size="4" maxlength="4" value="{$element->populated_value['element_'.$element->id.'_3']['default_value']}" type="text" />
			<label for="element_{$element->id}_3">{$lang['date_yyyy']}</label>
		</span>
	
		<span id="calendar_{$element->id}">
			<img id="cal_img_{$element->id}" class="datepicker" src="{$machform_path}images/calendar.gif" alt="Pick a date." />	
		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_{$element->id}_3",
			baseField    : "element_{$element->id}",
			displayArea  : "calendar_{$element->id}",
			button		 : "cal_img_{$element->id}",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		{$guidelines} {$error_message}
		</li>
EOT;
	
		return $element_markup;
	}
	
	//Date - Normal
	function display_europe_date($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		$machform_path = '';
		if(!empty($element->machform_path)){
			$machform_path = $element->machform_path;
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			<input id="element_{$element->id}_1" name="element_{$element->id}_1" class="element text" size="2" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_1']['default_value']}" type="text" /> /
			<label for="element_{$element->id}_1">{$lang['date_dd']}</label>
		</span>
		<span>
			<input id="element_{$element->id}_2" name="element_{$element->id}_2" class="element text" size="2" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_2']['default_value']}" type="text" /> /
			<label for="element_{$element->id}_2">{$lang['date_mm']}</label>
		</span>
		<span>
	 		<input id="element_{$element->id}_3" name="element_{$element->id}_3" class="element text" size="4" maxlength="4" value="{$element->populated_value['element_'.$element->id.'_3']['default_value']}" type="text" />
			<label for="element_{$element->id}_3">{$lang['date_yyyy']}</label>
		</span>
	
		<span id="calendar_{$element->id}">
			<img id="cal_img_{$element->id}" class="datepicker" src="{$machform_path}images/calendar.gif" alt="Pick a date." />	
		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_{$element->id}_3",
			baseField    : "element_{$element->id}",
			displayArea  : "calendar_{$element->id}",
			button		 : "cal_img_{$element->id}",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectEuropeDate
			});
		</script>
		{$guidelines} {$error_message}
		</li>
EOT;
	
		return $element_markup;
	}
	
	
	//Multiple Choice
	function display_radio($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		$option_markup = '';
		
		if($element->constraint == 'random'){
			$temp = $element->options;
			shuffle($temp);
			$element->options = $temp;
		}
		
		foreach ($element->options as $option){
			
			if($option->is_default){
				$checked = 'checked="checked"';
			}else{
				$checked = '';
			}
			
			//check for populated values
			if(!empty($element->populated_value['element_'.$element->id]['default_value'])){
				$checked = '';
				if($element->populated_value['element_'.$element->id]['default_value'] == $option->id){
					$checked = 'checked="checked"';
				}
			}
						
			$option_markup .= "<input id=\"element_{$element->id}_{$option->id}\" name=\"element_{$element->id}\" class=\"element radio\" type=\"radio\" value=\"{$option->id}\" {$checked} />\n";
			$option_markup .= "<label class=\"choice\" for=\"element_{$element->id}_{$option->id}\">{$option->option}</label>\n";
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			{$option_markup}
		</span>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	//Checkboxes
	function display_checkbox($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		//check for populated value first, if any exist, unselect all default value
		$is_populated = false;
		foreach ($element->options as $option){
			
			if(!empty($element->populated_value['element_'.$element->id.'_'.$option->id]['default_value'])){
				$is_populated = true;
				break;
			}
		}
	
		$option_markup = '';
		
		foreach ($element->options as $option){
			if(!$is_populated){
				if($option->is_default){
					$checked = 'checked="checked"';
				}else{
					$checked = '';
				}
			}else{
				
				if(!empty($element->populated_value['element_'.$element->id.'_'.$option->id]['default_value'])){
					$checked = 'checked="checked"';
				}else{
					$checked = '';	
				}
			}
			
			
			
			$option_markup .= "<input id=\"element_{$element->id}_{$option->id}\" name=\"element_{$element->id}_{$option->id}\" class=\"element checkbox\" type=\"checkbox\" value=\"1\" {$checked} />\n";
			$option_markup .= "<label class=\"choice\" for=\"element_{$element->id}_{$option->id}\">{$option->option}</label>\n";
		}
		
$element_markup = <<<EOT
		<li  id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			{$option_markup}
		</span>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}

	
	//Dropdown
	function display_select($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		$option_markup = '';
		
		$has_default = false;
		foreach ($element->options as $option){
			
			if($option->is_default){
				$selected = 'selected="selected"';
				$has_default = true;
			}else{
				$selected = '';
			}
			
			if(!empty($element->populated_value['element_'.$element->id]['default_value'])){
				$selected = '';
				if($element->populated_value['element_'.$element->id]['default_value'] == $option->id){
					$selected = 'selected="selected"';
				}
			}
			
			$option_markup .= "<option value=\"{$option->id}\" {$selected}>{$option->option}</option>\n";
		}
		
		if(!$has_default){
			if(!empty($element->populated_value['element_'.$element->id]['default_value'])){
				$option_markup = '<option value=""></option>'."\n".$option_markup;
			}else{
				$option_markup = '<option value="" selected="selected"></option>'."\n".$option_markup;
			}
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<div>
		<select class="element select {$element->size}" id="element_{$element->id}" name="element_{$element->id}"> 
			{$option_markup}
		</select>
		</div>{$guidelines} {$error_message}
		</li>
EOT;

		return $element_markup;
	}
	
	
	//Name - Simple
	function display_simple_name($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			<input id="element_{$element->id}_1" name= "element_{$element->id}_1" class="element text" maxlength="255" size="8" value="{$element->populated_value['element_'.$element->id.'_1']['default_value']}" />
			<label>{$lang['name_first']}</label>
		</span>
		<span>
			<input id="element_{$element->id}_2" name= "element_{$element->id}_2" class="element text" maxlength="255" size="14" value="{$element->populated_value['element_'.$element->id.'_2']['default_value']}" />
			<label>{$lang['name_last']}</label>
		</span>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}

	//Name 
	function display_name($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			<input id="element_{$element->id}_1" name="element_{$element->id}_1" class="element text" maxlength="255" size="2" value="{$element->populated_value['element_'.$element->id.'_1']['default_value']}" />
			<label>{$lang['name_title']}</label>
		</span>
		<span>
			<input id="element_{$element->id}_2" name="element_{$element->id}_2" class="element text" maxlength="255" size="8" value="{$element->populated_value['element_'.$element->id.'_2']['default_value']}" />
			<label class="tam">{$lang['name_first']}</label>
		</span>
		<span>
			<input id="element_{$element->id}_3" name="element_{$element->id}_3" class="element text" maxlength="255" size="14" value="{$element->populated_value['element_'.$element->id.'_3']['default_value']}" />
			<label class="tam">{$lang['name_last']}</label>
		</span>
		<span>
			<input id="element_{$element->id}_4" name="element_{$element->id}_4" class="element text" maxlength="255" size="3" value="{$element->populated_value['element_'.$element->id.'_4']['default_value']}" />
			<label>{$lang['name_suffix']}</label>
		</span>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	//Time
	function display_time($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		if(!empty($element->populated_value['element_'.$element->id.'_4']['default_value'])){
			if($element->populated_value['element_'.$element->id.'_4']['default_value'] == 'AM'){
				$selected_am = 'selected';
			}else{
				$selected_pm = 'selected';
			}
		}
		
		if($element->constraint == 'show_seconds'){
			$seconds_markup =<<<EOT
		<span>
			<input id="element_{$element->id}_3" name="element_{$element->id}_3" class="element text " size="2" type="text" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_3']['default_value']}" />
			<label>{$lang['time_ss']}</label>
		</span>
EOT;
			$seconds_separator = ':';
		}else{
			$seconds_markup = '';
			$seconds_separator = '';
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span>
			<input id="element_{$element->id}_1" name="element_{$element->id}_1" class="element text " size="2" type="text" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_1']['default_value']}" /> : 
			<label>{$lang['time_hh']}</label>
		</span>
		<span>
			<input id="element_{$element->id}_2" name="element_{$element->id}_2" class="element text " size="2" type="text" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_2']['default_value']}" /> {$seconds_separator} 
			<label>{$lang['time_mm']}</label>
		</span>
		{$seconds_markup}
		<span>
			<select class="element select" style="width:4em" id="element_{$element->id}_4" name="element_{$element->id}_4">
				<option value="AM" {$selected_am}>AM</option>
				<option value="PM" {$selected_pm}>PM</option>
			</select>
			<label>AM/PM</label>
		</span>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	//Price
	function display_money($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		if($element->constraint != 'yen'){ //for dollar, pound and euro
			if($element->constraint == 'pound'){
				$main_cur  = $lang['price_pound_main'];
				$child_cur = $lang['price_pound_sub'];
				$cur_symbol = '&#163;';
			}elseif ($element->constraint == 'euro'){
				$main_cur  = $lang['price_euro_main'];
				$child_cur = $lang['price_euro_sub'];
				$cur_symbol = '&#8364;';
			}else{ //dollar
				$main_cur  = $lang['price_dollar_main'];
				$child_cur = $lang['price_dollar_sub'];
				$cur_symbol = '$';
			}	
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		<span class="symbol">{$cur_symbol}</span>
		<span>
			<input id="element_{$element->id}_1" name="element_{$element->id}_1" class="element text currency" size="10" value="{$element->populated_value['element_'.$element->id.'_1']['default_value']}" type="text" /> .		
			<label for="element_{$element->id}_1">{$main_cur}</label>
		</span>
		<span>
			<input id="element_{$element->id}_2" name="element_{$element->id}_2" class="element text" size="2" maxlength="2" value="{$element->populated_value['element_'.$element->id.'_2']['default_value']}" type="text" />
			<label for="element_{$element->id}_2">{$child_cur}</label>
		</span>
		{$guidelines} {$error_message}
		</li>
EOT;

		}else{ //for yen, only display one textfield
			$main_cur  = $lang['price_yen'];
			$cur_symbol = '&#165;';
			
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<span class="symbol">{$cur_symbol}</span>
		<span>
			<input id="element_{$element->id}" name="element_{$element->id}" class="element text currency" size="10" value="{$element->populated_value['element_'.$element->id]['default_value']}" type="text" />	
			<label for="element_{$element->id}">{$main_cur}</label>
		</span>
		{$guidelines} {$error_message}
		</li>
EOT;
		
		}



		return $element_markup;
	}
	
	//Section Break
	function display_section($element){
				
$element_markup = <<<EOT
		<li id="li_{$element->id}" class="section_break">
			<h3>{$element->title}</h3>
			<p>{$element->guidelines}</p>
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	
	//Number
	function display_number($element){
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
				
		//check for populated value, if exist, use it instead default_value
		if(isset($element->populated_value['element_'.$element->id]['default_value'])){
			$element->default_value = $element->populated_value['element_'.$element->id]['default_value'];
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description" for="element_{$element->id}">{$element->title} {$span_required}</label>
		<div>
			<input id="element_{$element->id}" name="element_{$element->id}" class="element text {$element->size}" type="text" maxlength="255" value="{$element->default_value}" /> 
		</div>{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	
	//Address
	function display_address($element){
		
		$country[0]['label'] = "Afghanistan";
		$country[1]['label'] = "Albania";
		$country[2]['label'] = "Algeria";
		$country[3]['label'] = "Andorra";
		$country[4]['label'] = "Antigua and Barbuda";
		$country[5]['label'] = "Argentina";
		$country[6]['label'] = "Armenia";
		$country[7]['label'] = "Australia";
		$country[8]['label'] = "Austria";
		$country[9]['label'] = "Azerbaijan";
		$country[10]['label'] = "Bahamas";
		$country[11]['label'] = "Bahrain";
		$country[12]['label'] = "Bangladesh";
		$country[13]['label'] = "Barbados";
		$country[14]['label'] = "Belarus";
		$country[15]['label'] = "Belgium";
		$country[16]['label'] = "Belize";
		$country[17]['label'] = "Benin";
		$country[18]['label'] = "Bhutan";
		$country[19]['label'] = "Bolivia";
		$country[20]['label'] = "Bosnia and Herzegovina";
		$country[21]['label'] = "Botswana";
		$country[22]['label'] = "Brazil";
		$country[23]['label'] = "Brunei";
		$country[24]['label'] = "Bulgaria";
		$country[25]['label'] = "Burkina Faso";
		$country[26]['label'] = "Burundi";
		$country[27]['label'] = "Cambodia";
		$country[28]['label'] = "Cameroon";
		$country[29]['label'] = "Canada";
		$country[30]['label'] = "Cape Verde";
		$country[31]['label'] = "Central African Republic";
		$country[32]['label'] = "Chad";
		$country[33]['label'] = "Chile";
		$country[34]['label'] = "China";
		$country[35]['label'] = "Colombia";
		$country[36]['label'] = "Comoros";
		$country[37]['label'] = "Congo";
		$country[38]['label'] = "Costa Rica";
		$country[39]['label'] = "Côte d'Ivoire";
		$country[40]['label'] = "Croatia";
		$country[41]['label'] = "Cuba";
		$country[42]['label'] = "Cyprus";
		$country[43]['label'] = "Czech Republic";
		$country[44]['label'] = "Denmark";
		$country[45]['label'] = "Djibouti";
		$country[46]['label'] = "Dominica";
		$country[47]['label'] = "Dominican Republic";
		$country[48]['label'] = "East Timor";
		$country[49]['label'] = "Ecuador";
		$country[50]['label'] = "Egypt";
		$country[51]['label'] = "El Salvador";
		$country[52]['label'] = "Equatorial Guinea";
		$country[53]['label'] = "Eritrea";
		$country[54]['label'] = "Estonia";
		$country[55]['label'] = "Ethiopia";
		$country[56]['label'] = "Fiji";
		$country[57]['label'] = "Finland";
		$country[58]['label'] = "France";
		$country[59]['label'] = "Gabon";
		$country[60]['label'] = "Gambia";
		$country[61]['label'] = "Georgia";
		$country[62]['label'] = "Germany";
		$country[63]['label'] = "Ghana";
		$country[64]['label'] = "Greece";
		$country[65]['label'] = "Grenada";
		$country[66]['label'] = "Guatemala";
		$country[67]['label'] = "Guinea";
		$country[68]['label'] = "Guinea-Bissau";
		$country[69]['label'] = "Guyana";
		$country[70]['label'] = "Haiti";
		$country[71]['label'] = "Honduras";
		$country[72]['label'] = "Hong Kong";
		$country[73]['label'] = "Hungary";
		$country[74]['label'] = "Iceland";
		$country[75]['label'] = "India";
		$country[76]['label'] = "Indonesia";
		$country[77]['label'] = "Iran";
		$country[78]['label'] = "Iraq";
		$country[79]['label'] = "Ireland";
		$country[80]['label'] = "Israel";
		$country[81]['label'] = "Italy";
		$country[82]['label'] = "Jamaica";
		$country[83]['label'] = "Japan";
		$country[84]['label'] = "Jordan";
		$country[85]['label'] = "Kazakhstan";
		$country[86]['label'] = "Kenya";
		$country[87]['label'] = "Kiribati";
		$country[88]['label'] = "North Korea";
		$country[89]['label'] = "South Korea";
		$country[90]['label'] = "Kuwait";
		$country[91]['label'] = "Kyrgyzstan";
		$country[92]['label'] = "Laos";
		$country[93]['label'] = "Latvia";
		$country[94]['label'] = "Lebanon";
		$country[95]['label'] = "Lesotho";
		$country[96]['label'] = "Liberia";
		$country[97]['label'] = "Libya";
		$country[98]['label'] = "Liechtenstein";
		$country[99]['label'] = "Lithuania";
		$country[100]['label'] = "Luxembourg";
		$country[101]['label'] = "Macedonia";
		$country[102]['label'] = "Madagascar";
		$country[103]['label'] = "Malawi";
		$country[104]['label'] = "Malaysia";
		$country[105]['label'] = "Maldives";
		$country[106]['label'] = "Mali";
		$country[107]['label'] = "Malta";
		$country[108]['label'] = "Marshall Islands";
		$country[109]['label'] = "Mauritania";
		$country[110]['label'] = "Mauritius";
		$country[111]['label'] = "Mexico";
		$country[112]['label'] = "Micronesia";
		$country[113]['label'] = "Moldova";
		$country[114]['label'] = "Monaco";
		$country[115]['label'] = "Mongolia";
		$country[116]['label'] = "Montenegro";
		$country[117]['label'] = "Morocco";
		$country[118]['label'] = "Mozambique";
		$country[119]['label'] = "Myanmar";
		$country[120]['label'] = "Namibia";
		$country[121]['label'] = "Nauru";
		$country[122]['label'] = "Nepal";
		$country[123]['label'] = "Netherlands";
		$country[124]['label'] = "New Zealand";
		$country[125]['label'] = "Nicaragua";
		$country[126]['label'] = "Niger";
		$country[127]['label'] = "Nigeria";
		$country[128]['label'] = "Norway";
		$country[129]['label'] = "Oman";
		$country[130]['label'] = "Pakistan";
		$country[131]['label'] = "Palau";
		$country[132]['label'] = "Panama";
		$country[133]['label'] = "Papua New Guinea";
		$country[134]['label'] = "Paraguay";
		$country[135]['label'] = "Peru";
		$country[136]['label'] = "Philippines";
		$country[137]['label'] = "Poland";
		$country[138]['label'] = "Portugal";
		$country[139]['label'] = "Puerto Rico";
		$country[140]['label'] = "Qatar";
		$country[141]['label'] = "Romania";
		$country[142]['label'] = "Russia";
		$country[143]['label'] = "Rwanda";
		$country[144]['label'] = "Saint Kitts and Nevis";
		$country[145]['label'] = "Saint Lucia";
		$country[146]['label'] = "Saint Vincent and the Grenadines";
		$country[147]['label'] = "Samoa";
		$country[148]['label'] = "San Marino";
		$country[149]['label'] = "Sao Tome and Principe";
		$country[150]['label'] = "Saudi Arabia";
		$country[151]['label'] = "Senegal";
		$country[152]['label'] = "Serbia and Montenegro";
		$country[153]['label'] = "Seychelles";
		$country[154]['label'] = "Sierra Leone";
		$country[155]['label'] = "Singapore";
		$country[156]['label'] = "Slovakia";
		$country[157]['label'] = "Slovenia";
		$country[158]['label'] = "Solomon Islands";
		$country[159]['label'] = "Somalia";
		$country[160]['label'] = "South Africa";
		$country[161]['label'] = "Spain";
		$country[162]['label'] = "Sri Lanka";
		$country[163]['label'] = "Sudan";
		$country[164]['label'] = "Suriname";
		$country[165]['label'] = "Swaziland";
		$country[166]['label'] = "Sweden";
		$country[167]['label'] = "Switzerland";
		$country[168]['label'] = "Syria";
		$country[169]['label'] = "Taiwan";
		$country[170]['label'] = "Tajikistan";
		$country[171]['label'] = "Tanzania";
		$country[172]['label'] = "Thailand";
		$country[173]['label'] = "Togo";
		$country[174]['label'] = "Tonga";
		$country[175]['label'] = "Trinidad and Tobago";
		$country[176]['label'] = "Tunisia";
		$country[177]['label'] = "Turkey";
		$country[178]['label'] = "Turkmenistan";
		$country[179]['label'] = "Tuvalu";
		$country[180]['label'] = "Uganda";
		$country[181]['label'] = "Ukraine";
		$country[182]['label'] = "United Arab Emirates";
		$country[183]['label'] = "United Kingdom";
		$country[184]['label'] = "United States";
		$country[185]['label'] = "Uruguay";
		$country[186]['label'] = "Uzbekistan";
		$country[187]['label'] = "Vanuatu";
		$country[188]['label'] = "Vatican City";
		$country[189]['label'] = "Venezuela";
		$country[190]['label'] = "Vietnam";
		$country[191]['label'] = "Yemen";
		$country[192]['label'] = "Zambia";
		$country[193]['label'] = "Zimbabwe";
		
		
		$country[0]['value'] = "Afghanistan";
		$country[1]['value'] = "Albania";
		$country[2]['value'] = "Algeria";
		$country[3]['value'] = "Andorra";
		$country[4]['value'] = "Antigua and Barbuda";
		$country[5]['value'] = "Argentina";
		$country[6]['value'] = "Armenia";
		$country[7]['value'] = "Australia";
		$country[8]['value'] = "Austria";
		$country[9]['value'] = "Azerbaijan";
		$country[10]['value'] = "Bahamas";
		$country[11]['value'] = "Bahrain";
		$country[12]['value'] = "Bangladesh";
		$country[13]['value'] = "Barbados";
		$country[14]['value'] = "Belarus";
		$country[15]['value'] = "Belgium";
		$country[16]['value'] = "Belize";
		$country[17]['value'] = "Benin";
		$country[18]['value'] = "Bhutan";
		$country[19]['value'] = "Bolivia";
		$country[20]['value'] = "Bosnia and Herzegovina";
		$country[21]['value'] = "Botswana";
		$country[22]['value'] = "Brazil";
		$country[23]['value'] = "Brunei";
		$country[24]['value'] = "Bulgaria";
		$country[25]['value'] = "Burkina Faso";
		$country[26]['value'] = "Burundi";
		$country[27]['value'] = "Cambodia";
		$country[28]['value'] = "Cameroon";
		$country[29]['value'] = "Canada";
		$country[30]['value'] = "Cape Verde";
		$country[31]['value'] = "Central African Republic";
		$country[32]['value'] = "Chad";
		$country[33]['value'] = "Chile";
		$country[34]['value'] = "China";
		$country[35]['value'] = "Colombia";
		$country[36]['value'] = "Comoros";
		$country[37]['value'] = "Congo";
		$country[38]['value'] = "Costa Rica";
		$country[39]['value'] = "Côte d'Ivoire";
		$country[40]['value'] = "Croatia";
		$country[41]['value'] = "Cuba";
		$country[42]['value'] = "Cyprus";
		$country[43]['value'] = "Czech Republic";
		$country[44]['value'] = "Denmark";
		$country[45]['value'] = "Djibouti";
		$country[46]['value'] = "Dominica";
		$country[47]['value'] = "Dominican Republic";
		$country[48]['value'] = "East Timor";
		$country[49]['value'] = "Ecuador";
		$country[50]['value'] = "Egypt";
		$country[51]['value'] = "El Salvador";
		$country[52]['value'] = "Equatorial Guinea";
		$country[53]['value'] = "Eritrea";
		$country[54]['value'] = "Estonia";
		$country[55]['value'] = "Ethiopia";
		$country[56]['value'] = "Fiji";
		$country[57]['value'] = "Finland";
		$country[58]['value'] = "France";
		$country[59]['value'] = "Gabon";
		$country[60]['value'] = "Gambia";
		$country[61]['value'] = "Georgia";
		$country[62]['value'] = "Germany";
		$country[63]['value'] = "Ghana";
		$country[64]['value'] = "Greece";
		$country[65]['value'] = "Grenada";
		$country[66]['value'] = "Guatemala";
		$country[67]['value'] = "Guinea";
		$country[68]['value'] = "Guinea-Bissau";
		$country[69]['value'] = "Guyana";
		$country[70]['value'] = "Haiti";
		$country[71]['value'] = "Honduras";
		$country[72]['value'] = "Hong Kong";
		$country[73]['value'] = "Hungary";
		$country[74]['value'] = "Iceland";
		$country[75]['value'] = "India";
		$country[76]['value'] = "Indonesia";
		$country[77]['value'] = "Iran";
		$country[78]['value'] = "Iraq";
		$country[79]['value'] = "Ireland";
		$country[80]['value'] = "Israel";
		$country[81]['value'] = "Italy";
		$country[82]['value'] = "Jamaica";
		$country[83]['value'] = "Japan";
		$country[84]['value'] = "Jordan";
		$country[85]['value'] = "Kazakhstan";
		$country[86]['value'] = "Kenya";
		$country[87]['value'] = "Kiribati";
		$country[88]['value'] = "North Korea";
		$country[89]['value'] = "South Korea";
		$country[90]['value'] = "Kuwait";
		$country[91]['value'] = "Kyrgyzstan";
		$country[92]['value'] = "Laos";
		$country[93]['value'] = "Latvia";
		$country[94]['value'] = "Lebanon";
		$country[95]['value'] = "Lesotho";
		$country[96]['value'] = "Liberia";
		$country[97]['value'] = "Libya";
		$country[98]['value'] = "Liechtenstein";
		$country[99]['value'] = "Lithuania";
		$country[100]['value'] = "Luxembourg";
		$country[101]['value'] = "Macedonia";
		$country[102]['value'] = "Madagascar";
		$country[103]['value'] = "Malawi";
		$country[104]['value'] = "Malaysia";
		$country[105]['value'] = "Maldives";
		$country[106]['value'] = "Mali";
		$country[107]['value'] = "Malta";
		$country[108]['value'] = "Marshall Islands";
		$country[109]['value'] = "Mauritania";
		$country[110]['value'] = "Mauritius";
		$country[111]['value'] = "Mexico";
		$country[112]['value'] = "Micronesia";
		$country[113]['value'] = "Moldova";
		$country[114]['value'] = "Monaco";
		$country[115]['value'] = "Mongolia";
		$country[116]['value'] = "Montenegro";
		$country[117]['value'] = "Morocco";
		$country[118]['value'] = "Mozambique";
		$country[119]['value'] = "Myanmar";
		$country[120]['value'] = "Namibia";
		$country[121]['value'] = "Nauru";
		$country[122]['value'] = "Nepal";
		$country[123]['value'] = "Netherlands";
		$country[124]['value'] = "New Zealand";
		$country[125]['value'] = "Nicaragua";
		$country[126]['value'] = "Niger";
		$country[127]['value'] = "Nigeria";
		$country[128]['value'] = "Norway";
		$country[129]['value'] = "Oman";
		$country[130]['value'] = "Pakistan";
		$country[131]['value'] = "Palau";
		$country[132]['value'] = "Panama";
		$country[133]['value'] = "Papua New Guinea";
		$country[134]['value'] = "Paraguay";
		$country[135]['value'] = "Peru";
		$country[136]['value'] = "Philippines";
		$country[137]['value'] = "Poland";
		$country[138]['value'] = "Portugal";
		$country[139]['value'] = "Puerto Rico";
		$country[140]['value'] = "Qatar";
		$country[141]['value'] = "Romania";
		$country[142]['value'] = "Russia";
		$country[143]['value'] = "Rwanda";
		$country[144]['value'] = "Saint Kitts and Nevis";
		$country[145]['value'] = "Saint Lucia";
		$country[146]['value'] = "Saint Vincent and the Grenadines";
		$country[147]['value'] = "Samoa";
		$country[148]['value'] = "San Marino";
		$country[149]['value'] = "Sao Tome and Principe";
		$country[150]['value'] = "Saudi Arabia";
		$country[151]['value'] = "Senegal";
		$country[152]['value'] = "Serbia and Montenegro";
		$country[153]['value'] = "Seychelles";
		$country[154]['value'] = "Sierra Leone";
		$country[155]['value'] = "Singapore";
		$country[156]['value'] = "Slovakia";
		$country[157]['value'] = "Slovenia";
		$country[158]['value'] = "Solomon Islands";
		$country[159]['value'] = "Somalia";
		$country[160]['value'] = "South Africa";
		$country[161]['value'] = "Spain";
		$country[162]['value'] = "Sri Lanka";
		$country[163]['value'] = "Sudan";
		$country[164]['value'] = "Suriname";
		$country[165]['value'] = "Swaziland";
		$country[166]['value'] = "Sweden";
		$country[167]['value'] = "Switzerland";
		$country[168]['value'] = "Syria";
		$country[169]['value'] = "Taiwan";
		$country[170]['value'] = "Tajikistan";
		$country[171]['value'] = "Tanzania";
		$country[172]['value'] = "Thailand";
		$country[173]['value'] = "Togo";
		$country[174]['value'] = "Tonga";
		$country[175]['value'] = "Trinidad and Tobago";
		$country[176]['value'] = "Tunisia";
		$country[177]['value'] = "Turkey";
		$country[178]['value'] = "Turkmenistan";
		$country[179]['value'] = "Tuvalu";
		$country[180]['value'] = "Uganda";
		$country[181]['value'] = "Ukraine";
		$country[182]['value'] = "United Arab Emirates";
		$country[183]['value'] = "United Kingdom";
		$country[184]['value'] = "United States";
		$country[185]['value'] = "Uruguay";
		$country[186]['value'] = "Uzbekistan";
		$country[187]['value'] = "Vanuatu";
		$country[188]['value'] = "Vatican City";
		$country[189]['value'] = "Venezuela";
		$country[190]['value'] = "Vietnam";
		$country[191]['value'] = "Yemen";
		$country[192]['value'] = "Zambia";
		$country[193]['value'] = "Zimbabwe";
		
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;
		
		if(!empty($element->is_error)){
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}
		
		//check for required
		if($element->is_required){
			$span_required = "<span id=\"required_{$element->id}\" class=\"required\">*</span>";
		}
		
		//check for guidelines
		if(!empty($element->guidelines)){
			$guidelines = "<p class=\"guidelines\" id=\"guide_{$element->id}\"><small>{$element->guidelines}</small></p>";
		}
		
		
		//create country markup, if no default value, provide a blank option
		if(empty($element->default_value)){
			$country_markup = '<option value="" selected="selected"></option>'."\n";
		}else{
			$country_markup = '';
		}
		
		foreach ($country as $data){
			if($data['value'] == $element->default_value){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			
			//check for populated value, use it instead of default value
			if(!empty($element->populated_value['element_'.$element->id.'_6']['default_value'])){
				$selected = '';
				if($element->populated_value['element_'.$element->id.'_6']['default_value'] == $data['value']){
					$selected = 'selected="selected"';
				}
			}
			
			$country_markup .= "<option value=\"{$data['value']}\" {$selected}>{$data['label']}</option>\n";
		}
		
$element_markup = <<<EOT
		<li id="li_{$element->id}" {$error_class}>
		<label class="description">{$element->title} {$span_required}</label>
		
		<div id="li_{$element->id}_div_1">
			<input id="element_{$element->id}_1" name="element_{$element->id}_1" class="element text large" value="{$element->populated_value['element_'.$element->id.'_1']['default_value']}" type="text" />
			<label for="element_{$element->id}_1">{$lang['address_street']}</label>
		</div>
	
		<div id="li_{$element->id}_div_2">
			<input id="element_{$element->id}_2" name="element_{$element->id}_2" class="element text large" value="{$element->populated_value['element_'.$element->id.'_2']['default_value']}" type="text" />
			<label for="element_{$element->id}_2">{$lang['address_street2']}</label>
		</div>
	
		<div id="li_{$element->id}_div_3" class="left">
			<input id="element_{$element->id}_3" name="element_{$element->id}_3" class="element text medium" value="{$element->populated_value['element_'.$element->id.'_3']['default_value']}" type="text" />
			<label for="element_{$element->id}_3">{$lang['address_city']}</label>
		</div>
	
		<div id="li_{$element->id}_div_4" class="right">
			<input id="element_{$element->id}_4" name="element_{$element->id}_4" class="element text medium"  value="{$element->populated_value['element_'.$element->id.'_4']['default_value']}" type="text" />
			<label for="element_{$element->id}_4">{$lang['address_state']}</label>
		</div>
	
		<div id="li_{$element->id}_div_5" class="left">
			<input id="element_{$element->id}_5" name="element_{$element->id}_5" class="element text medium" maxlength="15" value="{$element->populated_value['element_'.$element->id.'_5']['default_value']}" type="text" />
			<label for="element_{$element->id}_5">{$lang['address_zip']}</label>
		</div>
		
		<div id="li_{$element->id}_div_6" class="right">
			<select class="element select medium" id="element_{$element->id}_6" name="element_{$element->id}_6"> 
			{$country_markup}	
			</select>
		<label for="element_{$element->id}_6">{$lang['address_country']}</label>
	</div>&nbsp;{$guidelines} {$error_message}
		</li>
EOT;
		
	
		return $element_markup;
	}
	
	
	//Captcha
	function display_captcha($element){
		
		if(!empty($element->error_message)){
			$error_code = $element->error_message;
		}else{
			$error_code = '';
		}
					
		//check for error
		$error_class = '';
		$error_message = '';
		$span_required = '';
		$guidelines = '';
		global $lang;		
		
		if(!empty($element->is_error)){
			
			if($element->error_message == 'el-required'){
				$element->error_message = $lang['captcha_required'];
				$error_code = '';	
			}elseif ($element->error_message == 'incorrect-captcha-sol'){
				$element->error_message = $lang['captcha_mismatch'];
			}else{
				$element->error_message = "{$lang['captcha_error']} ({$element->error_message})";
			}
			
			$error_class = 'class="error"';
			$error_message = "<p class=\"error\">{$element->error_message}</p>";
		}

		if(!empty($_SERVER['HTTPS'])){
			$use_ssl = true;
		}else{
			$use_ssl = false;
		}
		
		
		if(USE_INTERNAL_CAPTCHA === true){ //use the internal captcha if enabled
		
			$machform_path = '';
			if(!empty($element->machform_path)){
				$machform_path = $element->machform_path;
			}
			
			$timestamp = time(); //use this as paramater for captcha.php, to prevent caching
			
			$element->title = $lang['captcha_title'];
$captcha_html = <<<EOT
<img id="captcha_image" src="{$machform_path}captcha.php?t={$timestamp}" width="200" height="60" alt="Please refresh your browser to see this image." /><br />
<input id="captcha_response_field" name="captcha_response_field" class="element text small" type="text" /><div id="dummy_captcha_internal"></div>
EOT;
	 		
		}else{ //otherwise use the reCAPTCHA
			$captcha_html = recaptcha_get_html(RECAPTCHA_PUBLIC_KEY, $error_code,$use_ssl);
	
			if($captcha_html === false){
				$domain = str_replace('www.','',$_SERVER['SERVER_NAME']);
				$captcha_html = "<b>Error!</b> You have enabled CAPTCHA but no API key available. <br /><br />To use CAPTCHA you must get an API key from <a href='".recaptcha_get_signup_url($domain,'MachForm')."'>http://recaptcha.net/api/getkey</a><br /><br />After getting the API key, save them into your <b>config.php</b> file.";
				$error_class = 'class="error"';
			}
		}
		
		
		if(function_exists("form{$element->form_id}_hook_pre_captcha")){
			$custom_precaptcha = call_user_func("form{$element->form_id}_hook_pre_captcha");
		}
				
$element_markup = <<<EOT
		<li id="li_captcha" {$error_class}> {$custom_precaptcha}
		<label class="description" for="captcha_response_field">{$element->title} {$span_required}</label>
		<div>
			{$captcha_html}	
		</div>	 
		{$guidelines} {$error_message}
		</li>
EOT;
		
		return $element_markup;
	}
	
	
	//Main function to display a form
	//There are few mode when displaying a form
	//1. New blank form (form populated with default values)
	//2. New form with error (displayed when 1 submitted and having error, form populated with user inputs)
	//3. Edit form (form populated with data from db)
	//4. Edit form with error (displayed when 3 submiteed and having error)
	function display_form($form_id,$populated_values=array(),$error_elements=array(),$custom_error='',$edit_id=0,$embed=false){
		
		global $lang;
		
		//if there is custom error, don't show other errors
		if(!empty($custom_error)){
			$error_elements = array();
		}
		
		//get form properties data
		$query 	= "select 
						 form_name,
						 form_description,
						 form_redirect,
						 form_success_message,
						 form_password,
						 form_unique_ip,
						 form_frame_height,
						 form_has_css,
						 form_active,
						 form_captcha,
						 form_review
				     from 
				     	 ap_forms 
				    where 
				    	 form_id='$form_id'";
		$result = do_query($query);
		$row 	= do_fetch_result($result);
	
		$form = new stdClass();
		
		$form->id 				= $form_id;
		$form->name 			= $row['form_name'];
		$form->description 		= $row['form_description'];
		$form->redirect 		= $row['form_redirect'];
		$form->success_message  = $row['form_success_message'];
		$form->password 		= $row['form_password'];
		$form->frame_height 	= $row['form_frame_height'];
		$form->unique_ip 		= $row['form_unique_ip'];
		$form->has_css 			= $row['form_has_css'];
		$form->active 			= $row['form_active'];
		$form->captcha 			= $row['form_captcha'];
		$form->review 			= $row['form_review'];
		
		if(empty($error_elements)){
			$form->is_error 	= 0;
		}else{
			$form->is_error 	= 1;
		}
		
		//if this form has review enabled and user are having $_SESSION['review_id'], then populate the form with that values
		if(!empty($form->review) && !empty($_SESSION['review_id']) && empty($populated_values)){
			$populated_values = get_entry_values($form_id,$_SESSION['review_id'],true);
		}
		
		//get elements data
		//get element options first and store it into array
		$query = "select 
						element_id,
						option_id,
						`position`,
						`option`,
						option_is_default 
				    from 
				    	ap_element_options 
				   where 
				   		form_id='$form_id' and live=1 
				order by 
						element_id asc,`position` asc";
		$result = do_query($query);
		while($row = do_fetch_result($result)){
			$element_id = $row['element_id'];
			$option_id  = $row['option_id'];
			$options_lookup[$element_id][$option_id]['position'] 		  = $row['position'];
			$options_lookup[$element_id][$option_id]['option'] 			  = $row['option'];
			$options_lookup[$element_id][$option_id]['option_is_default'] = $row['option_is_default'];
		}
	
		
		//get elements data
		$element = array();
		$query = "select 
						element_id,
						element_title,
						element_guidelines,
						element_size,
						element_is_required,
						element_is_unique,
						element_is_private,
						element_type,
						element_position,
						element_default_value,
						element_constraint 
					from 
						ap_form_elements 
				   where 
				   		form_id='$form_id' 
				order by 
						element_position asc";
		$result = do_query($query);
		
		$j=0;
		$has_calendar = false; //assume the form doesn't have calendar, so it won't load calendar.js
		while($row = do_fetch_result($result)){
			$element_id = $row['element_id'];
			
			//lookup element options first
			if(!empty($options_lookup[$element_id])){
				$element_options = array();
				$i=0;
				foreach ($options_lookup[$element_id] as $option_id=>$data){
					$element_options[$i] = new stdClass();
					$element_options[$i]->id 		 = $option_id;
					$element_options[$i]->option 	 = $data['option'];
					$element_options[$i]->is_default = $data['option_is_default'];
					$element_options[$i]->is_db_live = 1;
					$i++;
				}
			}
			
		
			//populate elements
			$element[$j] = new stdClass();
			$element[$j]->title 		= $row['element_title'];
			$element[$j]->guidelines 	= $row['element_guidelines'];
			$element[$j]->size 			= $row['element_size'];
			$element[$j]->is_required 	= $row['element_is_required'];
			$element[$j]->is_unique 	= $row['element_is_unique'];
			$element[$j]->is_private 	= $row['element_is_private'];
			$element[$j]->type 			= $row['element_type'];
			$element[$j]->position 		= $row['element_position'];
			$element[$j]->id 			= $row['element_id'];
			$element[$j]->is_db_live 	= 1;
			
			//this data came from db or form submit
			//being used to display edit form or redisplay form with errors and previous inputs
			//this should be optimized in the future, only pass necessary data, not the whole array			
			$element[$j]->populated_value = $populated_values;
			
			//if there is file upload type, set form enctype to multipart
			if($row['element_type'] == 'file'){
				$form_enc_type = 'enctype="multipart/form-data"';
			}
			
			if(!empty($error_elements[$element[$j]->id])){
				$element[$j]->is_error 	    = 1;
				$element[$j]->error_message = $error_elements[$element[$j]->id];
			}
			
			
			$element[$j]->default_value = htmlspecialchars($row['element_default_value']);
			
			
			$element[$j]->constraint 	= $row['element_constraint'];
			if(!empty($element_options)){
				$element[$j]->options 	= $element_options;
			}else{
				$element[$j]->options 	= '';
			}
			
			
			//check for calendar type
			if($row['element_type'] == 'date' || $row['element_type'] == 'europe_date'){
				$has_calendar = true;
			}
			
			$j++;
		}
		
		
		//add captcha if enable
		if(!empty($form->captcha) && (empty($edit_id))){
			$element[$j] = new stdClass();
			$element[$j]->type 			= 'captcha';
			$element[$j]->form_id 		= $form_id;
			$element[$j]->is_private	= 0;
			
			if(!empty($error_elements['element_captcha'])){
				$element[$j]->is_error 	    = 1;
				$element[$j]->error_message = $error_elements['element_captcha'];
			}
		}
		
		//generate html markup for each element
		$all_element_markup = '';
		foreach ($element as $element_data){
			if($element_data->is_private && empty($_SESSION['logged_in'])){ //don't show private element
				continue;
			}
			$all_element_markup .= call_user_func('display_'.$element_data->type,$element_data);
		}
		
		if(!empty($custom_error)){
			$form->error_message =<<<EOT
			<li id="error_message">
					<h3 id="error_message_title">{$custom_error}</h3>
			</li>	
EOT;
		}elseif(!empty($error_elements)){
			$form->error_message =<<<EOT
			<li id="error_message">
					<h3 id="error_message_title">{$lang['error_title']}</h3>
					<p id="error_message_desc">{$lang['error_desc']}</p>
			</li>	
EOT;
		}
		
		
		//display edit_id if there is any
		if(!empty($edit_id)){
			$edit_markup = "<input type=\"hidden\" name=\"edit_id\" value=\"{$edit_id}\" />\n";
		}else{
			$edit_markup = '';
		}
		
		if(empty($form->review)){
			$button_text = $lang['submit_button'];
		}else{
			$button_text = $lang['continue_button'];
		}
		
		//markup for submit button
		$button_markup =<<<EOT
		<li id="li_buttons" class="buttons">
			    <input type="hidden" name="form_id" value="{$form->id}" />
			    {$edit_markup}
			    <input type="hidden" name="submit" value="1" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="{$button_text}" />
		</li>
EOT;
		
		//check for specific form css, if any, use it instead
		if($form->has_css){
			$css_dir = DATA_DIR."/form_{$form_id}/css/";
		}
		
		if(!empty($form->password) && empty($_SESSION['user_authenticated'])){ //if form require password and password hasn't set yet
			$show_password_form = true;
			
		}elseif (!empty($form->password) && !empty($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] != $form_id){ //if user authenticated but not for this form
			$show_password_form = true;
			
		}else{ //user authenticated for this form, or no password required
			$show_password_form = false;
		}
		
				
		if(empty($form->active)){ //if form is not active, don't show the fields
			$form_desc_div ='';	
			$all_element_markup = '';
			$button_markup = '';
			$ul_class = 'class="password"';
			$custom_element =<<<EOT
			<li>
				<h2>{$lang['form_inactive']}</h2>
			</li>
EOT;
		}elseif($show_password_form){ //don't show form description if this page is password protected and user not authenticated
			$form_desc_div ='';	
			$all_element_markup = '';	
			$custom_element =<<<EOT
			<li>
				<h2>{$lang['form_pass_title']}</h2>
				<div>
				<input type="password" value="" class="text" name="password" id="password" />
				<label for="password" class="desc">{$lang['form_pass_desc']}</label>
				</div>
			</li>
EOT;
			$ul_class = 'class="password"';
		}else{
			if(!empty($form->name) || !empty($form->description)){
			$form_desc_div =<<<EOT
		<div class="form_description">
			<h2>{$form->name}</h2>
			<p>{$form->description}</p>
		</div>
EOT;
			}
		}
		
		if($embed){
			$embed_class = 'class="embed"';
		}
		
		if($has_calendar){
			$calendar_js = '<script type="text/javascript" src="js/calendar.js"></script>';
		}else{
			$calendar_js = '';
		}
		
		//If you would like to remove the "Powered by MachForm" link, please contact us at customer.service@appnitro.com before doing so
		$form_markup = <<<EOT
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<meta content="width=device-width,user-scalable=no" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>{$form->name}</title>
<link rel="stylesheet" type="text/css" href="{$css_dir}view.css" media="all" />
<script type="text/javascript" src="js/view.js"></script>
{$calendar_js}
</head>
<body id="main_body" {$embed_class}>
	
	<div id="form_container">
	
		<h1><a>{$form->name}</a></h1>
		<form id="form_{$form->id}" class="appnitro" {$form_enc_type} method="post" action="#main_body">
			{$form_desc_div}						
			<ul {$ul_class}>
			{$form->error_message}
			{$all_element_markup}
			{$custom_element}
			{$button_markup}
			</ul>
		</form>	
		
	</div>
	<div style="padding-top:10px;color:#336699" align="center">迈瑞学院</div>
	</body>
</html>
EOT;
		return $form_markup;
		
	}
	
	//this function is similar as display_form, but designed to display form without IFRAME
	function display_integrated_form($form_id,$populated_values=array(),$error_elements=array(),$custom_error='',$edit_id=0,$machform_path){
		
		global $lang;
		
		//if there is custom error, don't show other errors
		if(!empty($custom_error)){
			$error_elements = array();
		}
		
		//get form properties data
		$query 	= "select 
						 form_name,
						 form_description,
						 form_redirect,
						 form_success_message,
						 form_password,
						 form_unique_ip,
						 form_frame_height,
						 form_has_css,
						 form_active,
						 form_captcha,
						 form_review
				     from 
				     	 ap_forms 
				    where 
				    	 form_id='$form_id'";
		$result = do_query($query);
		$row 	= do_fetch_result($result);
	
		$form = new stdClass();
		
		$form->id 				= $form_id;
		$form->name 			= $row['form_name'];
		$form->description 		= $row['form_description'];
		$form->redirect 		= $row['form_redirect'];
		$form->success_message  = $row['form_success_message'];
		$form->password 		= $row['form_password'];
		$form->frame_height 	= $row['form_frame_height'];
		$form->unique_ip 		= $row['form_unique_ip'];
		$form->has_css 			= $row['form_has_css'];
		$form->active 			= $row['form_active'];
		$form->captcha 			= $row['form_captcha'];
		$form->review 			= $row['form_review'];
		
		if(empty($error_elements)){
			$form->is_error 	= 0;
		}else{
			$form->is_error 	= 1;
		}
		
		//if this form has review enabled and user are having $_SESSION['review_id'], then populate the form with that values
		if(!empty($form->review) && !empty($_SESSION['review_id']) && empty($populated_values)){
			$param['machform_path'] = $machform_path;
			$populated_values = get_entry_values($form_id,$_SESSION['review_id'],true,$param);
		}
		
		//get elements data
		//get element options first and store it into array
		$query = "select 
						element_id,
						option_id,
						`position`,
						`option`,
						option_is_default 
				    from 
				    	ap_element_options 
				   where 
				   		form_id='$form_id' and live=1 
				order by 
						element_id asc,`position` asc";
		$result = do_query($query);
		while($row = do_fetch_result($result)){
			$element_id = $row['element_id'];
			$option_id  = $row['option_id'];
			$options_lookup[$element_id][$option_id]['position'] 		  = $row['position'];
			$options_lookup[$element_id][$option_id]['option'] 			  = $row['option'];
			$options_lookup[$element_id][$option_id]['option_is_default'] = $row['option_is_default'];
		}
	
		
		//get elements data
		$element = array();
		$query = "select 
						element_id,
						element_title,
						element_guidelines,
						element_size,
						element_is_required,
						element_is_unique,
						element_is_private,
						element_type,
						element_position,
						element_default_value,
						element_constraint 
					from 
						ap_form_elements 
				   where 
				   		form_id='$form_id' 
				order by 
						element_position asc";
		$result = do_query($query);
		
		$j=0;
		$has_calendar = false;
		while($row = do_fetch_result($result)){
			$element_id = $row['element_id'];
			
			//lookup element options first
			if(!empty($options_lookup[$element_id])){
				$element_options = array();
				$i=0;
				foreach ($options_lookup[$element_id] as $option_id=>$data){
					$element_options[$i] = new stdClass();
					$element_options[$i]->id 		 = $option_id;
					$element_options[$i]->option 	 = $data['option'];
					$element_options[$i]->is_default = $data['option_is_default'];
					$element_options[$i]->is_db_live = 1;
					$i++;
				}
			}
			
		
			//populate elements
			$element[$j] = new stdClass();
			$element[$j]->title 		= $row['element_title'];
			
			if(empty($edit_id)){
				$element[$j]->guidelines 	= $row['element_guidelines'];
			}else{
				$element[$j]->guidelines 	= '';
			}
			
			$element[$j]->size 			= $row['element_size'];
			$element[$j]->is_required 	= $row['element_is_required'];
			$element[$j]->is_unique 	= $row['element_is_unique'];
			$element[$j]->is_private 	= $row['element_is_private'];
			$element[$j]->type 			= $row['element_type'];
			$element[$j]->position 		= $row['element_position'];
			$element[$j]->id 			= $row['element_id'];
			$element[$j]->is_db_live 	= 1;
			$element[$j]->machform_path	= $machform_path;
			
			//this data came from db or form submit
			//being used to display edit form or redisplay form with errors and previous inputs
			//this should be optimized in the future, only pass necessary data, not the whole array			
			$element[$j]->populated_value = $populated_values;
			
			//if there is file upload type, set form enctype to multipart
			if($row['element_type'] == 'file'){
				$form_enc_type = 'enctype="multipart/form-data"';
			}
			
			if(!empty($error_elements[$element[$j]->id])){
				$element[$j]->is_error 	    = 1;
				$element[$j]->error_message = $error_elements[$element[$j]->id];
			}
			
			
			$element[$j]->default_value = htmlspecialchars($row['element_default_value']);
			
			
			$element[$j]->constraint 	= $row['element_constraint'];
			if(!empty($element_options)){
				$element[$j]->options 	= $element_options;
			}else{
				$element[$j]->options 	= '';
			}
			
			//check for calendar type
			if($row['element_type'] == 'date' || $row['element_type'] == 'europe_date'){
				$has_calendar = true;
			}
			
			$j++;
		}
		
		
		//add captcha if enable
		if(!empty($form->captcha) && (empty($edit_id))){
			$element[$j] = new stdClass();
			$element[$j]->type 			= 'captcha';
			$element[$j]->form_id 		= $form_id;
			$element[$j]->machform_path	= $machform_path;
			
			if(!empty($error_elements['element_captcha'])){
				$element[$j]->is_error 	    = 1;
				$element[$j]->error_message = $error_elements['element_captcha'];
			}
		}
		
		//generate html markup for each element
		$all_element_markup = '';
		foreach ($element as $element_data){
			if($element_data->is_private && empty($_SESSION['logged_in'])){ //don't show private element
				continue;
			}
			$all_element_markup .= call_user_func('display_'.$element_data->type,$element_data);
		}
		
		if(!empty($custom_error)){
			$form->error_message =<<<EOT
			<li id="error_message">
					<h3 id="error_message_title">{$custom_error}</h3>
			</li>	
EOT;
		}elseif(!empty($error_elements)){
			$form->error_message =<<<EOT
			<li id="error_message">
					<h3 id="error_message_title">{$lang['error_title']}</h3>
					<p id="error_message_desc">{$lang['error_desc']}</p>
			</li>	
EOT;
		}
		
		if(!empty($form->password) && empty($_SESSION['user_authenticated'])){ //if form require password and password hasn't set yet
			$show_password_form = true;
			
		}elseif (!empty($form->password) && !empty($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] != $form_id){ //if user authenticated but not for this form
			$show_password_form = true;
			
		}else{ //user authenticated for this form, or no password required
			$show_password_form = false;
		}
		
		//display edit_id if there is any
		if(!empty($edit_id)){
			$edit_markup = "<input type=\"hidden\" name=\"edit_id\" value=\"{$edit_id}\" />\n";
			$submit_button = '<input id="saveForm" class="button_text" type="submit" name="submit" value="Save Changes" />';
		}else{
			$edit_markup = '';
						
			if(!empty($form->review) && !$show_password_form){
				$submit_button = '<input type="hidden" name="submit_continue" value="1" />'."\n".'<input id="saveForm" class="button_text" type="submit" name="submit_continue" value="'.$lang['continue_button'].'" />';
			}else{
				$submit_button = '<input type="hidden" name="submit" value="1" />'."\n".'<input id="saveForm" class="button_text" type="submit" name="submit" value="'.$lang['submit_button'].'" />';
			}
		}
		
				
		//markup for submit button
		$button_markup =<<<EOT
		<li id="li_buttons" class="buttons">
			    <input type="hidden" name="form_id" value="{$form->id}" />
			    {$edit_markup}
				{$submit_button}
		</li>
EOT;
		
		//check for specific form css, if any, use it instead
		if($form->has_css){
			$css_dir = DATA_DIR."/form_{$form_id}/css/";
		}
				
				
		if(empty($form->active)){ //if form is not active, don't show the fields
			$form_desc_div ='';	
			$all_element_markup = '';
			$button_markup = '';
			$ul_class = 'class="password"';
			$custom_element =<<<EOT
			<li>
				<h2>{$lang['form_inactive']}</h2>
			</li>
EOT;
		}elseif($show_password_form){ //don't show form description if this page is password protected and user not authenticated
			$form_desc_div ='';	
			$all_element_markup = '';	
			$custom_element =<<<EOT
			<li>
				<h2>{$lang['form_pass_title']}</h2>
				<div>
				<input type="password" value="" class="text" name="password" id="password" />
				<label for="password" class="desc">{$lang['form_pass_desc']}</label>
				</div>
			</li>
EOT;
			$ul_class = 'class="password"';
		}else{
			if(!empty($form->name) || !empty($form->description)){
			$form_desc_div =<<<EOT
		<div class="form_description">
			<h2>{$form->name}</h2>
			<p>{$form->description}</p>
		</div>
EOT;
			}
		}
		
		
		$embed_class = 'class="integrated"';
		
		if(empty($edit_id)){
			$css_markup = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$machform_path}{$css_dir}view.css\" media=\"all\" />";
		}else{
			$css_markup = "<link rel=\"stylesheet\" type=\"text/css\" href=\"edit_entry.css\" media=\"all\" />"; 
		}
		
		if($has_calendar){
			$calendar_js = "<script type=\"text/javascript\" src=\"{$machform_path}js/calendar.js\"></script>";
		}else{
			$calendar_js = '';
		}
		
		
		//If you would like to remove the "Powered by MachForm" link, please contact us at customer.service@appnitro.com before doing so
		$form_markup =<<<EOT
{$css_markup}
<script type="text/javascript" src="{$machform_path}js/view.js"></script>
{$calendar_js}

<div id="main_body" {$embed_class}>
		
	<div id="form_container">
	
		<h1><a>{$form->name}</a></h1>
		<form id="form_{$form->id}" class="appnitro" {$form_enc_type} method="post" action="#main_body">
			{$form_desc_div}						
			<ul {$ul_class}>
			{$form->error_message}
			{$all_element_markup}
			{$custom_element}
			{$button_markup}
			</ul>
		</form>	
		<div id="footer">
			
		</div>
	</div>
</div>
EOT;
		return $form_markup;
		
	}
	
	function display_success($form_id,$embed=false){
		//get form properties data
		$query 	= "select 
						  form_success_message,
						  form_has_css,
						  form_name
				     from 
				     	 ap_forms 
				    where 
				    	 form_id='$form_id'";
		
		$result = do_query($query);
		$row 	= do_fetch_result($result);
	
		$form = new stdClass();
		
		$form->id 				= $form_id;
		$form->success_message  = nl2br($row['form_success_message']);
		$form->has_css 			= $row['form_has_css'];
		$form->name 			= $row['form_name'];
	
		
		//check for specific form css, if any, use it instead
		if($form->has_css){
			$css_dir = DATA_DIR."/form_{$form_id}/css/";
		}
		
		if($embed){
			$embed_class = 'class="embed"';
		}
	
		$form_markup = <<<EOT
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<meta content="width=device-width,user-scalable=no" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>{$form->name}</title>
<link rel="stylesheet" type="text/css" href="{$css_dir}view.css" media="all" />
</head>
<body id="main_body" {$embed_class}>
	
	
	<div id="form_container">
	
		<h1><a>迈瑞学院问卷调查</a></h1>
			
		<div class="form_success">
			<h2>{$form->success_message}</h2>
		</div>
		<div id="footer" class="success">
			
		</div>		
	</div>
	
	</body>
</html>
EOT;
		return $form_markup;
	}
	
	//this function is similar as display_success, but designed to display success page without IFRAME
	function display_integrated_success($form_id,$machform_path){
		//get form properties data
		$query 	= "select 
						  form_success_message,
						  form_has_css
				     from 
				     	 ap_forms 
				    where 
				    	 form_id='$form_id'";
		
		$result = do_query($query);
		$row 	= do_fetch_result($result);
	
		$form = new stdClass();
		
		$form->id 				= $form_id;
		$form->success_message  = nl2br($row['form_success_message']);
		$form->has_css 			= $row['form_has_css'];
	
		
		//check for specific form css, if any, use it instead
		if($form->has_css){
			$css_dir = DATA_DIR."/form_{$form_id}/css/";
		}
		
			
		$form_markup = <<<EOT
<link rel="stylesheet" type="text/css" href="{$machform_path}{$css_dir}view.css" media="all" />
<div id="main_body" class="embed">
	<div id="form_container">
		<h1><a>迈瑞学院问卷调查</a></h1>
			
		<div class="form_success">
			<h2>{$form->success_message}</h2>
		</div>
		<div id="footer" class="success">
			
		</div>		
	</div>
</div>
EOT;
		return $form_markup;
	}	
	
	//display form confirmation page
	function display_form_review($form_id,$record_id,$embed=false){
		global $lang;
		
		//get form properties data
		$query 	= "select 
						  form_has_css,
						  form_redirect
				     from 
				     	 ap_forms 
				    where 
				    	 form_id='$form_id'";
		
		$result = do_query($query);
		$row 	= do_fetch_result($result);
	
		
		$form_has_css 			= $row['form_has_css'];
		$form_redirect			= $row['form_redirect'];
		
		//prepare entry data for previewing
		$param['strip_download_link'] = true;
		$param['review_mode']    	  = true;
		$param['show_attach_image']   = true;
		$entry_details = get_entry_details($form_id,$record_id,$param);
		
		$entry_data = '<table id="machform_review_table" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>'."\n";
		
		$toggle = false;
		foreach ($entry_details as $data){ 
			if($toggle){
				$toggle = false;
				$row_style = 'class="alt"';
			}else{
				$toggle = true;
				$row_style = '';
			}	

  			$entry_data .= "<tr {$row_style}>\n";
  	    	$entry_data .= "<td width=\"40%\"><strong>{$data['label']}</strong></td>\n";
  			$entry_data .= "<td width=\"60%\">".nl2br($data['value'])."</td>\n";
  			$entry_data .= "</tr>\n";
 		}   	
		 	
   	    $entry_data .= '</tbody></table>';

		//check for specific form css, if any, use it instead
		if($form_has_css){
			$css_dir = DATA_DIR."/form_{$form_id}/css/";
		}
		
		if($embed){
			$embed_class = 'class="embed"';
		}
	
		$form_markup = <<<EOT
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<meta content="width=device-width,user-scalable=no" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>{$form_name}</title>
<link rel="stylesheet" type="text/css" href="{$css_dir}view.css" media="all" />
<script src="js/jquery/jquery-core.js"></script>  
<script src="js/jquery/jquery-columnhover.js"></script>
<script>
$(document).ready(function () {
	$('#machform_review_table').columnHover(); 
});
</script>
</head>
<body id="main_body" {$embed_class}>
	
	<img id="top" src="images/top.png" alt="" />
	<div id="form_container">
	
		<h1><a>Appnitro MachForm</a></h1>
		<form id="form_{$form->id}" class="appnitro" method="post" action="{$_SERVER['PHP_SELF']}">
		    <div class="form_description">
				<h2>{$lang['review_title']}</h2>
				<p>{$lang['review_message']}</p>
			</div>
			{$entry_data}
			<ul>
			<li id="li_buttons" class="buttons">
			    <input type="hidden" name="id" value="{$form_id}" />
			    <input id="review_back" class="button_text" type="submit" name="review_back" value="&laquo; {$lang['back_button']}" />
			    <input id="review_submit" class="button_text" type="submit" name="review_submit" value="{$lang['submit_button']}" />
			</li>
			</ul>
		</form>		
		<div id="footer" class="success">
		</div>		
	</div>
	<img id="bottom" src="images/bottom.png" alt="" />
	</body>
</html>
EOT;
		return $form_markup;
	}
	
	//display form confirmation page for integrated embed code
	function display_integrated_form_review($form_id,$record_id,$machform_path){
		global $lang;
		
		//get form properties data
		$query 	= "select 
						  form_has_css,
						  form_redirect
				     from 
				     	 ap_forms 
				    where 
				    	 form_id='$form_id'";
		
		$result = do_query($query);
		$row 	= do_fetch_result($result);
	
		
		$form_has_css 			= $row['form_has_css'];
		$form_redirect			= $row['form_redirect'];
		
		//prepare entry data for previewing
		$param['strip_download_link'] = true;
		$param['review_mode']    	  = true;
		$param['show_attach_image']   = true;
		$param['machform_path']		  = $machform_path;
		$entry_details = get_entry_details($form_id,$record_id,$param);
		
		$entry_data = '<table id="machform_review_table" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody>'."\n";
		
		$toggle = false;
		foreach ($entry_details as $data){ 
			if($toggle){
				$toggle = false;
				$row_style = 'class="alt"';
			}else{
				$toggle = true;
				$row_style = '';
			}	

  			$entry_data .= "<tr {$row_style}>\n";
  	    	$entry_data .= "<td width=\"40%\"><strong>{$data['label']}</strong></td>\n";
  			$entry_data .= "<td width=\"60%\">".nl2br($data['value'])."</td>\n";
  			$entry_data .= "</tr>\n";
 		}   	
		 	
   	    $entry_data .= '</tbody></table>';

		//check for specific form css, if any, use it instead
		if($form_has_css){
			$css_dir = DATA_DIR."/form_{$form_id}/css/";
		}
		
		if($embed){
			$embed_class = 'class="embed"';
		}
	
		$form_action = str_replace(array("&show_review=1","?show_review=1"),"",$_SERVER['REQUEST_URI']);
		
		$form_markup = <<<EOT
<link rel="stylesheet" type="text/css" href="{$machform_path}{$css_dir}view.css" media="all" />
<script src="{$machform_path}js/jquery/jquery-core.js"></script>  
<script src="{$machform_path}js/jquery/jquery-columnhover.js"></script>
<script>
$(document).ready(function () {
	$('#machform_review_table').columnHover(); 
});
</script>
</head>
<div id="main_body" class="embed">
	<div id="form_container">
		<h1><a>Appnitro MachForm</a></h1>
		<form id="form_{$form->id}" class="appnitro" method="post" action="{$form_action}">
		    <div class="form_description">
				<h2>{$lang['review_title']}</h2>
				<p>{$lang['review_message']}</p>
			</div>
			{$entry_data}
			<ul>
			<li id="li_buttons" class="buttons">
			    <input type="hidden" name="id" value="{$form_id}" />
			    <input id="review_back" class="button_text" type="submit" name="review_back" value="&laquo; {$lang['back_button']}" />
			    <input id="review_submit" class="button_text" type="submit" name="review_submit" value="{$lang['submit_button']}" />
			</li>
			</ul>
		</form>		
		<div id="footer" class="success">
		</div>		
	</div>
</div>
EOT;
		return $form_markup;
	}
?>