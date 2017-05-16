<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	header("p3p: CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\"");
	
	session_start();	
	
	require('lib/php-captcha/php-captcha.inc.php');

   	$fonts = array('lib/php-captcha/VeraSeBd.ttf','lib/php-captcha/VeraBd.ttf');
   	$captcha = new PhpCaptcha($fonts, 200, 60);
   	$captcha->Create();
	
?>
