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
$collection=array('weid'=>$cweid,'date'=>$cdate,'title'=>$ctitle,'icon'=>$cicon,'who'=>$cfname,'link'=>$clink,'description'=>$source);
$sel=pdo_fetch("select * from mr_collection where link='".$clink."'"." and who='".$cfname."' and weid=".$cweid);
if($sel){
message("已收藏!",referer(),"success");
}
else{
$incol=pdo_insert("collection",$collection);

if(!$inclo)
{message("收藏成功!",referer(),"success");}
else
{message("无法收藏" . mysql_error(),referer(),"tips");}
}

?>


