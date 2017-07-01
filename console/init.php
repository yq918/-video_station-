<?php 
include (__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
$connectionParams = include __DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'db.php';
$config = new \Doctrine\DBAL\Configuration();  
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
 