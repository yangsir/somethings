<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('common/header', TEMPLATE_INCLUDEPATH);?>
<style>
.table td span{display:inline-block;}
.table td input{margin-bottom:0;}
</style>
<script type="text/javascript">
$(function(){
	$('div.make-switch').on('switch-change', function (e, data) {
		var rids = [];
		$('div.make-switch :checkbox:checked').each(function(){
			rids.push($(this).val());
		});
		$.post(location.href, {'rids': rids.toString()}, function(dat){
		});
	});
});
</script>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo create_url('site/module/switch', array('name' => 'userapi'));?>">常用服务接入</a></li>
</ul>
<form action="" method="post">
<div class="main">
	<div style="padding:15px;">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="width:200px;">服务名称</th>
					<th style="min-width:260px;">功能说明</th>
					<th style="width:60px;">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($ds)) { foreach($ds as $row) { ?>
				<tr>
					<td><?php  echo $row['title'];?></td>
					<td><?php  echo $row['description'];?></td>
					<td>
						<div class="make-switch" data-on-label="启用" data-off-label="停用">
							<input type="checkbox" value="<?php  echo $row['rid'];?>"<?php  echo $row['switch'];?> />
						</div>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
		</table>
	</div>
</div>
</form>
<?php  include $this->template('common/footer', TEMPLATE_INCLUDEPATH);?>
