<?php
require_once('../lib/coreheader.php');
require_once('../lib/conn.php');
require_once('../lib/weixin.php');

$url = "/weidemo/front/index.php";

$weixin = new WPublicController($url);
$userInfo = $weixin->userInfo;
$openid = $userInfo['openid'];

//var_dump($weixin->userInfo);
//oF66bs-ckFzzbgHLB5vP6f-sx3wM
file_put_contents('aa.log',var_export($weixin->userInfo,true));

if($openid) { //此页面会进来两次，第一次没有openid
    $sql1 = "select count(*) as count from joel_vip where openid = '$openid'";
    $query1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($query1);
    if(!$row1['count']) { //如果没有用户信息，需要记录用户信息
        $nickname = $userInfo['nickname'];
        $sex = $userInfo['sex'];
        $language = $userInfo['language'];
        $province = $userInfo['province'];
        $country = $userInfo['country'];
        $headimgurl = $userInfo['headimgurl'];
        $plv=1;   //层级
        $status=1;
        $ctime=time();  //创建时间
        $pid=0;
        $path=0;
        $sql2 = "insert into joel_vip (`openid`,`nickname`,`sex`,`language`,`province`,`country`,`headimgurl`,`plv`,`status`,`ctime`,`pid`,`path`) values ('$openid','$nickname',$sex,'$language','$province','$country','$headimgurl',$plv,$status,'$ctime',$pid,$path);"; 
        mysql_query($sql2);
    }

    $sql2 = "select count(*) as count from joel_record where openid = '$openid'";
    $query2 = mysql_query($sql2);
    $row2 = mysql_fetch_assoc($query2);
    if(!$row2['count']) {
        header('Location:index.html');
    } else {
        header('Location:itemdata.php');
    }
    exit;
}
