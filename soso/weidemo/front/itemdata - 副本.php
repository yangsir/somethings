<?php
require_once('../lib/coreheader.php');
require_once('../lib/conn.php');

$userInfo = json_decode($_COOKIE['userInfo'],true);
$openid = $userInfo['openid'];
!$openid && $openid = 1222;

//file_put_contents('aabbbcc1.log',$openid);
//var_dump($weixin->userInfo);
//file_put_contents('aabbbcc.log',var_export($userInfo,true));

if($openid) { //此页面会进来两次，第一次没有openid
    $rows = array();
    $sql1 = "select * from record where openid = '$openid' order by record_id desc limit 1";
    $query1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($query1);

    if($row1['role_id']) {
        $sql2 = "select * from role where id = {$row1['role_id']}";
        $query2 = mysql_query($sql2);
        $row = mysql_fetch_assoc($query2);
        $rows[] = array(
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
