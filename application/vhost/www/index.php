<?php 
define('APPLICATION_PATH', dirname(__FILE__));
define("DEBUG",true);
  
if(DEBUG){
  error_reporting(E_ALL || E_STRICT);
  ini_set("display_errors","on");
}  
require_once '../../conf/init.php';
 
$application = new Yaf\Application( APPLICATION_PATH . "/application.ini"); 

$application->bootstrap()->run();


