<?php
require_once('../lib/header.php');
require_once('../lib/upload.class.php');
require_once('../lib/conn.php');

$sql   = "select * from joel_role where status = 0 order by id desc";
$query = mysql_query($sql);

if($_POST['search']) {
    $name = trim($_POST['name']);
    if($name) {
        $where = " and name like '%$name%' ";
    }
    $sql   = "select * from joel_role where status = 0 $where order by id desc";
    $query = mysql_query($sql);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>微信人物上传后台</title>
<head>
<style>
.pc{text-align:center;}
table{border:none; border-collapse: collapse;}
table td{border:1px solid #ccc;}
</style>
</head>
<body>
<p><a href='add.php'>上传人物</a></p>
<div style='text-align:center;'>
<form action='' method='post'>
<p>名称：<input type='text' name='name' size='20'>&nbsp;
<input type='submit' name='search' value='搜索'></p>
</form>
<table width='100%'>
<tr>
<td width='5%'>主键</td>
<td width='10%'>名称</td>
<td width='20%'>头像</td>
<td width='20%'>简介</td>
<td width='20%'>二维码</td>
<td width='5%'>背景颜色</td>
<td width='10%'>上传时间</td>
<td width='10%'>操作</td>
</tr>
<?php 
while ($row = mysql_fetch_assoc($query)) {
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><img src="<?php echo $imgpre.$row['img']; ?>" width='150px'></td>
<td><img src="<?php echo $imgpre.$row['descrpimg']; ?>" width='150px'></td>
<td><img src="<?php echo $imgpre.$row['weixinimg']; ?>" width='150px'></td>
<td><?php echo $row['bgcolor']; ?></td>
<td><?php echo date('Y-m-d',$row['addtime']); ?></td>
<td><a href='modify.php?id=<?php echo $row['id']; ?>'>编辑</a>&nbsp;&nbsp;<a href="del.php?id=<?php echo $row['id']; ?>" onclick= "return confirm('是否确定删除');">删除</a></td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
