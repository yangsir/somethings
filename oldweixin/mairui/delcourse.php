<?php
define('IN_MOBILE', true);
require 'source/bootstrap.inc.php';

$tid=intval($_GPC["did"]);
$dele=pdo_delete("course",array("id"=>$tid));
if(!$dele)
{

message("删除成功",referer(),'success');

}
else
{

message("删除失败".mysqlerror(),referer(),'tips');

}

?>