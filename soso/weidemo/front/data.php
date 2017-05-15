<?php
require_once('../lib/coreheader.php');
require_once('../lib/conn.php');

$userInfo = $_COOKIE['userInfo'];
$openid = $userInfo['openid'];
!$openid && $openid = 1222;
//file_put_contents('aa.log',var_export($weixin->userInfo,true));

if($openid) { //此页面会进来两次，第一次没有openid
    $rows = array();
    $sql = "select * from joel_role where status = 0 order by rand() limit 9";
    $query = mysql_query($sql);
   
    while($row = mysql_fetch_assoc($query)) {
        $rows[] = array(
            'id'      => $row['id'],
            'src'     => '../'.$row['img'],
            'intro'   => array(
                'head'    => '../'.$row['descrpimg'],
                'ewm'     => '../'.$row['weixinimg'],
                'bgColor' => $row['bgcolor'],
            )
        );   
    }
    exit(json_encode($rows));
}
