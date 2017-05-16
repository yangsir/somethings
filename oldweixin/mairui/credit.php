<?php
define('IN_MOBILE', true);
require 'source/bootstrap.inc.php';
$id=intval($_GET['id']);
$weid=intval($_GET['weid']);
$from=intval($_GET['from']);
$sql = "SELECT * FROM " . tablename('fans') . " WHERE `id`=:who and `weid`=:weid";
		$credit = pdo_fetch($sql, array(':who'=>$from,':weid'=>$weid));
if($id==100){
if(intval($credit['credit1'])<100)
{
echo "积分不足！刷新后查看您的积分！";
}
else{

pdo_update("fans",array('credit1'=>'0','affectivestatus'=>'已兑换','nationality'=>'奖品1'),array('id'=>$from,'weid'=>$weid));
echo "恭喜您，奖品兑换成功！刷新后您的积分将相应减少！";
}
}
if($id==200){
if(intval($credit['credit1'])<200)
{
echo "积分不足！刷新后查看您的积分！";
}
else{
pdo_update("fans",array('credit1'=>'0','affectivestatus'=>'已兑换','nationality'=>'奖品2'),array('id'=>$from,'weid'=>$weid));
echo "恭喜您，奖品兑换成功！刷新后您的积分将相应减少！";
}
}
if($id==500){
if(intval($credit['credit1'])<500)
{
echo "积分不足！刷新后查看您的积分！";
}
else{
pdo_update("fans",array('credit1'=>'0','affectivestatus'=>'已兑换','nationality'=>'奖品3'),array('id'=>$from,'weid'=>$weid));
echo "恭喜您，奖品兑换成功！刷新后您的积分将相应减少！";
}
}
?>