<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
	<script type="text/javascript" src="./resource/script/jquery.zclip.min.js"></script>
	<ul class="nav nav-tabs">
		<!--ul class="pull-right unstyled">
			<li><a href="<?php  echo create_url('account/post')?>">添加公众号</a></li>
			<li class="active"><a href="<?php  echo create_url('account/display')?>">管理公众号</a></li>
		</ul-->
		<li><a href="<?php  echo create_url('account/post')?>"><i class="icon-plus"></i> 添加公众号</a></li>
		<li <?php  if(empty($_GPC['type']) || $_GPC['type'] == 1) { ?>class="active"<?php  } ?>><a href="<?php  echo create_url('account/display', array('type' => 1))?>">微信公众号</a></li>
		
	</ul>
	<div class="main">
		<div class="account">
			<?php  if(is_array($list)) { foreach($list as $row) { ?>
			<div class="navbar-inner thead">
				<h4>
					<span class="pull-right"><a onclick="return confirm('删除帐号将同时删除全部规则及回复，确认吗？');return false;" href="<?php  echo create_url('account/delete', array('id' => $row['weid']))?>">删除</a><a href="<?php  echo create_url('account/post', array('id' => $row['weid']))?>">编辑</a><a href="<?php  echo create_url('account/switch', array('id' => $row['weid']))?>">切换</a></span>
					<span class="pull-left"><?php  echo $row['name'];?> <small>（微信号：<?php  echo $row['account'];?>）（所属用户：<?php  if(in_array($row['uid'], $founder)) { ?><span>管理员</span><?php  } else { ?><span><?php  echo $users[$row['uid']]['username'];?></span><?php  } ?>）</small></span>
				</h4>
			</div>
			<div class="tbody">
				<div class="con">
					<div class="name pull-left">API地址</div>
					<div class="input-append pull-left" id="api_<?php  echo $row['weid'];?>">
						<input id="" type="text" value="<?php  echo $_W['siteroot'];?>api.php?hash=<?php  echo $row['hash'];?>">
						<button class="btn" type="button">复制</button>
					</div>
				</div>
				<div class="con">
					<div class="name pull-left">Token</div>
					<div class="input-append pull-left" id="token_<?php  echo $row['weid'];?>">
						<input id="" type="text" value="<?php  echo $row['token'];?>">
						<button class="btn" type="button">复制</button>
					</div>
				</div>
			</div>
			<script>
				$(function() {
					$("#api_<?php  echo $row['weid'];?> button").zclip({
						path:'./resource/script/ZeroClipboard.swf',
						copy:$('#api_<?php  echo $row['weid'];?> input').val()
					});
					$("#token_<?php  echo $row['weid'];?> button").zclip({
						path:'./resource/script/ZeroClipboard.swf',
						copy:$('#token_<?php  echo $row['weid'];?> input').val()
					});
				});
			</script>
			<?php  } } ?>
		</div>
		<?php  echo $pager;?>
	</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
