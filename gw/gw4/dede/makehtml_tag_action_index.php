<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");

    $tagFile = DEDEADMIN."/../tags.html";
	$fp = fopen($tagFile,"w") or die("你指定的文件名有问题，无法创建文件");
	fclose($fp);	
	$pv = new PartView();
	$GLOBALS['_arclistEnv'] = 'index';
	$pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$cfg_df_style."/index_tag.htm");  //打开TAG页模板
	$pv->SaveToHtml($tagFile);
	echo "成功更新TAG主页：".$tagFile;
	echo "<br/><br/><a href='../tags.html' target='_blank'>浏览...</a>";

?>