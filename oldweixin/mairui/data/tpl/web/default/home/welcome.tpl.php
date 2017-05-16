<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo create_url('index/welcome/' . $do);?>">概况</a></li>
	<?php  if($_W['isfounder']) { ?><li><a href="<?php  echo create_url('index/sysinfo/' . $do);?>">系统信息</a></li><?php  } ?>
</ul>
<div class="main">
	<div style="padding:15px 15px 0 15px;">
		<?php  if($do == 'global') { ?>
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:100px;">名称</th>
					<th style="width:80px;">类型</th>
					<th style="width:150px;">粉丝</th>
					<th style="width:150px;">规则</th>
					<th style="width:150px;">请求数</th>
					<th style="width:300px;">功能</th>
					<th style="min-width:51px;">操作</th>
				</tr>
			</thead>
			<?php  if(is_array($wechats)) { foreach($wechats as $item) { ?>
			<tr>
				<td><?php  echo $item['name'];?></td>
				<td><?php  if(!empty($item['key']) && !empty($item['secret'])) { ?><span class="label label-info">服务号<?php  } else { ?><span class="label label-success">订阅号<?php  } ?></span></td>
				<td>
					<p>总粉丝：<?php  echo $item['fans']['total'];?><p>
					<p>当日增加：<?php  echo $item['fans']['todayjoin'];?><p>
					<p>当日流失：<?php  echo $item['fans']['todayquit'];?><p>
				</td>
				<td>
					<p>基本文字：<?php  echo $item['rule']['basic'];?><p>
					<p>图文混合：<?php  echo $item['rule']['news'];?><p>
					<p>基本语音：<?php  echo $item['rule']['music'];?><p>
					<p>其它：<?php  echo $item['rule']['other'];?><p>
				</td>
				<td>
					<p>总请求：<?php  echo $item['response']['total'];?><p>
					<p>当月请求：<?php  echo $item['response']['month'];?><p>
					<p>当日请求：<?php  echo $item['response']['today'];?><p>
				</td>
				<td class="funcmenus" weid="<?php  echo $item['weid'];?>">
					<p><a href="<?php  echo create_url('rule')?>">规则管理</a>&nbsp;|&nbsp;<a href="<?php  echo create_url('setting/category')?>">分类管理</a>&nbsp;|&nbsp;<a href="<?php  echo create_url('member/module')?>">模块管理</a></p>
					<p><a href="<?php  echo create_url('rule/post')?>">添加规则</a>&nbsp;|&nbsp;<a href="<?php  echo create_url('menu')?>">自定义菜单设置</a>&nbsp;|&nbsp;<a href="<?php  echo create_url('rule/system')?>">系统回复设置</a></p>
					<p><a href="<?php  echo create_url('site/style')?>">风格管理</a>&nbsp;|&nbsp;<a href="<?php  echo create_url('site/nav')?>">导航管理</a></p>
				</td>
				<td>
					<p><a href="<?php  echo create_url('account/post', array('id' => $item['weid']))?>">编辑</a>&nbsp;|&nbsp;<a href="<?php  echo create_url('account/switch', array('id' => $item['weid']))?>">切换</a></p>
				</td>
			</tr>
			<?php  } } ?>
		</table>
		<script type="text/javascript">
		<!--
			$('.funcmenus').each(function(){
				var weid = $(this).attr('weid');
				$(this).find('a').each(function(){
					$(this).click(function(){
						var url = $(this).attr('href');
						ajaxopen('account.php?act=switch&id='+weid, function(s){
							location.href = url;
							$('#current-account', window.parent.document).html(s);
							return false;
						});
						return false;
					});
				});
			});
		//-->
		</script>
		<?php  } else { ?>
		<div class="welcome">
			<h4><i class="icon-user"></i> 公众号信息</h4>
			<div class="item">
				<img class="img-polaroid pull-left" style="margin-right:20px" src="<?php  echo $_W['attachurl'];?>/headimg_<?php  echo $_W['weid'];?>.jpg?weid=<?php  echo $_W['account']['weid'];?>" width="85" onerror="$(this).remove();" />
				<div class="pull-left">
					<p><b style="font-size:16px;"><?php  echo $_W['account']['name'];?></b></p>
					<p><span style="width:80px;display:inline-block;">接口地址：</span><?php  echo $_W['siteroot'];?>api.php?hash=<?php  echo $_W['account']['hash'];?></p>
					<p><span style="width:80px;display:inline-block;">Token：</span><?php  echo $_W['account']['token'];?></p>
				</div>
			</div>
			<h4><i class="icon-plane"></i> 快捷操作</h4>
			<div class="item fast-set">
				<a href="<?php  echo create_url('site/module/switch', array('name' => 'userapi'))?>"><i class="icon-cogs"></i><span>自定义接口</span></a>
				<?php  if(is_array($shortcuts)) { foreach($shortcuts as $shortcut) { ?>
				<a href="<?php  echo $shortcut['link'];?>"><img class="gray" src="<?php  echo $shortcut['image'];?>" style="width:40px;height:40px;margin-top:10px;" /><span><?php  echo $shortcut['title'];?></span></a>
				<?php  } } ?>
			</div>
		</div>
		<table class="table">
			<tr><th colspan="2" class="alert alert-info">可用模块</th></tr>
			<?php  if(is_array($modules)) { foreach($modules as $module) { ?>
			<tr>
				<th style="width:250px;">
					<p><?php  echo $_W['modules'][$module['name']]['title'];?></p>
					<?php  if(!empty($_W['modules'][$module['name']]['isrulefields'])) { ?>
					<p><a href="<?php  echo create_url('rule/post', array('module' => $module['name']))?>">添加规则</a></p>
					<?php  } ?>
				</th>
				<td>
					<?php  if(empty($_W['modules'][$module['name']]['isrulefields'])) { ?>
					此模块无规则
					<?php  } else { ?>
					<p>规则数：<?php  echo $module['rule'];?></p>
					<p>当日触发数：<?php  echo $module['response']['today'];?></p>
					<p>当月触发数：<?php  echo $module['response']['month'];?></p>
					<?php  } ?>
				</td>
			</tr>
			<?php  } } ?>
		</table>
		<?php  } ?>
		<?php  if($_W['isfounder']) { ?>
		<table class="table">
			<tr><th colspan="2" class="alert alert-info">迈瑞微信后台</th></tr>
<tr>
				<th>技术支持</th>
				<td>
				深圳市新风向科技有限公司 	
				</td>
			</tr>

			<tr>
				<th style="width:250px;">版权所有</th>
				<td>深圳迈瑞生物医疗电子股份有限公司</td>
			</tr>
		</table>
		<?php  } ?>
	
	</div>
</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
