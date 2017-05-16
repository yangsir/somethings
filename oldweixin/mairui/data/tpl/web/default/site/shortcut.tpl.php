<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<form class="form-horizontal form" action="" method="post" enctype="multipart/form-data">
<div class="main">
	<div class="alert alert-info" style="margin-top:10px; width:100%">
		设置微站底部的快捷菜单显示的模板与风格，并可以定制快捷菜单在模块中是否显示。
	</div>
	<input type="hidden" name="id" value="<?php  echo $id;?>" />
	<input type="hidden" name="templateid" value="<?php  echo $template['id'];?>">
	<h4>快捷菜单风格模板</h4>
	<table class="tb">
		<tr>
			<th><label for="">选择模板</label></th>
			<td>
				<select name="template" onchange="$('#preview').attr('src', '<?php  echo create_url('site/shortcut/preview')?>&file='+this.options[this.options.selectedIndex].value);$('#preview').document.reload();" autocomplete="off">
					<?php  if(is_array($template)) { foreach($template as $path) { ?>
					<option value="<?php  echo $path;?>" <?php  if($_W['account']['quickmenu']['template'] == $path) { ?> selected<?php  } ?>><?php  echo $path;?></option>
					<?php  } } ?>
				</select>
				<div class="help-block">选择微站底部快捷菜单的风格模板，选择后可通过下方的模板预览进得查看。</div>
			</td>
		</tr>
		<tr>
			<th><label for="">启用显示模块</label></th>
			<td>
				<div style="overflow:hidden;">
					<?php  if(is_array($_W['account']['modules'])) { foreach($_W['account']['modules'] as $item) { ?>
					<?php  if(!in_array($item['name'], $ignore)) { ?>
					<label class="checkbox" style="width:200px; float:left;"><input type="checkbox" name="module[]" value="<?php  echo $item['name'];?>" <?php  if(in_array($item['name'], (array)$_W['account']['quickmenu']['enablemodule'])) { ?> checked<?php  } ?> /> <?php  echo $item['title'];?></label>
					<?php  } ?>
					<?php  } } ?>
				</div>
				<div class="help-block">微站底部快捷菜单只在勾选上的模块中显示，未勾选则不显示快捷菜单。</div>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				<input type="submit" class="btn btn-primary" name="submit" value="提交" />
			</td>
		</tr>
		<tr>
			<th><label for="">模板预览</label></th>
			<td>
				<iframe width="100%" scrolling="yes" frameborder="0" src="<?php  echo create_url('site/shortcut/preview', array('file' => 'default'))?>" id="preview" name="preview" style="width: 320px; overflow: visible; height: 280px;"></iframe>
			</td>
		</tr>
	</table>
</div>
</form>
<script type="text/javascript">
<!--

//-->
</script>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>