<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('header', TEMPLATE_INCLUDEPATH);?>
<?php  $result = site_article_search($cid,'',1);?>
<body class="b_g"  style="margin-top:-20px">
<style>
li{padding-top:8px;}
.c{height:4px;border:#336699 1px solid;}
.class a:hover{text-decoration:none;color:#336699;}
.class img{height:22%;width:100%;}
</style>
<section>
	<div><img src="img/c_10.png" width="100%" height="166" /></div>

        <ul style="width:320; margin: 0px auto;" class="class">
    	<?php  if(is_array($result['list'])) { foreach($result['list'] as $row) { ?>
            <?php  echo $row['content'];?>
<?php  } } ?>
<?php  echo $result['pager'];?>
    </ul>



</body>
</html>