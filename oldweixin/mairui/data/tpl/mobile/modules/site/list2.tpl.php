<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('header', TEMPLATE_INCLUDEPATH);?>
<body>
<section>
	<div class="qlist"><ul><?php  if(is_array($result['list'])) { foreach($result['list'] as $row) { ?>


       	  <li><b><a style="overflow:hidden;width:100%;text-decoration:none;height:20;" href="./survey/survey/view.php?id=<?php  echo $row['form_id'];?>"><?php  echo $row['form_name'];?></a></b><p style="overflow:hidden;width:100%;text-decoration:none;height:40;" class="g6"><?php  echo $row['form_description'];?>   </p></li>
       	 
         <?php  } } ?></ul>
    </div>
</section>
<section>
<?php  echo $result['pager'];?>
</section>
</body>