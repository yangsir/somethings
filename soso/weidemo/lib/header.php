<?php
require_once('coreheader.php');

session_start();
if(!$_SESSION['login'] || $_SESSION['login'] !='su123') {
    header("Location:login.php");exit;
}
