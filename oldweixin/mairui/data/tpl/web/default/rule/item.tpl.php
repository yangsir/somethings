<?php defined('IN_IA') or exit('Access Denied');?><?php  if(empty($item)) { ?>
	<?php  $namesuffix = '-new[]';?>
	<?php  $itemid = '(itemid)';?>
	<?php  $item['type'] = 2;?>
<?php  } else { ?>
	<?php  $namesuffix = '['.$item['id'].']'?>
	<?php  $itemid = 'keyword-item-' . $item['id'];?>
<?php  } ?>
<div id="form" class="alert alert-block alert-new <?php  if(!empty($item)) { ?>hide<?php  } ?>">
	<a class="close" data-dismiss="alert">×</a>
	<h4 class="alert-heading">添加关键字</h4>
	<table>
		<tr<?php  if($item['type'] == 4) { ?> class="hidden"<?php  } ?>>
			<th>关键字</th>
			<td>
				<input type="text" id="keyword-name-<?php  echo $itemid;?>" name="keyword-name<?php  echo $namesuffix;?>" class="span7 keyword-name-new" placeholder="" autocomplete="off" value="<?php  echo $item['content'];?>">
				<span class="help-block">根据此处设置的关键字进行对应回复，关键词单次添一个，可多次添加 <a class="iconEmotion" href="javascript:;" inputid="keyword-name-<?php  echo $itemid;?>"><i class="icon-github-alt"></i> 表情</a></span>
			</td>
		</tr>
		<tr>
			<th>对应方式</th>
			<td>
				<input type="hidden" id="keyword-type-new" name="keyword-type<?php  echo $namesuffix;?>" class="span7 keyword-type-new" value="<?php  echo $item['type'];?>" autocomplete="off">
				<div class="btn-group" data-toggle="buttons-radio">
					<?php  if(is_array($types)) { foreach($types as $value => $type) { ?>
						<?php  if($value == $item['type']) { ?>
							<?php  $currenttype = $type;?>
						<?php  } ?>
						<span class="btn <?php  if($value == $item['type']) { ?>active<?php  } ?>" value="<?php  echo $value;?>" onclick="<?php  if($value == 4) { ?>$(this).parent().parent().parent().prev().hide();<?php  } else { ?>$(this).parent().parent().parent().prev().show();<?php  } ?>" description="<?php  echo $type['description'];?>"><?php  echo $type['name'];?></span>
					<?php  } } ?>
				</div>
				<?php  if(empty($item)) { ?>
				<span class="help-block rule-description">用户进行微信交谈时，对话内容完全等于上述关键字才会执行这条规则。</span>
				<?php  } else { ?>
				<span class="help-block rule-description"><?php  echo $currenttype['description'];?></span>
				<?php  } ?>
			</td>
		</tr>
		<tr>
			<th></th>
			<td><button type="button" class="btn btn-primary span2" onclick="keywordHandler.doAdd('<?php  echo $itemid;?>')">添加</button></td>
		</tr>
	</table>
</div>
<div id="show" class="alert alert-info <?php  if(empty($item)) { ?>hide<?php  } ?>">
	<span class="pull-right"><?php  if(!empty($item['id'])) { ?><a href="<?php  echo create_url('rule/delete', array('type' => 'keyword', 'rid' => $item['rid'], 'kid' => $item['id']))?>" onclick="return doDeleteItem('keyword-item-<?php  echo $item['id'];?>', this.href)" style="margin-right:5px;">删除</a><?php  } else { ?><a onclick="doDeleteItem('<?php  echo $itemid;?>')" href="javascript:;" style="margin-right:5px;">删除</a><?php  } ?><a onclick="keywordHandler.doEditItem('<?php  echo $itemid;?>')" href="javascript:;">编辑</a></span>
	<div class="content"><span id="content"><?php  echo $item['content'];?></span>&nbsp;（<span id="type"><?php  echo $types[$item['type']]['name'];?></span>）</div>
</div>
