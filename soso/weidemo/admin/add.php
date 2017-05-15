<?php
require_once('../lib/header.php');
require_once('../lib/upload.class.php');
require_once('../lib/conn.php');

$basedir = 'uploads/';
if($_POST['upsubmit']) {
    $name  = trim($_POST['name']);
    $openid=trim($_POST['openid']);
    $bgcolor  = trim($_POST['bgcolor']);
    if(!$name || !$bgcolor || !$_FILES['img']['name'] || !$_FILES['descrpimg']['name'] || !$_FILES['weixinimg']['name']) {
        echo "<script>alert('请输入完整信息~');history.go(-1);</script>"; 
        return false;
    }

    $upload = new Upload();                              // 实例化上传类
	$upload->maxSize	= 2097152;								// 设置附件上传大小
	$upload->allowExts	= array('jpg', 'gif', 'png', 'jpeg');	// 设置附件上传类型
	//$upload->savePath = 'choumeitopic/';				// 设置附件上传目录

    $file_info = $upload->upload();  //上传成功 获取上传文件信息
	if(!$file_info){
        echo "<script>alert('图片上传失败~');history.go(-1);</script>"; 
        return false;
    }else{
        $img = $basedir.$file_info['img']['savepath'].$file_info['img']['savename'];
        $descrpimg = $basedir.$file_info['descrpimg']['savepath'].$file_info['descrpimg']['savename'];
        $weixinimg = $basedir.$file_info['weixinimg']['savepath'].$file_info['weixinimg']['savename'];
    }

    $time = time();
    $sql = "insert into joel_role (`name`,`img`,`descrpimg`,`weixinimg`,`bgcolor`,`addtime`,`modifytime`,`status`,`openid`) values ('$name','$img','$descrpimg','$weixinimg','$bgcolor',$time,0,0,'$openid')";
    $query = mysql_query($sql);
    if($query) {
        header("Location:index.php");exit;
    } else {
        echo "<script>alert('数据库写入失败~');history.go(-1);</script>"; 
        return false;
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>微信人物上传后台</title>
<script src="http://apps.bdimg.com/libs/jquery/2.1.1/jquery.min.js"></script>
<head>
<style>
.pc{text-align:center;}
</style>
    <script>
    function check(){
        var name=$("#name").val();
        var openid=$("#openid").val();
        if(name==''){
            alert('名称不能为空');
            return false;
        }
          if(openid==''){
            alert('openid不能为空');
            return false;
        }
    }
    </script>
</head>
<body>
<p><a href='index.php'>查看人物</a></p>
<div>
    <form enctype="multipart/form-data" method="post" name="upform" onsubmit="return check();"> 
        <p class='pc'>名&nbsp;&nbsp;称：<input type='text' name='name' size='20' id="name"><span style="color:red;">*</span></p>
        <p class='pc'>openid：<input type='text' name='openid' size='20' id="openid"><span style="color:red;">*</span></p>
<p class='pc'>头像上传：<input name="img" type="file" size="25"><br/><font color='red' size='2'>*：图片宽高264x324px</font></p>
<p class='pc'>简介上传：<input name="descrpimg" type="file" size="25"><br/><font color='red' size='2'>*：图片宽高560x800px</font></p>
<p class='pc'>二维码上传：<input name="weixinimg" type="file" size="25"><br/><font color='red' size='2'>*：图片宽高154x155px</font></p>
<p class='pc'>背景颜色：<input type='text' name='bgcolor' size='20'><br/><font color='red' size='2'>*：格式#fdf577或者red</font></p>
<p class='pc'><input type='submit' name='upsubmit' value='上传'>&nbsp;&nbsp;&nbsp;&nbsp;<input type='reset' value='重置'></p>
</form>
</div>
</body>
</html>
