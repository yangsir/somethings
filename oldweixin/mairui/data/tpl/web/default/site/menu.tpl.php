<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<ul class="nav nav-tabs">
	<?php  if($visiables['home']) { ?><li<?php  if($do == 'display' && $position == '1') { ?> class="active"<?php  } ?>><a href="<?php  echo create_url('site/nav', array('position' => 1, 'name' => $modulename));?>">首页导航管理</a></li><?php  } ?>
	<?php  if($visiables['shortcut']) { ?><li<?php  if($do == 'display' && $position == '3') { ?> class="active"<?php  } ?>><a href="<?php  echo create_url('site/nav', array('position' => 3, 'name' => $modulename));?>">快捷导航管理</a></li><?php  } ?>
	<?php  if($visiables['profile']) { ?><li<?php  if($do == 'display' && $position == '2') { ?> class="active"<?php  } ?>><a href="<?php  echo create_url('site/nav', array('position' => 2, 'name' => $modulename));?>">个人中心导航管理</a></li><?php  } ?>
	<?php  if(empty($modulename)) { ?>
	<li<?php  if($do == 'post' && empty($id)) { ?> class="active"<?php  } ?>><a href="<?php  echo create_url('site/nav/post', array('_p' => $position));?>"><i class="icon-plus"></i> 添加导航条目</a></li>
	<?php  if($do == 'post' && !empty($id)) { ?><li class="active"><a href="<?php  echo create_url('site/nav/post', array('id' => $id, '_p' => $_p));?>"><i class="icon-edit"></i> 编辑导航条目</a></li><?php  } ?>
	<?php  } ?>
</ul>
<?php  if($do == 'post') { ?>
<form class="form-horizontal form" action="" method="post" enctype="multipart/form-data">
<div class="main">
	<input type="hidden" name="id" value="<?php  echo $id;?>" />
	<input type="hidden" name="templateid" value="<?php  echo $template['id'];?>">
	<h4>微站导航</h4>
	<table class="tb">
		<tr>
			<th><label for="">微站导航位置</label></th>
			<td>
				<?php  if(empty($item['position']) || empty($item['module'])) { ?>
				<select name="position">
					<?php  if(is_array($positions)) { foreach($positions as $key => $p) { ?>
					<option value="<?php  echo $key;?>"<?php  if($_p == $key) { ?> selected="selected"<?php  } ?>><?php  echo $p['title'];?></option>
					<?php  } } ?>
				</select>
				<span class="help-block">这个导航链接将显示在微站指定页面. 注意: 此项添加后不能修改.</span>
				<?php  } else { ?>
				<span class="help-block">这个导航链接将显示在微站<?php  echo $positions[$item['position']]['title'];?>页面</span>
				<?php  } ?>
			</td>
		</tr>
		<tr>
			<th><label for="">名称</label></th>
			<td>
				<input type="text" class="span4" name="title" id="name" value="<?php  echo $item['name'];?>" />
			</td>
		</tr>
		<tr>
			<th><label for="">链接</label></th>
			<td>
				<input class="span5" type="text" name="url" id="url" value="<?php  echo $item['url'];?>" />
				<span class="help-block">指定这个导航的链接目标</span>
				<div class="alert-block" style="padding:3px 0;"><strong class="text-error">使用微站链接:</strong>
				<?php  if(is_array($systemmenus)) { foreach($systemmenus as $menu) { ?>
					<a href="javascript:;" class="icon-external-link" onclick="$('#url').val('<?php  echo $menu['url'];?>');"><?php  echo $menu['title'];?></a> &nbsp;
				<?php  } } ?>
				</div>
			</td>
		</tr>
		<tr>
			<th><label for="">状态</label></th>
			<td>
				<label for="status_1" class="radio inline"><input autocomplete="off" type="radio" name="status" id="status_1" value="1" <?php  if($item['status'] == 1 || empty($item)) { ?> checked="checked"<?php  } ?> /> 显示</label>
				<label for="status_0" class="radio inline"><input autocomplete="off" type="radio" name="status" id="status_0" value="0" <?php  if(!empty($item) && $item['status'] == 0) { ?> checked="checked"<?php  } ?> /> 隐藏</label>
				<span class="help-block">设置导航菜单的显示状态</span>
			</td>
		</tr>
		<tr>
			<th><label for="">排序</label></th>
			<td>
				<input type="text" class="span2" name="displayorder" value="<?php  echo $item['displayorder'];?>" />
				<span class="help-block">导航排序，越大越靠前</span>
			</td>
		</tr>
	</table>
	<h4>导航样式</h4>
	<table class="tb">
		<tr>
			<th>系统图标</th>
			<td>
				<div class="input-append" style="display:block; margin-top:5px;">
					<input class="span3" type="text" name="icon[icon]" id="icon" value="<?php  echo $item['css']['icon']['icon'];?>" placeholder=""><button class="btn" onclick="w = ajaxshow('<?php  echo create_url('site/icon')?>', '图标列表', {width : 800});return false;">选择图标</button>
				</div>
				<span class="help-block">导航的背景图标，微擎系统提供丰富的图标ICON。</span>
			</td>
		</tr>
		<tr>
			<th><label for="">图标颜色</label></th>
			<td>
				<input type="text" class="span3" id="iconcolor" name="icon[color]" value="<?php  echo $item['css']['icon']['color'];?>" />
				<input class="colorpicker" target="iconcolor" value="<?php  echo $item['css']['icon']['color'];?>" />
				<span class="help-block">图标颜色，上传图标时此设置项无效</span>
			</td>
		</tr>
		<tr>
			<th>图标大小</th>
			<td>
				<input class="span2" type="text" name="icon[size]" id="icon" value="<?php  if($item['css']['icon']['size']) { ?><?php  echo $item['css']['icon']['size'];?><?php  } else { ?>35<?php  } ?>"><span class="help-inline">PX</span>
				<span class="help-block">图标的尺寸大小，单位为像素，上传图标时此设置项无效</span>
			</td>
		</tr>
		<tr>
			<th><label for="">上传图标</label></th>
			<td>
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-preview thumbnail" style="width: 50px; height: 50px;"><?php  if($item['fileicon']) { ?><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['fileicon'];?>" width="50" /><?php  } ?></div>
					<div>
						<span class="btn btn-file"><span class="fileupload-new">选择图片</span><span class="fileupload-exists">更改</span><input name="icon" type="file" /></span>
						<?php  if($item['fileicon']) { ?><button type="submit" name="fileupload-delete" value="<?php  echo $item['fileicon'];?>" class="btn fileupload-new">删除</button><?php  } ?>
						<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">移除</a>
					</div>
				</div>
				<input type="hidden" name="icon_old" value="<?php  echo $item['fileicon'];?>" />
				<span class="help-block">自定义上传图标图片，“系统图标”优先于此项</span>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
				<input type="submit" class="btn btn-primary" name="submit" value="提交" />
			</td>
		</tr>
	</table>
</div>
</form>
<script type="text/javascript" src="./resource/script/colorpicker/spectrum.js"></script>
<link type="text/css" rel="stylesheet" href="./resource/script/colorpicker/spectrum.css" />
<link type="text/css" rel="stylesheet" href="./resource/script/kindeditor/themes/default/default.css" />
<script type="text/javascript">
	$(function(){
		colorpicker();
	});
</script>
<?php  } else if($do == 'display') { ?>
<script type="text/javascript">
$(function(){
	$('div.make-switch').on('switch-change', function (e, data) {
		var dat = data.el.attr('data');
		var ret = data.value;
		$.post(location.href, {dat: dat, ret: ret}, function(resp){
			if(resp == 'success') {
				window.setTimeout(function(){location.href = location.href;}, 300);
			}
		});
	});
});
</script>
<style>
.table td input{margin-bottom:0;}
</style>
<form action="" method="post">
<div class="main">
	<div class="stat">
		<?php  if($position == '1') { ?>
		<h4 class="sub-title">选择要显示在微站首页的信息 <small>这里提供了<?php  if($mod) { ?>"<?php  echo $mod['title'];?>"功能中<?php  } ?>能够显示在微站首页的信息, 你可以选择性的自定义或显示隐藏</small></h4>
		<?php  } ?>
		<?php  if($position == '2') { ?>
		<h4 class="sub-title">选择要显示在微站个人中心的信息 <small>这里提供了<?php  if($mod) { ?>"<?php  echo $mod['title'];?>"功能中<?php  } ?>能够显示在微站个人中心的信息, 你可以选择性的自定义或显示隐藏</small></h4>
		<?php  } ?>
		<?php  if($position == '3') { ?>
		<h4 class="sub-title">选择要显示在微站快捷选项的信息 <small>这里提供了<?php  if($mod) { ?>"<?php  echo $mod['title'];?>"功能中<?php  } ?>能够显示在微站快捷选项的信息(需要微站模板支持), 你可以选择性的自定义或显示隐藏</small></h4>
		<?php  } ?>
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:45px;">图标</th>
					<th style="width:250px;">标题</th>
					<th>链接</th>
					<th style="width:200px;">来源</th>
					<th style="width:60px;">排序</th>
					<th style="width:200px;">是否在微站上显示此导航?</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($ds)) { foreach($ds as $item) { ?>
				<tr>
					<td><?php  if($item['css']['icon']['icon'] && empty($item['icon'])) { ?><i class="<?php  echo $item['css']['icon']['icon'];?> icon-2x"></i><?php  } else { ?><?php  if($item['icon']) { ?><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['icon'];?>" width="30" /><?php  } ?><?php  } ?></td>
					<td><?php  if($item['remove']) { ?><input type="text" class="span3" name="title[<?php  echo $item['id'];?>]" value="<?php  echo $item['title'];?>" /><?php  } else { ?><?php  echo $item['title'];?><?php  } ?></td>
					<td>
						<input class="span5" type="text" value="<?php  echo $item['url'];?>">
					</td>
					<td><?php  if($item['module']) { ?>"<?php  echo $_W['modules'][$item['module']]['title'];?>" <?php  } ?><?php  echo $froms[$item['from']];?></td>
					<td><?php  if($item['remove']) { ?><input type="text" class="span1" name="displayorder[<?php  echo $item['id'];?>]" value="<?php  echo $item['displayorder'];?>" /><?php  } else { ?>无效<?php  } ?></td>
					<td style="text-align:right;">
						<a href="<?php  echo create_url('site/nav/post', array('id' => $item['id'], '_p' => $position));?>" class="btn edit btn-small"<?php  if(!$item['checked']) { ?> style="display:none;"<?php  } ?> title="编辑"><i class="icon-edit"></i></a>
						<a href="<?php  echo create_url('site/nav/delete', array('id' => $item['id'], 'name' => $modulename));?>" class="btn remove btn-small"<?php  if(!$item['remove']) { ?> style="display:none;"<?php  } ?> title="删除"><i class="icon-remove"></i></a>
						<div class="make-switch switch-small" data-on-label="是" data-off-label="否">
							<input type="checkbox" value="20"<?php  if($item['checked']) { ?> checked="checked"<?php  } ?> data="<?php  echo base64_encode(json_encode($item));?>" />
						</div>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
			<tr>
				<td colspan="6">
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
					<input type="hidden" name="do" value="saves" />
					<input type="submit" class="btn btn-primary" name="submit" value="提交" />
				</td>
			</tr>
		</table>
	</div>
</div>
</form>
<?php  } ?>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
