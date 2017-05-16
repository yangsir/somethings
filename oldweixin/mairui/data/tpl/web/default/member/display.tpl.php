<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<script type="text/javascript">
	var u ={};
	u.deny = function(uid, status){
		var uid = parseInt(uid);
		if(isNaN(uid)) {
			return;
		}
		if(!confirm('确认要禁用/解禁此用户吗? ')) {
			return;
		}
		$.post('<?php  echo create_url('member/permission');?>', {'do': 'deny', uid: uid, status: status}, function(dat){
			if(dat == 'success') {
				location.href = location.href;
			} else {
				message('操作失败, 请稍后重试. ' + dat);
			}
		});
	};
	u.del = function(uid){
		var uid = parseInt(uid);
		if(isNaN(uid)) {
			return;
		}
		if(!confirm('确认要删除此用户吗? ')) {
			return;
		}
		$.post('<?php  echo create_url('member/edit');?>', {'do': 'delete', uid: uid}, function(dat){
			if(dat == 'success') {
				location.href = location.href;
			} else {
				message('操作失败, 请稍后重试. ' + dat);
			}
		});
	};
</script>
<ul class="nav nav-tabs">
	<li><a href="<?php  echo create_url('member/create');?>">添加用户</a></li>
	<li <?php  if(empty($_GPC['status'])) { ?>class="active"<?php  } ?>><a href="<?php  echo create_url('member/display');?>">用户列表</a></li>
	<li <?php  if($_GPC['status'] == -1) { ?>class="active"<?php  } ?>><a href="<?php  echo create_url('member/display', array('status' => -1));?>">审核用户</a></li>
</ul>
<div class="rule">
	<table class="table table-hover">
		<thead class="navbar-inner">
			<tr>
				<th style="max-width:150px;">用户名</th>
				<th style="width:60px;">身份</th>
				<th style="width:80px;">状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php  if(is_array($members)) { foreach($members as $m) { ?>
			<tr>
				<td><?php  if(!$m['founder']) { ?><a href="./member.php?act=edit&uid=<?php  echo $m['uid'];?>"><?php  echo $m['username'];?></a><?php  } else { ?><?php  echo $m['username'];?><?php  } ?></td>
				<td><?php echo $m['founder'] ? '<span class="label label-success">管理员</span>' : '';?></td>
				<td><?php echo $m['status'] == '-1' ? '<span class="label label-important">被禁止</span>' : '';?></td>
				<td>
					<?php  if(!$m['founder']) { ?>
					<div><a href="<?php  echo create_url('member/edit', array('uid' => $m['uid']))?>">编辑</a>&nbsp;&nbsp;<a href="<?php  echo create_url('member/permission', array('uid' => $m['uid']))?>">设置操作权限</a>&nbsp;&nbsp;<a href="javascript:;" onclick="u.deny('<?php  echo $m['uid'];?>', '<?php echo $m['status'] == '-1' ? '0' : '-1';?>');"><?php echo $m['status'] == '-1' ? '启用' : '禁止';?>用户</a>&nbsp;&nbsp;<a href="javascript:;" onclick="u.del('<?php  echo $m['uid'];?>');">删除用户</a></div>
					<?php  } ?>
				</td>
			</tr>
			<?php  } } ?>
		</tbody>
	</table>
	<?php  echo $pager;?>
</div>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
