<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<div class="main">
	<form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" onsubmit="return formcheck(this)">
		<h4>风格设置</h4>
		<table class="tb">
			<tr>
				<th><label for="">模板风格</label></th>
				<td>
					<select name="template">
						<?php  if(is_array($template)) { foreach($template as $path) { ?>
						<option value="<?php  echo $path;?>" <?php  if($_W['setting']['basic']['template'] == $path) { ?> selected<?php  } ?>><?php  echo $path;?></option>
						<?php  } } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<input name="submit" type="submit" value="提交" class="btn btn-primary span3" />
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</td>
			</tr>
		</table>
	</form>
</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>