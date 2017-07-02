<?php 
namespace models\db;
use models\db\dbConfig;

class db{
	 public static $_instance = NULL;
	 
     private function __construct() 
     {
     }
	 public static function getinstance($type='master')
	 {
       if(self::$_instance == NULL){            	 
          self::$_instance = (new self())->getConnection($type);
        }
       return self::$_instance; 
	 }

	 private function getConnection($type = 'master')
	 {
	 	$dbConfig = new dbConfig();
	 	if($type == dbConfig::MASTER){
              $connectionParams = $dbConfig->writeDbConfig(); 
	 	}else{
	 		  $connectionParams = $dbConfig->readDbConfig(); 
	 	}

	 	$config = new \Doctrine\DBAL\Configuration();  
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        return $conn; 
	 } 
 }
