<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('common/header', TEMPLATE_INCLUDEPATH);?>
<?php  if($operation == 'display') { ?>
<div class="main">
	<div class="search">
		<form action="site.php" method="get">
		<input type="hidden" name="act" value="entry" />
		<input type="hidden" name="eid" value="<?php  echo $_GPC['eid']?>" />
		<table class="table table-bordered tb">
			<tbody>
				<tr>
					<th>关键字</th>
					<td>
						<input class="span6" name="keyword" id="" type="text" value="<?php  echo $_GPC['keyword'];?>">
					</td>
				</tr>
				<tr>
					<th>状态</th>
					<td>
						<select name="status">
							<option value="1" <?php  if(empty($_GPC['status']) || $_GPC['status']=='1') { ?> selected<?php  } ?>>启用</option>
							<option value="0" <?php  if($_GPC['status']=='0') { ?> selected<?php  } ?>>禁用</option>
						</select>
					</td>
				</tr>
				<tr>
				 <tr class="search-submit">
					<td colspan="2"><button class="btn pull-right span2"><i class="icon-search icon-large"></i> 搜索</button></td>
				 </tr>
			</tbody>
		</table>
		</form>
	</div>
	<div style="padding:15px;">
		<table class="table table-hover">
			<thead class="navbar-inner">
				<tr>
					<th style="min-width:150px;">卡号</th>
					<th style="width:100px;">姓名</th>
					<th style="width:80px;">积分</th>
					<th style="width:80px;">余额</th>
					<th style="min-width:120px;">领卡时间</th>
					<th style="">状态</th>
					<th style="text-align:right; min-width:60px;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<tr>
					<td><?php  echo $item['cardsn'];?></td>
					<td><?php  echo $fans[$item['from_user']]['realname'];?></td>
					<td><?php  echo $item['credit1'];?></td>
					<td><?php  echo $item['credit2'];?></td>
					<td><?php  echo date('Y-m-d H:i:s', $item['createtime'])?></td>
					<td><?php  if($item['status']) { ?><span class="label label-success">可用</span><?php  } else { ?><span class="label label-error">禁用</span><?php  } ?></td>
					<td style="text-align:right;">
						<a href="<?php  echo create_url('site/module/member', array('name' => 'member', 'id' => $item['id'], 'op' => 'credit'))?>" class="btn btn-small" title="充值"><i class="icon-money"></i></a>
						<a href="<?php  echo create_url('site/module/profile', array('name' => 'fans', 'from_user' => $item['from_user']))?>" class="btn btn-small" title="编辑"><i class="icon-edit"></i></a>
						<!--a href="<?php  echo create_url('index/module/member', array('name' => 'member', 'id' => $item['id'], 'op' => 'delete'))?>" onclick="return confirm('此操作不可恢复，确认删除？');return false;">删除</a-->
						<a href="<?php  echo create_url('site/module/member', array('name' => 'member', 'id' => $item['id'], 'op' => 'status'))?>" class="btn btn-small" title="<?php  if($item['status']) { ?>禁用<?php  } else { ?>启用<?php  } ?>"><i class="<?php  if($item['status']) { ?>icon-eye-open<?php  } else { ?>icon-eye-close<?php  } ?>"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
			<tr>
				<td colspan="3">
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
					<input type="submit" class="btn btn-primary" name="submit" value="提交" />
				</td>
			</tr>
		</table>
		<?php  echo $pager;?>
	</div>
</div>
<?php  } else if($operation == 'credit') { ?>
<div class="main">
<form action="" class="form-horizontal form" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php  echo $id;?>" />
	<h4>修改用户积分及余额</h4>
	<table class="tb">
		<tr>
			<th><label for="">积分</label></th>
			<td><input type="text" name="credit1" value="<?php  echo $member['credit1'];?>" class="span5"></td>
		</tr>
		<tr>
			<th><label for="">余额</label></th>
			<td><input type="text" name="credit2" value="<?php  echo $member['credit2'];?>" class="span5"></td>
		</tr>
		<tr>
			<th></th>
			<td>
				<button type="submit" class="btn btn-primary span3" name="submit" value="提交">提交</button>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</td>
		</tr>
	</table>
</div>
<?php  } ?>