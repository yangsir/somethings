<?php
require_once('../lib/coreheader.php');
require_once('../lib/conn.php');

$role_id = intval($_REQUEST['id']);
//$gotype = intval($_REQUEST['gotype']);
if(!$role_id) exit('param error');

$userInfo = json_decode($_COOKIE['userInfo'],true);
$openid = $userInfo['openid'];
//!$openid && $openid = 1222;

//var_dump($weixin->userInfo);
//file_put_contents('aa.log',var_export($weixin->userInfo,true));

if($openid) { //此页面会进来两次，第一次没有openid
    /*
    $sql1 = "select count(*) as count from lookuser where openid = '$openid'";
    $query1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($query1);
    if(!$row1['count']) { //如果没有用户信息，需要记录用户信息
        $nickname = $userInfo['nickname'];
        $sex = $userInfo['sex'];
        $language = $userInfo['language'];
        $province = $userInfo['province'];
        $country = $userInfo['country'];
        $headimgurl = $userInfo['headimgurl'];
    
        $sql2 = "insert into lookuser (`openid`,`nickname`,`sex`,`language`,`province`,`country`,`headimgurl`) values ('$openid','$nickname',$sex,'$language','$province','$country','$headimgurl');"; 
        mysql_query($sql2);
    }
     */
    
    //if(!$gotype) { //浏览记录
     $sql = "insert into joel_record (openid,role_id) values ('$openid',$role_id)";
     //插入分配导师id进vip表
     $rows = array();
  

    if($role_id) {
        $sql1 = "select * from joel_role where id =$role_id limit 1";
        $query1 = mysql_query($sql1);
        $row1 = mysql_fetch_assoc($query1);
        $sql2="select * from joel_vip where openid='".$row1['openid']."' limit 1";
        $query2 = mysql_query($sql2);
        $row2 = mysql_fetch_assoc($query2);
        $sql3="update joel_vip set fpr=".$row2['id']." where openid='".$openid."'";
        mysql_query($sql3);
    }
    //} else { //最后一次记录
    //    $sql = "insert into lastrecord (openid,role_id) values ('$openid',$role_id)";
    //}
    
    mysql_query($sql);
    exit('1');
}
