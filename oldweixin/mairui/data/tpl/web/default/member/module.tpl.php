<?php defined('IN_IA') or exit('Access Denied');?><?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<style>
.module .thumbnails li{}
</style>
<div class="main">
		<div class="module">
			<div class="row-fluid">
				<ul class="thumbnails">
				<?php  if(is_array($modulelist)) { foreach($modulelist as $row) { ?>
				<li style="width:23.6%; min-width:240px;">
					<div class="thumbnail">
						<div class="module-pic">
							<img src="./source/modules/<?php  echo strtolower($row['name']);?>/preview.jpg" onerror="this.src='./resource/image/module-nopic-big.jpg'" <?php  if(!$row['enabled']) { ?>class="gray"<?php  } ?>>
							<div class="module-detail">
								<h5 class="module-title"><?php  echo $row['title'];?><small>（标识：<?php  echo $row['name'];?>）</small></h5>
								<p class="module-brief"><?php  echo $row['ability'];?></p>
								<p class="module-description"><?php  echo $row['description'];?> <?php  if($row['isrulefields']) { ?><a href="<?php  echo create_url('rule/post', array('module' => $row['name']))?>" class="text-info">添加规则</a><?php  } ?></p>
							</div>
						</div>
						<div class="module-button">
							<?php  if($row['issystem']) { ?>
								<a href="javascript:;" class="pull-right"><span>此模块为系统模块</span></a>
							<?php  } else { ?>
							<?php  if($row['enabled']) { ?>
								<a id="enabled_<?php  echo $row['mid'];?>_0" href="<?php  echo create_url('member/module/enable', array('mid' => $row['mid'], 'enabled' => 0))?>" onclick="return ajaxopen(this.href)" class="btn btn-primary module-button-switch">禁用</a>
							<?php  } else { ?>
								<a id="enabled_<?php  echo $row['mid'];?>_1" href="<?php  echo create_url('member/module/enable', array('mid' => $row['mid'], 'enabled' => 1))?>" onclick="return ajaxopen(this.href);" class="btn btn-danger module-button-switch">启用</a>
							<?php  } ?>
							<?php  } ?>
							<?php  if($row['enabled'] && !$row['issystem']) { ?>
								<?php  if($row['shortcut']) { ?>
								<a href="<?php  echo create_url('member/module/shortcut', array('mid' => $row['mid'], 'shortcut' => 0))?>" onclick="return ajaxopen(this.href);" class="btn btn-danger">移出快捷操作</a>
								<?php  } else { ?>
								<a href="<?php  echo create_url('member/module/shortcut', array('mid' => $row['mid'], 'shortcut' => 1))?>" onclick="return ajaxopen(this.href);" class="btn">加入快捷操作</a>
								<?php  } ?>
							<?php  } ?>
							<?php  if($row['settings'] && $row['enabled']) { ?>
								<a href="<?php  echo create_url('member/module/setting', array('mid' => $row['mid']))?>" class="btn module-button-switch">设置</a>
							<?php  } ?>
						</div>
					</div>
				</li>
				<?php  } } ?>
			</div>
			<div>
				<?php  echo $pager;?>
			</div>
		</div>
		<!-- The End -->
	</div>
</div>
	<script type="text/javascript">
		function toggle_description(id) {
			var container = $('#'+id).parent().parent().parent();
			var status = $('#'+id).attr("status");
			if(status == 1) {
				$('#'+id).attr("status", "0")
				container.find(".module_description").show();
			} else {
				$('#'+id).attr("status", "1")
				container.find(".module_description").hide();
			}
		}
		$(function() {
			$('.module .thumbnails').delegate('li .module-button-switch', 'click', function(){ //控制模块开关
				if($(this).hasClass('btn-primary')) { //禁用模块
					$(this).removeClass('btn-primary').addClass('btn-danger').html('开启');
				} else if($(this).hasClass('btn-danger')) { //开启模块
					$(this).removeClass('btn-danger').addClass('btn-primary').html('禁用');
				}
				$(this).parent().parent().find('.module-pic img').toggleClass('gray');
			});
			$('.module .thumbnails').delegate('li', 'hover', function(){ //控制模块详细信息
				$(this).find('.module-title,.module-brief').toggle();
				$(this).find('.module-description').toggle('fast');
			});
		});
	</script>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
