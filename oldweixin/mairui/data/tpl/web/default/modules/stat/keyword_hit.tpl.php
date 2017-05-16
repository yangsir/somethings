<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('common/header', TEMPLATE_INCLUDEPATH);?>
	<div class="main">
		<div class="stat">
			<div class="stat-div">
				<?php  include $this->template('keyword_search', TEMPLATE_INCLUDEPATH);?>
				<div class="sub-item" id="table-list">
					<h4 class="sub-title">详细数据</h4>
					<div class="sub-content">
						<table class="table table-hover">
							<thead class="navbar-inner">
								<tr>
									<th style="width:150px;" class="row-hover">关键字<i></i></th>
									<th>规则<i></i></th>
									<th style="width:80px;">模块<i></i></th>
									<th style="width:80px;">命中次数<i></i></th>
									<th style="width:80px;">最后触发<i></i></th>
									<th style="width:80px;">操作</th>
								</tr>
							</thead>
							<tbody>
								<?php  if(is_array($list)) { foreach($list as $row) { ?>
								<tr>
									<td class="row-hover"><?php  echo $keywords[$row['kid']]['content'];?></td>
									<td><?php  if(empty($row['rid'])) { ?>N/A<?php  } else { ?><a target="main" href="<?php  echo create_url('rule/post', array('id' => $row['rid']))?>"><?php  echo $rules[$row['rid']]['name'];?></a><?php  } ?></td>
									<td><?php  if($rules[$row['rid']]['module']) { ?><?php  echo $rules[$row['rid']]['module'];?><?php  } else { ?>default<?php  } ?></td>
									<td><?php  echo $row['hit'];?></td>
									<td style="font-size:12px; color:#666;"><?php  echo date('Y-m-d <br /> h:i:s', $row['lastupdate']);?></td>
									<td>
										<a target="main" href="<?php  echo $this->createWebUrl('trend', array('id' => $row['rid']))?>" class="btn btn-small" title="使用率走势"><i class="icon-bar-chart"></i></a>
									</td>
								</tr>
								<?php  } } ?>
							</tbody>
						</table>
					</div>
					<?php  echo $pager;?>
				</div>
			</div>
		</div>
	</div>

<script>
$(function() {
	//详细数据相关操作
	var tdIndex;
	$("#table-list thead").delegate("th", "mouseover", function(){
		if($(this).find("i").hasClass("")) {
			$("#table-list thead th").each(function() {
				if($(this).find("i").hasClass("icon-sort")) $(this).find("i").attr("class", "");
			});
			$("#table-list thead th").eq($(this).index()).find("i").addClass("icon-sort");
		}
	});
	$("#table-list thead th").click(function() {
		if($(this).find("i").length>0) {
			var a = $(this).find("i");
			if(a.hasClass("icon-sort") || a.hasClass("icon-caret-up")) { //递减排序
				/*
					数据处理代码位置
				*/
				$("#table-list thead th i").attr("class", "");
				a.addClass("icon-caret-down");
			} else if(a.hasClass("icon-caret-down")) { //递增排序
				/*
					数据处理代码位置
				*/
				$("#table-list thead th i").attr("class", "");
				a.addClass("icon-caret-up");
			}
			$("#table-list thead th,#table-list tbody:eq(0) td").removeClass("row-hover");
			$(this).addClass("row-hover");
			tdIndex = $(this).index();
			$("#table-list tbody:eq(0) tr").each(function() {
				$(this).find("td").eq(tdIndex).addClass("row-hover");
			});
		}
	});
});
</script>
<?php  include $this->template('common/footer', TEMPLATE_INCLUDEPATH);?>
