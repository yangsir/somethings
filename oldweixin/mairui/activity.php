<?php
define('IN_MOBILE', true);
require 'source/bootstrap.inc.php';
$ctitle=urldecode($_GPC['title']);
$cweid=intval($_GPC['weid']);
$cfname=urldecode($_GPC['fname']);
$cdate=intval($_GPC['date']);
$clink=urldecode($_GPC['link']);
$cicon=urldecode($_GPC['icon']);
$source=urldecode($_GPC['source']);
$author=urldecode($_GPC['author']);
$aid=intval($_GPC['aid']);
$collection=array('weid'=>$cweid,'date'=>$cdate,'title'=>$ctitle,'icon'=>$cicon,'who'=>$cfname,'link'=>$clink,'description'=>$source,'author'=>$author,'aid'=>$aid);
$sel=pdo_fetch("select * from mr_activity where link='".$clink."' and weid=".$cweid." and who='".$cfname."'");
if($sel){
message("已报名!",referer(),"success");
}
else{
$incol=pdo_insert("activity",$collection);

if(!$inclo)
{
message("报名成功!",referer(),"success");}
else
{message("报名出错，请稍后在试" . mysql_error(),referer(),"tips");}
}

?>