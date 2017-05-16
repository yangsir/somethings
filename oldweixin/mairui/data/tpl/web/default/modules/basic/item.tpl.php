<?php defined('IN_IA') or exit('Access Denied');?><?php  if(empty($item)) { ?>
	<?php  $namesuffix = '-new[]';?>
	<?php  $itemid = '(itemid)';?>
<?php  } else { ?>
	<?php  $namesuffix = '['.$item['id'].']'?>
	<?php  $itemid = 'basic-item-' . $item['id'];?>
<?php  } ?>
<div id="show" class="span6 alert alert-info <?php  if(empty($item)) { ?>hide<?php  } ?>">
	<div class="content" id="content"><?php  echo $item['content'];?></div>
	<span class="pull-right">
		<?php  if(!empty($item)) { ?><a href="<?php  echo create_url('site/module/delete', array('name' => 'basic', 'id' => $item['id']))?>" onclick="return doDeleteItem('<?php  echo $itemid;?>', this.href)" style="margin-right:5px;">删除</a><?php  } else { ?><a onclick="doDeleteItem('<?php  echo $itemid;?>')" href="javascript:;" style="margin-right:5px;">删除</a><?php  } ?>
		<a style="margin-right:5px;" onclick="doEditItem('<?php  echo $itemid;?>')" id="<?php  echo $row['id'];?>" href="javascript:;">编辑</a>
	</span>
</div>
<div id="form" class="alert alert-block alert-new <?php  if(!empty($item)) { ?>hide<?php  } ?>">
	<a class="close" data-dismiss="alert">×</a>
	<h4 class="alert-heading">添加回复内容</h4>
	<table>
		<tr>
			<th>内容</th>
			<td>
				<textarea style="height:200px;" class="span7 basic-content-new" cols="70" id="basic-content-<?php  echo $itemid;?>" name="basic-content<?php  echo $namesuffix;?>" autocomplete="off"><?php  echo $item['content'];?></textarea>
				<span class="help-block">用户进行微信交谈时，对话内容完全等于上述关键字才会执行这条规则。<a class="iconEmotion" href="javascript:;" inputid="basic-content-<?php  echo $itemid;?>"><i class="icon-github-alt"></i> 表情</a></span>
			</td>
		</tr>
		<tr>
			<th></th>
			<td><button type="button" onclick="basicHandler.doAdd('<?php  echo $itemid;?>')" class="btn btn-primary span2">添加</button></td>
		</tr>
	</table>
</div>