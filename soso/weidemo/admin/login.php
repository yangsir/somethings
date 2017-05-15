<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(7);
session_start();

if($_POST['submit']) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if(!$username || !$password) {
        echo "<script>alert('请输入用户名或密码~');history.go(-1);</script>"; 
        return false;
    }
    
    if($username != 'admin' || $password != '1234567890') {
        echo "<script>alert('用户名或密码错误~');history.go(-1);</script>"; 
        return false;
    }

    $_SESSION['login'] = 'su123';
    header("Location:index.php");exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>微信人物上传后台</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<head>
<style>
p{text-align:center;}
</style>
</head>
<body>
<form action='' method='post'>
<p>登录系统</p>
<p>用户名：<input type='text' name='username' size='20'></p>
<p>密&nbsp;&nbsp;码：<input type='password' name='password' size='20'></p>
<p><input type='submit' name='submit' value='登录'>&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='重置'></p>
</form>
</body>
</html>
