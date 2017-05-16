<?php defined('IN_IA') or exit('Access Denied');?>﻿<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php  if(empty($_W['setting']['copyright']['sitename'])) { ?>迈瑞微信后台管理系统<?php  } else { ?><?php  echo $_W['setting']['copyright']['sitename'];?><?php  } ?></title>
<meta name="keywords" content="<?php  if(empty($_W['setting']['copyright']['keywords'])) { ?>微信,微信公众平台<?php  } else { ?><?php  echo $_W['setting']['copyright']['keywords'];?><?php  } ?>" />
<meta name="description" content="<?php  if(empty($_W['setting']['copyright']['description'])) { ?>迈瑞微信公众号自助管理平台。<?php  } else { ?><?php  echo $_W['setting']['copyright']['description'];?><?php  } ?>" />
<link type="text/css" rel="stylesheet" href="./resource/style/bootstrap.css" />
<link type="text/css" rel="stylesheet" href="./resource/style/font-awesome.css" />
<link type="text/css" rel="stylesheet" href="./resource/style/common.css?v=<?php echo TIMESTAMP;?>" />
<script type="text/javascript" src="./resource/script/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="./resource/script/bootstrap.js"></script>
<script type="text/javascript" src="./resource/script/common.js?v=<?php echo TIMESTAMP;?>"></script>
<script type="text/javascript" src="./resource/script/emotions.js"></script>
<script type="text/javascript">
cookie.prefix = '<?php  echo $_W['config']['cookie']['pre'];?>';
</script>
<!--[if IE 7]>
<link rel="stylesheet" href="./resource/style/font-awesome-ie7.min.css">
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="./resource/style/bootstrap-ie6.min.css">
<link rel="stylesheet" type="text/css" href="./resource/style/ie.css">
<![endif]-->
</head>
<body <?php  if($action == 'frame') { ?>style="height:100%; overflow:hidden;" scroll="no"<?php  } ?>>
