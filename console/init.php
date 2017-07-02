<?php 
include (__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');

$basepath = realpath(dirname(__FILE__).'/../') . '/'; 
define('SITEBASE', $basepath);
define('BASEPATH',SITEBASE.'console');
define('VIDEO_PATH','/data/video/');

function load($class)
{ 
    $file = '';
    if(!empty($class)){
        $classFile = str_replace('\\',DIRECTORY_SEPARATOR,$class);
        $file  = BASEPATH.DIRECTORY_SEPARATOR.$classFile.'.php';
    }   
    if(!empty($file) && file_exists($file)){
         require $file;
    }
}
spl_autoload_register('load');



