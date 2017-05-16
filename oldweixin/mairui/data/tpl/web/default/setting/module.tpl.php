<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<script type="text/javascript">
$(function() {
	$('.module').delegate('.media-description-button', 'click', function(){ //控制模块详细信息
		$(this).parents('.item').find('.media-description').toggle();
		return false;
	});
});
</script>
<style type="text/css">
small a{color:#999;}
.module{padding:15px;}
</style>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo create_url('setting/module');?>">模块列表</a></li>
	<li><a href="<?php  echo create_url('setting/designer');?>">设计新模块</a></li>
	
</ul>
<div class="main">
	<div class="module form-horizontal">
		<div class="alert alert-info">已安装的模块</div>
		<?php  if(is_array($modules)) { foreach($modules as $row) { ?>
		<div class="item">
			<div class="media">
				<div class="pull-right" style="width:230px;">
					<div class="input-prepend">
					</div>
					<div class="module-set">
						<?php  if($row['version_error']) { ?>
						版本不兼容 <a href="<?php  echo create_url('setting/module/convert', array('id' => strtolower($row['name'])))?>" style="color:red;">转换版本</a>
						<?php  } else { ?>
						<?php  if($row['upgrade']) { ?><a href="<?php  echo create_url('setting/module/upgrade', array('id' => $row['name']))?>" style="color:red;">更新</a><?php  } ?>
						<?php  if(!$row['issystem']) { ?><a href="<?php  echo create_url('setting/module/uninstall', array('id' => $row['name']))?>">卸载</a><?php  } ?>
						<?php  } ?>
						<a href="<?php  echo create_url('setting/module/permission', array('id' => $row['name']))?>">访问权限</a>
						&nbsp;
					</div>
				</div>
				<a class="pull-left" href="#">
					<img class="media-object" src="./source/modules/<?php  echo $row['name'];?>/icon.jpg" onerror="this.src='./resource/image/module-nopic-small.jpg'">
				</a>
				<div class="media-body">
					<h4 class="media-heading"><?php  echo $row['title'];?><small>（标识：<?php  echo $row['name'];?>&nbsp;&nbsp;&nbsp;作者：<?php  echo $row['author'];?>）</small> <?php  if($row['upgrade']) { ?><em style="color:red;">New</em><?php  } ?></h4>
					<span><?php  echo $row['ability'];?>&nbsp;<a href="#" class="media-description-button">详细介绍</a></span >
				</div>
			</div>
			<div class="media-description">
				<b>功能介绍：</b>
				<span><?php  echo $row['description'];?></span>
			</div>
		</div>
		<?php  } } ?>
		<div class="alert alert-info">未安装的模块</div>
		<?php  if(is_array($uninstallModules)) { foreach($uninstallModules as $row) { ?>
		<div class="item">
			<div class="media">
				<div class="pull-right" style="width:230px;">
					<div class="module-set">
						<?php  if($row['version_error']) { ?>
						版本不兼容 <a href="<?php  echo create_url('setting/module/convert', array('id' => strtolower($row['name'])))?>" style="color:red;">转换版本</a>
						<?php  } else { ?>
						<a href="<?php  echo create_url('setting/module/install', array('id' => strtolower($row['name'])))?>">安装</a>
						<?php  } ?>
						<a href="<?php  echo create_url('setting/module/permission', array('id' => strtolower($row['name'])))?>">访问权限</a>
						&nbsp;
					</div>
				</div>
				<a class="pull-left" href="<?php  echo $row['url'];?>" target="_blank">
					<img class="media-object gray" src="./source/modules/<?php  echo strtolower($row['name']);?>/icon.jpg" onerror="this.src='./resource/image/module-nopic-small.jpg'">
				</a>
				<div class="media-body">
					<h4 class="media-heading"><?php  echo $row['title'];?><small>（标识：<?php  echo $row['name'];?>&nbsp;&nbsp;&nbsp;作者：<?php  echo $row['author'];?>）</small></h4>
					<span><?php  echo $row['ability'];?>&nbsp;<a href="#" class="media-description-button">详细介绍</a></span >
				</div>
			</div>
			<div class="media-description">
				<b>功能介绍：</b>
				<span>
					<?php  echo $row['description'];?>
				</span>
			</div>
		</div>
		<?php  } } ?>
	</div>
</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
