<?php
require_once('../lib/header.php');
require_once('../lib/conn.php');

$id = intval($_GET['id']);
$sql = "update joel_role set status = 1 where id = $id";
$query = mysql_query($sql);
header("Location:index.php");
exit;
