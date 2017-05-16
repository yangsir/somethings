<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('header', TEMPLATE_INCLUDEPATH);?>
<body>
<style>
img{width:80;height:60;}
</style>
<section>
	<div class="list">
        <?php  if($collec) { ?>

    	<ul>
	<?php  if(is_array($collec)) { foreach($collec as $v) { ?>
	<li>

      <?php  if($v['icon']!=$_W['attachurl']) { ?><img src="<?php  echo $v['icon'];?>" /><?php  } else { ?>       <img src="img/sthumb.jpg" /><?php  } ?>
             <a style="display:block;overflow:hidden;text-decoration:none;width:52%;height:20;" href="<?php  echo $v['link'];?>">
  <b><?php  echo $v['title'];?></b></a><div style="position:absolute;top:11;right:0;"><font><?php  echo date('Y-m-d', $v['date'])?></font></div><p style="overflow:hidden;width:70%;height:20;"><?php  echo $v['description'];?></p>
            	<div style="float:right;position:relative;top:6;"><a href="del.php?did=<?php  echo $v['id'];?>&weid=<?php  echo $_W['weid'];?>">删除</a>
</div>											
	</li>
	<?php  } } ?>
 </ul>
 </div>
</section>
<section>
<?php  echo $result['pager'];?>
</section>
<?php  } else { ?>
<div align='center'><h2>暂无收藏</h2></div>
<?php  } ?>
<?php  include $this->template('footer', TEMPLATE_INCLUDEPATH);?>