<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('header', TEMPLATE_INCLUDEPATH);?>

<?php  $result = site_article_search($cid,'',1);?>
<?php  $resultall = site_article_search($cid,'',1000);?>
<body>
<style>
.z1 img{height:25%;width:100%;}
.z1 a{text-decoration:none;}
.down { display:none; position:absolute; top:60px; width:200px; background:#333; border-radius:5px;}

.dbg { position:absolute; left:94px; top:-6px; width:12px; height:8px; background:url(../img/t_bg.png) 0 0 no-repeat; background-size:12px 8px;}

.down ul { margin:5px; border:#222 1px solid; border-bottom:0; background:#444;}

.down li { font-size:12px; height:38px; line-height:38px;  width:100%; border-bottom:#222 1px solid; text-align:center;}
</style>
<script type="text/javascript" src="js/module.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<section style="width:100%" class="z2">
	<span class="pull"><?php  echo urldecode($_GPC['cname']);?><img src="img/p_bg.png" class="ml5" width="10" height="8" />
        <div class="down">
            <div class="dbg"></div>

            <ul>
                  <?php  if(is_array($resultall['list'])) { foreach($resultall['list'] as $item) { ?>
              <li><a style="font-size:14px;color:#fff" href="<?php  echo $this->createMobileUrl('list3', array('name' => 'list3','cname'=>urlencode($item['title']),'id' =>$item['id'], 'cid' =>$cid,'weid' => $_W['weid']))?>"><?php  echo $item['title'];?></a></li>               
<?php  } } ?>

                           </ul>
        </div>
    </span>
</section>
<section class="z1">

	<ul class="class pclass">
    <?php  if(!$id) { ?>
                <?php  if(is_array($result['list'])) { foreach($result['list'] as $row) { ?>
        	<?php  echo $row['content'];?>
        	        
                <?php  } } ?>
<div style="position:relative;top:16;left:8;">
<?php  echo $result['pager'];?>
</div>
</ul>



<?php  } else { ?>

        	<?php  echo $detail['content'];?>
        	        
                </ul>
<?php  } ?>
</section>
<?php  include $this->template('footer', TEMPLATE_INCLUDEPATH);?>