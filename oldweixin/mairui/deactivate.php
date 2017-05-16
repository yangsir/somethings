<?php
define('IN_MOBILE', true);
require 'source/bootstrap.inc.php';
$link=urldecode($_GPC['link']);
$weid=intval($_GPC["weid"]);
$from=urldecode($_GPC['from']);
$dele=pdo_delete("activity",array("weid"=>$weid,link=>$link,who=>$from));
if($dele)
{

echo message('&#24744;&#24050;&#21462;&#28040;&#25253;&#21517;',referer(),"success");
}
else
{

message("删除失败".mysqlerror(),referer(),'tips');

}

?>