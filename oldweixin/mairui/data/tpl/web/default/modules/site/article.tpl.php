<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('common/header', TEMPLATE_INCLUDEPATH);?>
<ul class="nav nav-tabs">
	<li <?php  if($foo == 'post') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('article', array('foo' => 'post'));?>">添加文章</a></li>
	<li <?php  if($foo == 'display') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('article', array('foo' => 'display'));?>">文章列表</a></li>
</ul>
<style>
.table td span{display:inline-block;margin-top:4px;}
.table td input{margin-bottom:0;}
</style>
<?php  if($foo == 'display') { ?>
<div class="main">
	<div class="search">
		<form action="site.php" method="get">
		<input type="hidden" name="act" value="module" />
		<input type="hidden" name="do" value="article" />
		<input type="hidden" name="name" value="site" />
		<table class="table table-bordered tb">
			<tbody>
				<tr>
					<th>关键字</th>
					<td>
						<input class="span6" name="keyword" id="" type="text" value="<?php  echo $_GPC['keyword'];?>">
					</td>
				</tr>
				<tr>
					<th>分类</th>
					<td>
						<select class="span3" style="margin-right:15px;" name="cate_1" onchange="fetchChildCategory(this.options[this.selectedIndex].value)">
							<option value="0">请选择一级分类</option>
							<?php  if(is_array($category)) { foreach($category as $row) { ?>
							<?php  if($row['parentid'] == 0) { ?>
							<option value="<?php  echo $row['id'];?>" <?php  if($row['id'] == $_GPC['cate_1']) { ?> selected="selected"<?php  } ?>><?php  echo $row['name'];?></option>
							<?php  } ?>
							<?php  } } ?>
						</select>
						<select class="span3" id="cate_2" name="cate_2">
							<option value="0">请选择二级分类</option>
							<?php  if(!empty($_GPC['cate_1']) && !empty($children[$_GPC['cate_1']])) { ?>
							<?php  if(is_array($children[$_GPC['cate_1']])) { foreach($children[$_GPC['cate_1']] as $row) { ?>
							<option value="<?php  echo $row['0'];?>" <?php  if($row['0'] == $_GPC['cate_2']) { ?> selected="selected"<?php  } ?>><?php  echo $row['1'];?></option>
							<?php  } } ?>
							<?php  } ?>
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
		<table class="table table-hover table-striped">
			<thead class="navbar-inner">
				<tr>
					<th>标题</th>
					<th style="width:180px;">属性</th>
					<th style="width:85px; text-align:right;">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php  if(is_array($list)) { foreach($list as $item) { ?>
				<tr>
					<td>
						<span class="cate"><?php  if(!empty($item['pcate'])) { ?><span class="text-error">[<?php  echo site_category_name_search($item['pcate']);?>]</span><?php  } ?><?php  if(!empty($item['ccate'])) { ?><span class="text-info">[<?php  echo site_category_name_search($item['ccate']);?>]</span><?php  } ?></span>
						<a href="<?php  echo $this->createWebUrl('article', array('foo' => 'post', 'id' => $item['id']))?>" style="color:#333;"><?php  echo $item['title'];?></a>
					</td>
					<td>
						<?php  if($item['type'] && strstr($item['type'], 'h,')) { ?><span class="label label-success">头条</span><?php  } ?>
						<?php  if($item['type'] && strstr($item['type'], 'c,')) { ?><span class="label label-success">推荐</span><?php  } ?>
						<?php  if($item['type'] && strstr($item['type'], 'f,')) { ?><span class="label label-success">幻灯</span><?php  } ?>
						<?php  if($item['type'] && strstr($item['type'], 's,')) { ?><span class="label label-success">滚动</span><?php  } ?>
					</td>
					<td style="text-align:right;">
						<a href="<?php  echo $this->createWebUrl('article', array('foo' => 'post', 'id' => $item['id']))?>" title="编辑" class="btn btn-mini"><i class="icon-edit"></i></a>
						<a onclick="return confirm('此操作不可恢复，确认吗？'); return false;" href="<?php  echo $this->createWebUrl('article', array('foo' => 'delete', 'id' => $item['id']))?>" title="删除" class="btn btn-mini"><i class="icon-remove"></i></a>
					</td>
				</tr>
				<?php  } } ?>
			</tbody>
			<!--tr>
				<td></td>
				<td colspan="3">
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
					<input type="submit" class="btn btn-primary" name="submit" value="提交" />
				</td>
			</tr-->
		</table>
		<?php  echo $pager;?>
	</div>
</div>
<script type="text/javascript">
<!--
	var category = <?php  echo json_encode($children)?>;
//-->
</script>
<?php  } else if($foo == 'post') { ?>
<div class="main">
	<form class="form-horizontal form" action="" method="post" enctype="multipart/form-data" onsubmit="return formcheck(this)">
		<input type="hidden" name="id" value="<?php  echo $item['id'];?>">
		<h4>文章管理</h4>
		<table class="tb">
			<?php  if(!empty($item)) { ?>
			<tr>
				<th><label for="">访问地址</label></th>
				<td>
					<a href="<?php  echo $this->createMobileUrl('detail', array('id' => $item['id'], 'weid' => $_W['weid']))?>" target="_blank"><?php  echo $this->createMobileUrl('detail', array('id' => $item['id'], 'weid' => $_W['weid']))?></a>
					<span class="help-block">您可以根据此地址，添加回复规则，设置访问。</span>
				</td>
			</tr>
			<?php  } ?>
			<tr>
				<th><label for="">标题</label></th>
				<td>
					<input type="text" class="span6" placeholder="" name="title" value="<?php  echo $item['title'];?>">
				</td>
			</tr>
			<tr>
				<th><label for="">自定义属性</label></th>
				<td>
					<label class="checkbox inline"><input type="checkbox" name="option[]" value="h" <?php  if($item['type'] && in_array('h', $item['type'])) { ?> checked<?php  } ?>> 头条[h]</label>
					<label class="checkbox inline"><input type="checkbox" name="option[]" value="c" <?php  if($item['type'] && in_array('c', $item['type'])) { ?> checked<?php  } ?>> 推荐[c]</label>
					<label class="checkbox inline"><input type="checkbox" name="option[]" value="f" <?php  if($item['type'] && in_array('f', $item['type'])) { ?> checked<?php  } ?>> 幻灯[f]</label>
					<label class="checkbox inline"><input type="checkbox" name="option[]" value="s" <?php  if($item['type'] && in_array('s', $item['type'])) { ?> checked<?php  } ?>> 滚动[s]</label>
				</td>
			</tr>
			<tr>
				<th><label for="">文章来源</label></th>
				<td>
					<input type="text" class="span3" placeholder="" name="source" value="<?php  echo $item['source'];?>">
					<label for="writer" class="checkbox inline" style="margin-right:15px;">文章作者</label>
					<input type="text" class="span2" id="writer" name="author" value="<?php  echo $item['author'];?>">
				</td>
			</tr>
			<tr>
				<th><label for="">缩略图</label></th>
				<td>
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;"><?php  if($item['thumb']) { ?><img src="<?php  echo $_W['attachurl'];?><?php  echo $item['thumb'];?>" width="200" /><?php  } ?></div>
						<div>
							<span class="btn btn-file"><span class="fileupload-new">选择图片</span><span class="fileupload-exists">更改</span><input name="thumb" type="file" /></span>
							<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">移除</a>
							<?php  if($item['thumb']) { ?><button type="submit" name="fileupload-delete" value="<?php  echo $item['thumb'];?>" class="btn fileupload-new">删除</button><?php  } ?>
						</div>
					</div>
					<span class="help-block"></span>
				</td>
			</tr>
			<tr>
				<th><label for="">规则类别</label></th>
				<td>
					<select class="span3" style="margin-right:15px;" name="cate_1" onchange="fetchChildCategory(this.options[this.selectedIndex].value);buildModuleForm($(this.options[this.selectedIndex]).attr('module'));">
						<option value="0">请选择一级栏目</option>
						<?php  if(is_array($category)) { foreach($category as $row) { ?>
						<?php  if($row['parentid'] == 0) { ?>
						<option value="<?php  echo $row['id'];?>" module="<?php  echo $row['module'];?>" <?php  if($row['id'] == $item['pcate'] || $row['id'] == $_GPC['pcate']) { ?> selected="selected"<?php  } ?>><?php  echo $row['name'];?></option>
						<?php  } ?>
						<?php  } } ?>
					</select>
					<select class="span3" name="cate_2" id="cate_2" onchange="buildModuleForm($(this.options[this.selectedIndex]).attr('module'));"><option value="0">请选择二级栏目</option>
					<?php  if($item['ccate'] || $item['pcate']) { ?>
					<?php  if(is_array($children[$item['pcate']])) { foreach($children[$item['pcate']] as $row) { ?>
						<option value="<?php  echo $row['0'];?>" module="<?php  echo $row['2'];?>" <?php  if($row['0'] == $item['ccate']) { ?> selected="selected"<?php  } ?>><?php  echo $row['1'];?></option>
					<?php  } } ?>
					<?php  } ?>
					</select>
					<input type="hidden" name="module" id="module" value="">
				</td>
			</tr>
			<tr>
				<th></th>
				<td><label class="checkbox inline"><input type="checkbox" name="autolitpic" value="1" checked="true">提取内容的第一个图片为缩略图</label></td>
			</tr>
			<tr>
				<th>内容</th>
				<td>
					<textarea style="height:400px; width:100%;" class="span7 richtext-clone" name="content" cols="70" id="reply-add-text"><?php  echo $item['content'];?></textarea>
				</td>
			</tr>
		</table>

		<table class="tb">
			<tr>
				<th></th>
				<td>
					<button type="submit" class="btn btn-primary span3" name="submit" value="提交">提交</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
<!--
	var category = <?php  echo json_encode($children)?>;
	kindeditor($('.richtext-clone'));
//-->
</script>
<?php  } ?>
<?php  include $this->template('common/footer', TEMPLATE_INCLUDEPATH);?>
