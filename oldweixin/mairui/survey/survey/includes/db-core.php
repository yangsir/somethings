<?php
/******************************************************************************
 MachForm
  
 Copyright 2007 Appnitro Software. This code cannot be redistributed without
 permission from http://www.nulledscriptz.com/
 
 More info at: http://www.nulledscriptz.com/
 ******************************************************************************/
	function connect_db(){
		$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Could not connect: ' . mysql_error());
		mysql_select_db(DB_NAME) or die('Could not select database');
		mysql_query("SET NAMES utf8");
		return $link;
	}
	
	function do_query($query){
		$result = mysql_query($query) or die($query.' Query failed: ' . mysql_error());
		return $result;
	}
	
	function do_fetch_result($result){
		$row = mysql_fetch_array($result);
		return $row;
	}

?>
