<?php defined('IN_IA') or exit('Access Denied');?><div id="append-list" class="reply-list list">
	<?php  if(is_array($reply)) { foreach($reply as $item) { ?>
	<div class="item" id="basic-item-<?php  echo $item['id'];?>">
		<?php  include $this->template('item');?>
	</div>
	<?php  } } ?>
</div>
<a href="javascript:;" onclick="buildAddForm('basic-item-html', $('#append-list'));" class="add-reply-button"><i class="icon-plus"></i> 添加回复内容</a>
<script type="text/html" id="basic-item-html">
<?php  unset($item); include $this->template('item');?>
</script>
<script type="text/javascript">
<!--
	var basicHandler = {
		'doAdd' : function(itemid) {
			var parent = $('#' + itemid);
			if ($('.basic-content-new', parent).val() == '') {
				message('请输入回复内容！', '', 'error');
				return false;
			}
			$('#show #content', parent).html($('.basic-content-new', parent).val());
			$('#show', parent).css('display', 'block');
			$('#form', parent).css('display', 'none');
			<?php  if(empty($rid)) { ?>buildAddForm('basic-form-html', $('#append-list'));<?php  } ?>
		}
	};
	<?php  if(empty($rid)) { ?>
	$(function(){
		buildAddForm('basic-item-html', $('#append-list'));
	});
	<?php  } ?>
//-->
</script>