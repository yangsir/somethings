<?php defined('IN_IA') or exit('Access Denied');?><?php  include $this->template('header', TEMPLATE_INCLUDEPATH);?>
<body onload="javascript:document.getElementById('b1').innerHTML='兑换';document.getElementById('b2').innerHTML='兑换';document.getElementById('b3').innerHTML='兑换';" class="b_g">
<script>
function showC(id,c,id2)
{
var xmlhttp;
if (c=="")
  {
  document.getElementById(id).innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
if(xmlhttp.responseText.indexOf("积分不足")>=0)
{id2.innerHTML="兑换";
}
else{
id2.innerHTML="已兑";
}
document.getElementById(id).innerHTML=xmlhttp.responseText;

id2.onclick="";

    }
  }
xmlhttp.open("GET","credit.php?from=<?php  echo $_W['fans']['id'];?>&weid=<?php  echo $_W['weid'];?>&id="+c,true);
xmlhttp.send();
}
</script>
<style>
.cl{text-decoration:none;}
.cl a:hover{text-decoration:none;color:#336699;}
</style>
<section class="m12">

<p><b class="ft16">我的积分:<span id="mycredit" class="red"><?php  echo $p['credit1'];?></span></b></p>
    
<p class="g6">
	<b class="red">积分规则：</b>每读完瑞学•观摩栏目里的一门微课程,即可积<b class="red">10</b>分
</p>
<div class="plist">
   		<ul>
          <?php  if(is_array($detail)) { foreach($detail as $row) { ?>
        	<li class="cl">
            	<a  href="<?php  echo $row['link'];?>"><b><?php  echo $row['title'];?></b>
                <p class="g6 ft12">获得积分：<b class="red">5</b>分</p></a>
            </li>
       	<?php  } } ?>
       <li class="cl" align="center"><a href="<?php  echo $this->createMobileUrl('list4', array('name' => 'list4','fname'=>$_W['fans']['from_user'],'weid' => $_W['weid']))?>">点击查看详细积分>></a></li>
        </ul>
    </div>
<?php  echo $prize['content'];?>
		
<div class="credit"> <button id="b1" <?php  if($p['credit1']<100) { ?>class="not"  <?php  } else { ?>onclick="javascript:this.style.borderColor='#336699';this.style.color='#336699';showC('prize',100,this)"<?php  } ?>></button></div>
<div class="ccredit"> <button id="b2" <?php  if($p['credit1']<200) { ?>class="not"  <?php  } else { ?>onclick="javascript:this.style.borderColor='#336699';this.style.color='#336699';showC('prize',200,this)"<?php  } ?>></button>
</div>
    <div class="bcredit"><button id="b3" <?php  if($p['credit1']<500) { ?>class="not"  <?php  } else { ?>onclick="javascript:this.style.borderColor='#336699';this.style.color='#336699';showC('prize',500,this)"<?php  } ?>></button></div>
	
   
</section>
</body>
</html>