<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");

    $tagFile = DEDEADMIN."/../tags.html";
	$fp = fopen($tagFile,"w") or die("��ָ�����ļ��������⣬�޷������ļ�");
	fclose($fp);	
	$pv = new PartView();
	$GLOBALS['_arclistEnv'] = 'index';
	$pv->SetTemplet($cfg_basedir.$cfg_templets_dir."/".$cfg_df_style."/index_tag.htm");  //��TAGҳģ��
	$pv->SaveToHtml($tagFile);
	echo "�ɹ�����TAG��ҳ��".$tagFile;
	echo "<br/><br/><a href='../tags.html' target='_blank'>���...</a>";

?>