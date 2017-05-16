<?php defined('IN_IA') or exit('Access Denied');?>﻿<?php  include template('common/header', TEMPLATE_INCLUDEPATH);?>
<style type="text/css">
body{background:#f9f9f9;}
.login{width:598px;height:230px; margin:0 auto;padding-top:30px;background:#EEE;border:1px #ccc solid;border-top:0;}
.login .table{width:500px;margin:0 auto;}
.login .table td{border:0;}
.login .table label{font-size:16px;}
.login-hd{width:600px;margin:0 auto;overflow:hidden;margin-top:35px;font-size:20px;font-weight:600;height:40px;}
.login-hd div{float:left;width:200px; height:35px; line-height:40px;cursor:pointer;}
.login-hd div a{color:#FFF; display:block; text-decoration:none; text-align:center;}
.login-hd-bottom{width:600px;margin:0 auto;height:5px;background:#CCC;margin-top:-5px;}
</style>
<script>
$(function() {
	$('.login-hd div').each(function() {
		$(this).css({'border-bottom': '5px '+$(this).css("background-color")+' solid', 'background': 'none'});
		$(this).find('a').hide();
	});
	$('.login-hd').delegate("div", "mouseover", function(){
		$('.login-hd div').each(function() {
			$(this).css({'border-bottom': '5px '+$(this).css("border-bottom-color")+' solid', 'background': 'none'});
			$(this).find('a').hide();
		});
		$(this).css('background-color', $(this).css("border-bottom-color"));
		$(this).find('a').show();
	});
	$('.login-hd').mouseleave(function() {
		$('.login-hd div').each(function() {
			$(this).css({'border-bottom': '5px '+$(this).css("border-bottom-color")+' solid', 'background': 'none'});
			$(this).find('a').hide();
		});
	});
	var u = cookie.get('remember-username');
	if($.trim(u)) {
		$('#remember')[0].checked = true;
		$(':text[name="username"]').val($.trim(u));
	}
});
function formcheck() {
	if($('#remember:checked').length == 1) {
		cookie.set('remember-username', $(':text[name="username"]').val());
	} else {
		cookie.del('remember-username');
	}
	return true;
}
</script>

<div align="center">
	<h2>迈瑞微信后台管理登录</h2>
</div>
<div class="login-hd-bottom"></div>
<form action="" method="post" target="_top" onsubmit="return formcheck();">
<div class="login" id="myLogin">
	<table class="table">
		<tr>
			<td style="width:120px;"><label>用户名：</label></td>
			<td><input type="text" class="span4" autocomplete="off" name="username"></td>
		</tr>
		<tr>
			<td><label>密码：</label></td>
			<td><input type="password" class="span4" autocomplete="off" name="password"></td>
		</tr>
		<tr>
			<td></td>
			<td><label for="remember" class="checkbox inline"><input type="checkbox" value="1" id="remember" checked> 记住账号</label> &nbsp;&nbsp;<?php  if(!empty($_W['setting']['register']['open'])) { ?><label class="checkbox inline"><a href="<?php  echo create_url('member/register')?>">注册新用户</a></label><?php  } ?></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" class="btn span2" value="登录"/><input type="hidden" name="token" value="<?php  echo $_W['token'];?>" /></td>
		</tr>
	</table>
</div>
</form>
<?php  include template('common/footer', TEMPLATE_INCLUDEPATH);?>
