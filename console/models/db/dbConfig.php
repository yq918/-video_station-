<?php
namespace models\db;
class dbConfig{
	  private static $dbConfig = [];
	  const MASTER = 'master';
	  const SLAVE  = 'slave';

	  public function __construct()
	  {
	  	$this->parseConfigFile();
	  }
	 /**
	  * [writeDbConfig description]
	  * @return [type] [description]
	  * @author zhangxuanru [strive965432@gmail.com]
	  */
     public function writeDbConfig()
     {     	 
     	$connectionParams = self::$dbConfig[self::MASTER];
        return $connectionParams;
     }

     /**
	  * [readDbConfig description]
	  * @return [type] [description]
	  * @author zhangxuanru [strive965432@gmail.com]
	  */
    public function readDbConfig()
    {
              $readConnection = self::$dbConfig[self::SLAVE];
              $len = count($readConnection);
              $randKey = rand(0,$len);
              $connectionParams = $readConnection[$randKey];
              return $connectionParams;
     }

      /**
	  * [parseConfigFile description]
	  * @return [type] [description]
	  * @author zhangxuanru [strive965432@gmail.com]
	  */
    private function parseConfigFile()
    {           	
    	$configFile = BASEPATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'db.php';
    	$dbconfig = include $configFile;
    	self::$dbConfig = $dbconfig;
    }

}


