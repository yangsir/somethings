<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php  include $this->template('common/header', TEMPLATE_INCLUDEPATH);?>
<style>
.field label{float:left;margin:0 !important; width:140px;}
</style>
<ul class="nav nav-tabs">
	<li class="active"><a href="<?php  echo $this->createWebUrl('display');?>">粉丝列表</a></li>
	<!--li><a href="<?php  echo $this->createWebUrl('asyn');?>">获取粉丝信息</a></li-->
</ul>
<div class="main">
	<div class="stat">
		<div class="stat-div">
			<div class="navbar navbar-static-top">
				<div class="navbar-inner">
					<span class="brand">关键指标详解</span>
				</div>
				<div class="sub-item">
					<h4 class="sub-title">搜索</h4>
					<form action="site.php" method="get">
					<input type="hidden" name="act" value="module" />
					<input type="hidden" name="name" value="fans" />
					<input type="hidden" name="do" value="display" />
					<table class="table sub-search">
					<tbody>
						<tr>
							<th style="width:80px;">
								登记情况
								<div style="font-weight:normal;">
									<label class="checkbox inline" id="field-all"><input type="checkbox"> 全选</label>
								</div>
							</th>
							<td class="field">
								<?php  if(is_array($fields)) { foreach($fields as $field) { ?>
								<label class="checkbox inline"><input type="checkbox" name="select[]" value="<?php  echo $field['field'];?>" <?php  if(in_array($field, $select)) { ?> checked<?php  } ?> /> <?php  echo $field['title'];?></label>
								<?php  } } ?>
							</td>
						</tr>
						<tr>
							<th>关注时间</th>
							<td>
								<button style="margin:0;" class="btn span5" id="date-range" type="button"><span class="date-title"><?php  echo date('Y-m-d', $starttime)?> 至 <?php  echo date('Y-m-d', $endtime)?></span> <i class="icon-caret-down"></i></button>
								<input name="start" type="hidden" value="<?php  echo date('Y-m-d', $starttime)?>" />
								<input name="end" type="hidden" value="<?php  echo date('Y-m-d', $endtime)?>" />
							</td>
						</tr>
						<tr>
							<th></th>
							<td><input type="submit" name="" value="搜索" class="btn btn-primary"></td>
						</tr>
					</tbody>
					</table>
					</form>
				</div>
			</div>
			<div class="sub-item" id="table-list">
				<h4 class="sub-title">详细数据</h4>
				<form action="" method="post" onsubmit="">
				<div class="sub-content">
					<table class="table table-hover">
						<thead class="navbar-inner">
							<tr>
								<th style="width:40px;" class="row-first">选择</th>
								<th class="row-hover" style="width:80px;">用户<i></i></th>
								<?php  if(is_array($select)) { foreach($select as $field) { ?>
								<th><?php  echo $fields[$field]['title'];?><i></i></th>
								<?php  } } ?>
								<th style="width:110px;">关注时间<i></i></th>
<th>昵称</th>
                                                                <th>积分</th>
                                                                 <th style="width:40px;" class="row-first">奖品种类</th> 
                                                                 <th style="width:40px;" class="row-first">奖品申领状态</th>
								<th></th>
							</tr>

						</thead>
						<tbody>
							<?php  if(is_array($list)) { foreach($list as $row) { ?>
							<tr>
								<td class="row-first"><input type="checkbox" name="select[]" value="<?php  echo $row['id'];?>" /></td>
								<td class="row-hover"><a href="#" title="<?php  echo $row['from_user'];?>"><?php  echo cutstr($row['from_user'], 8)?></a></td>
								<?php  if(is_array($select)) { foreach($select as $field) { ?>
								<th><?php  echo $row[$field];?><i></i></th>
								<?php  } } ?>
								
<td style="font-size:12px; color:#666;">
								<?php  echo date('Y-m-d <br /> H:i:s', $row['createtime']);?>
								</td>
 <td style="font-size:12px; color:#666;">
								<?php  echo $row['nickname'];?>
								</td>
                                                                  <td style="font-size:12px; color:#666;">
								<?php  echo $row['credit1'];?>
								</td>
                                                                 <td style="font-size:12px; color:#666;">
								<?php  echo $row['nationality'];?>
								</td>
                                                                 <td style="font-size:12px; color:#666;">
								<?php  echo $row['affectivestatus'];?>
								</td>								<td><a href="<?php  echo $this->createWebUrl('profile', array('from_user' => $row['from_user']))?>">编辑</a></td>
							</tr>
							<?php  } } ?>
						</tbody>
					</table>
					<table class="table">
						<tr>
							<td class="row-first"></td>
							<td>
								<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
							</td>
						</tr>
					</table>
				</div>
				</form>
				<?php  echo $pager;?>
			</div>
		</div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="./resource/style/daterangepicker.css" />
<script type="text/javascript" src="./resource/script/daterangepicker.js"></script>
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
	//全选
	$('#field-all input[type="checkbox"]').change(function() {
		var a = $(this).is(':checked');
		if(a) {
			$('.field input[type="checkbox"]').each(function() {
				$(this).attr("checked", true);
			});
		} else {
			$('.field input[type="checkbox"]').each(function() {
				$(this).attr("checked", false);
			});
		}
	});
});
</script>
<?php  include $this->template('common/footer', TEMPLATE_INCLUDEPATH);?>
