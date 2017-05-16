<?php
define('IN_MOBILE', true);
require 'source/bootstrap.inc.php';
$id=$_GPC['id'];
$now=$_GPC['now'];
$totalNum=pdo_fetch("select cool from mr_article where id='".$id."' and weid='".$_GPC['weid']."'");
if(empty($now)){
$n=intval($totalNum['cool'])+1;
$upcool=pdo_update("article",array("cool"=>$n),array("id"=>$id));
}
else
{
$n=$totalNum['cool'];
}
echo $n;
?>