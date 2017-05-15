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
    $sql1 = "select * from joel_record where openid = '$openid' order by record_id desc limit 1";
    $query1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($query1);

    if($row1['role_id']) {
        $sql2 = "select * from joel_role where id = {$row1['role_id']}";
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
}
?>

<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>发财助理</title>
    <meta name="description" content="发财助理">
    <meta name="author" content="发财助理">
    <meta name="keywords" content="发财助理">
    <meta name="expires" content="7776000">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta content="telephone=no" name="format-detection" />
    <script src="./js/prefixfree.min.js"></script>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
<div class="container">
    <!--loading-->
    <div class="loading mask" style="display:block">
        <div class="loader loader--glisteningWindow"></div>
    </div>
    <!--/loading-->
    <!--主内容-->
    <ul class="content-list">
    <li class="content-li" style="background-color:<?php echo $rows[0]['intro']['bgColor']; ?>">
            <div class="page p2">
                <div class="row">
                    <div class="col12 intro">
                        <img src="<?php echo $rows[0]['intro']['head']; ?>"/>
                        <img class="ewm" src="<?php echo $rows[0]['intro']['ewm']; ?>"/>
                        <img class="footer" src="./images/footer.png"/>
                    </div>
                </div>
            </div>
        </li>
        <!--
        <li class="content-li" style="background-color:#abcdef">
            <div class="page p2">
                <div class="row">
                    <div class="col12 intro">
                        <img src="./images/intro.jpg"/>
                        <img class="ewm" src="./images/ewm.jpg"/>
                        <img class="footer" src="./images/footer.png"/>
                    </div>
                </div>
            </div>
        </li>
        -->
    </ul>
</div>
<script src="./js/zepto.min.js"></script>
<script src="./js/head.min.js"></script>
<script>
    $(document).ready(function () {
        head.load("./js/fastclick.min.js",function () {
            var firstImg=new Image();
            firstImg.src=$(".p2").find("img").first().attr("src");
            var init=function(){
                $(".content-li").css({"visibility":"visible"});
                $(".loading").hide();
                $(".ewm").addClass("shake");
            };
            if(firstImg.complete){
                init();
            }else{
                firstImg.onload=function(){
                    init();
                };
            }
        });
        $(window).on("resize",function () {
            $(".content-li").each(function () {
                $(this).css("height", $(window).height());
            });
        }).trigger("resize");
    });
</script>
</body>
</html>
