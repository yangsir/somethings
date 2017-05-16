<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('header', TEMPLATE_INCLUDEPATH);?>
<body class="b_g">
<style>
.cl{text-decoration:none;}
.cl a:hover{text-decoration:none;color:#336699;}
</style>
<section class="m12">
<p class="g6">积分规则：每门微课读完之后可积<b class="red">5</b>分</p>

	<div class="plist">
   		<ul>
          <?php  if(is_array($result['list'])) { foreach($result['list'] as $v) { ?>
        	<li class="cl">
            	<a  href="<?php  echo $v['link'];?>"><b><?php  echo $v['title'];?></b>
                <p class="g6 ft12">获得积分：<b class="red">5</b>分</p></a>
            </li>
       	<?php  } } ?>
        </ul>
    </div>
    
</section>
<section>
<?php  echo $result['pager'];?>
</section>
</body>
</html>