<?php defined('IN_IA') or exit('Access Denied');?>﻿	
<script type="text/javascript">
$(function() {
	$(".user-box .box-item").each(function(i) {
		i = i +1;
		if(i%3 == 0) $(this).css("border-right", "0");
	});
	$(window).scroll(function(){
		$(".menu-button").find("i").removeClass("icon-minus-sign").addClass("icon-plus-sign");
		$(".menu-main").hide();
	});
	$(".menu-main a").click(function(){ $(".menu-main").hide(); });

	//控制tab宽度
	var profile_tab = $(".nav-tabs li");
	profile_tab.css({"width": 100/profile_tab.length+"%", "text-align": "center"});

	//手机表单处理
	$(".form-table").delegate(".checkbox input[type='checkbox']", "click", function(){
		$(this).parent().toggleClass("btn-inverse");
	});
	$(".form-table").delegate(".file input[type='file']", "change", function(){
		var a = $(this).next("button");
		a.html(a.html() +' '+  $(this).val());
	});

	//处理固定横向导航条
	var navbarFixedTop = false, navbarFixedBottom = false;
	navbarFixedTop = $(".navbar").hasClass("navbar-fixed-top");
	navbarFixedBottom = $(".navbar").hasClass("navbar-fixed-bottom");
	if(navbarFixedTop) $("body").css("padding-top", "41px");
	if(navbarFixedBottom) $("body").css("padding-bottom", "41px");
});

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
WeixinJSBridge.call('hideOptionMenu');
});
	
</script>
</body>
</html>