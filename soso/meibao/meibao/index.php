<?php
header("Content-type: text/html; charset=utf-8");

// 应用入口文件


// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);
// 定义应用目录
define('APP_PATH',     './Application/');

require './ThinkPHP/ThinkPHP.php';