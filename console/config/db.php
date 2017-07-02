<?php
$config = array(
      'master'=>array(
		    'dbname' => 'test',
		    'user' => 'root',
		    'password' => '',
		    'host' => 'localhost',
		    'driver' => 'pdo_mysql',
              ),
      'slave' => array(
			     array(
					    'dbname' => 'test',
					    'user' => 'root',
					    'password' => '',
					    'host' => 'localhost',
					    'driver' => 'pdo_mysql',
			           )
      	          )
	); 
 return $config;
      


