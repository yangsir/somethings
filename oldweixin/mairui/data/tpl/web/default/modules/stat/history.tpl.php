<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('common/header', TEMPLATE_INCLUDEPATH);?>
<link type="text/css" rel="stylesheet" href="./resource/style/daterangepicker.css" />
<script type="text/javascript" src="./resource/script/daterangepicker.js"></script>
<script type="text/javascript">
$(function() {
	$('#date-range').daterangepicker({
		format: 'YYYY-MM-DD',
		startDate: $(':hidden[name=start]').val(),
		endDate: $(':hidden[name=end]').val(),
		locale: {
			applyLabel: '确定',
			cancelLabel: '取消',
			fromLabel: '从',
			toLabel: '至',
			weekLabel: '周',
			customRangeLabel: '日期范围',
			daysOfWeek: moment()._lang._weekdaysMin.slice(),
			monthNames: moment()._lang._monthsShort.slice(),
			firstDay: 0
		}
	}, function(start, end){
		$('#date-range .date-title').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
		$(':hidden[name=start]').val(start.format('YYYY-MM-DD'));
		$(':hidden[name=end]').val(end.format('YYYY-MM-DD'));
	});
});
function range(days) {
	var start = moment().add('days', 0 - days).format('YYYY-MM-DD');
	var end = moment().format('YYYY-MM-DD');
	$('#date-range .date-title').html(start + ' 至 ' + end);
	$(':hidden[name=start]').val(start);
	$(':hidden[name=end]').val(end);
	$('form[method=get]')[0].submit();
}
</script>
	<div class="main">
		<div class="stat">
			<div class="stat-div">
				<div class="navbar navbar-static-top">
					<div class="navbar-inner">
						<span class="brand">关键指标详解</span>
						<div class="pull-left">
							<ul class="nav">
								<li <?php  if($_GPC['searchtype'] == 'rule' || empty($_GPC['searchtype'])) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('history', array('searchtype' => 'rule'))?>">已有规则回复</a></li>
								<li <?php  if($_GPC['searchtype'] == 'default') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('history', array('searchtype' => 'default'))?>">默认规则回复</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="sub-item">
					<form action="" method="get">
					<div class="pull-right">
						<input class="btn btn-primary" type="submit" value="搜索">
					</div>
					<div class="pull-left">
						<input name="act" type="hidden" value="<?php  echo $_GPC['act'];?>" />
						<input type="hidden" name="eid" value="<?php  echo $_GPC['eid'];?>" />
						<input name="do" type="hidden" value="<?php  echo $_GPC['do'];?>" />
						<input name="name" type="hidden" value="<?php  echo $_GPC['name'];?>" />
						<input name="searchtype" type="hidden" value="<?php  echo $_GPC['searchtype'];?>" />
						<input type="text" class="span2 kw" name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键字">
						<input name="start" type="hidden" value="<?php  echo date('Y-m-d', $starttime)?>" />
						<input name="end" type="hidden" value="<?php  echo date('Y-m-d', $endtime)?>" />
						<button class="btn" id="date-range" class="date" type="button"><span class="date-title"><?php  echo date('Y-m-d', $starttime)?> 至 <?php  echo date('Y-m-d', $endtime)?></span> <i class="icon-caret-down"></i></button>
						<span class="date-section"><a href="javascript:;" onclick="range(7);">7天</a><a href="javascript:;" onclick="range(14);">14天</a><a href="javascript:;" onclick="range(30);">30天</a></span>
					</div>
					</form>
				</div>
				<div class="sub-item" id="table-list">
					<h4 class="sub-title">详细数据</h4>
					<div class="sub-content">
						<table class="table table-hover">
							<thead class="navbar-inner">
								<tr>
									<th style="width:80px;">用户<i></i></th>
									<th class="row-hover">内容<i></i></th>
									<th style="width:110px;">规则<i></i></th>
									<th style="width:80px;">模块<i></i></th>
									<th style="width:100px;">时间<i></i></th>
									<th style="width:110px;">操作</th>
								</tr>
							</thead>
							<tbody>
								<?php  if(is_array($list)) { foreach($list as $row) { ?>
								<tr>
									<td><a href="#" title="<?php  echo $row['from_user'];?>"><?php  echo cutstr($row['from_user'], 8)?></a></td>
									<td align="left" class="row-hover"><div style="max-width:515px; overflow:hidden; word-break:break-all; word-wrap:break-word;"><?php  echo $row['message'];?></div></td>
									<td><?php  if(empty($row['rid'])) { ?>N/A<?php  } else { ?><a target="main" href="<?php  echo create_url('rule/post', array('id' => $row['rid']))?>"><?php  echo cutstr($rules[$row['rid']]['name'], 6)?></a><?php  } ?></td>
									<td><?php  echo $row['module'];?></td>
									<td style="font-size:12px; color:#666;">
									<?php  echo date('Y-m-d <br /> h:i:s', $row['createtime']);?>
									</td>
									<td>
									<a href="#" title="删除" class="btn btn-small"><i class="icon-remove"></i></a>
									</td>
								</tr>
								<?php  } } ?>
							</tbody>
						</table>
					</div>
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
