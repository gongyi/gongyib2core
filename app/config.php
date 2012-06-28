<?php
// 所有配置内容都可以在这个文件维护
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ALL);
define('ALPA_VERSION', '3.7');
// 配置url路由
$route_config = array(
  '/page/'=>'/page/view/',
  '/tag/'=>'/page/tag/',
  '/thumb/'=>'/file/thumb/',
  '/login/'=>'/user/login/',
  '/reg/'=>'/user/reg/',
  '/logout/'=>'/user/logout/'
);

if(file_exists(APP.'config_user.php')) require(APP.'config_user.php');
if(file_exists(APP.'config_app.php')) require(APP.'config_app.php');
