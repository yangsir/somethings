<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('header', TEMPLATE_INCLUDEPATH);?>
<?php  $row = $detail;?>
<?php  $t=time();?>
<body>
<div id="activity-detail">
<style type="text/css">
@charset "utf-8";
html{background:#FFF;color:#000;}
body, div, dl, dt, dd, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, textarea, p, blockquote, th, td{margin:0;padding:0;}
table{border-collapse:collapse;border-spacing:0;}
fieldset, img{border:0;}
address, caption, cite, code, dfn,  th, var{font-style:normal;font-weight:normal;}
ol, ul{list-style:none;}
caption, th{text-align:left;}
h1, h2, h3, h4, h5, h6{font-size:100%;font-weight:normal;}
q:before, q:after{content:'';}
abbr, acronym{border:0;font-variant:normal;}
sup{vertical-align:text-top;}
sub{vertical-align:text-bottom;}
input, textarea, select{font-family:inherit;font-size:inherit;font-weight:inherit;}
input, textarea, select{font-size:100%;}
legend{color:#000;}
html{background-color:#f8f7f5;}
body{background:#f8f7f5;color:#222;font-family:Helvetica, STHeiti STXihei, Microsoft JhengHei, Microsoft YaHei, Tohoma, Arial;height:100%;position:relative;}
body > .tips{display:none;left:50%;padding:20px;position:fixed;text-align:center;top:50%;width:200px;z-index:100;}
.page{padding:15px;}
.page .page-error, .page .page-loading{line-height:30px;position:relative;text-align:center;}
.btn{background-color:#fcfcfc;border:1px solid #cccccc;border-radius:5px;box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);color:#222;cursor:pointer;display:block;font-size:15px;font-weight:bold;margin:15px 0;moz-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);padding:10px;text-align:center;text-decoration:none;webkit-box-shadow:0 1px 4px rgba(0, 0, 0, 0.3);}
#activity-detail .page-bizinfo{border-bottom:2px solid #EEE;}
#activity-detail .page-bizinfo .header{padding: 15px 15px 10px; border-bottom:1px solid #CCC;}
#activity-detail .page-bizinfo .header #activity-name{color:#000;font-size:20px;font-weight:bold;word-break:normal;word-wrap:break-word;}
#activity-detail .page-bizinfo .header #post-date{color:#8c8c8c;font-size:11px;margin:0;}
#activity-detail .page-content{padding:15px;}
#activity-detail .page-content .media{margin-bottom:18px;}
#activity-detail .page-content .media img{width:100%;}
#activity-detail .page-content .text{color:#3e3e3e;font-size:1.5;line-height:1.5;width: 100%;overflow: hidden;zoom:1;}
#activity-detail .page-content .text p{min-height:1.5em;min-height: 1.5em;word-wrap: break-word;word-break:break-all;}
#activity-list .header{font-size:20px;}
#activity-list .page-list{border:1px solid #ccc;border-radius:5px;margin:18px 0;overflow:hidden;}
#activity-list .page-list .line.btn{border-radius:0;margin:0;text-align:left;}
#activity-list .page-list .line.btn .checkbox{height:25px;line-height:25px;padding-left:35px;position:relative;}
#activity-list .page-list .line.btn .checkbox .icons{background-color:#ccc;left:0;position:absolute;top:0;}
#activity-list .page-list .line.btn.off .icons{background-image:none;}
#activity-list #save.btn{background-image:linear-gradient(#22dd22, #009900);background-image:-moz-linear-gradient(#22dd22, #009900);background-image:-ms-linear-gradient(#22dd22, #009900);background-image:-o-linear-gradient(#22dd22, #009900);background-image:-webkit-gradient(linear, left top, left bottom, from(#22dd22), to(#009900));background-image:-webkit-linear-gradient(#22dd22, #009900);}
.vm{vertical-align:middle;}
.tc{text-align:center;}
.db{display:block;}
.dib{display:inline-block;}
.b{font-weight:700;}
.clr{clear:both;}
.text img{max-width:100%;}
.page-url{padding-top:18px;}
.page-url-link{color:#607FA6;font-size:14px;text-decoration:none;text-shadow:0 1px #ffffff;-webkit-text-shadow:0 1px #ffffff;-moz-text-shadow:0 1px #ffffff;}
#footer{text-align:center; background-color:#DADADA; border-top:1px solid #B9B9B9;}
#footer .copyright{border-top: 1px solid #CACACA;color: #7B7B7B;height: 35px;line-height: 35px;font-size: 12px;}
#footer .copyright a{display: block;width: 100%;height: 100%;}
</style>
<div class="page-bizinfo">
	<div class="header">
		<h1 id="activity-name"><?php  echo $row['title'];?></h1>
		<span id="post-date"><?php  echo date('Y-m-d',$row['createtime'])?></span> &nbsp;&nbsp;     <span id="post-date"><?php  echo $row['author'];?></span>

	</div>
</div>

<div class="page-content">
	<div class="media">
		<img onerror="this.parentNode.removeChild(this)" src="<?php  echo $row['thumb'];?>">
	</div>
	<div class="text">
		<?php  echo $row['content'];?>

	</div>
</div>
<?php  if($rid) { ?>
<footer>
		<a href="deactivate.php?from=<?php  echo urlencode($_W['fans']['from_user'])?>&weid=<?php  echo $_W['weid'];?>&link=<?php  echo urlencode($this->createMobileUrl('detail5', array('name' => 'detail5', 'id' => $row['id'], 'weid' => $_W['weid'])))?>"><img src="img/i_c.png" width="24" height="24" />取消报名</a>
		</footer>

<?php  } else { ?>
<footer>
		<a href="activity.php?aid=<?php  echo $row['id'];?>&weid=<?php  echo $_W['weid'];?>&author=<?php  echo $row['author'];?>&fname=<?php  echo urlencode($_W['fans']['from_user'])?>&title=<?php  echo urlencode($row['title'])?>&icon=<?php  echo urlencode($row['thumb'])?>&date=<?php  echo $t;?>&link=<?php  echo urlencode($this->createMobileUrl('detail5', array('name' => 'detail5', 'id' => $row['id'], 'weid' => $_W['weid'])))?>&&source=<?php  echo urlencode($row['source'])?>"><img src="img/i_c.png" width="24" height="24" />报名</a>
		<a href="s.php?weid=<?php  echo $_W['weid'];?>&fname=<?php  echo urlencode($_W['fans']['from_user'])?>&title=<?php  echo urlencode($row['title'])?>&icon=<?php  echo urlencode($row['thumb'])?>&date=<?php  echo $t;?>&link=<?php  echo urlencode($this->createMobileUrl('detail5', array('name' => 'detail5', 'id' => $row['id'], 'weid' => $_W['weid'])))?>&&source=<?php  echo urlencode($row['source'])?>"><img src="img/i_s.png" width="24" height="24" />收藏</a>

</footer>
<?php  } ?>
</div>
<?php  include $this->template('footer', TEMPLATE_INCLUDEPATH);?>