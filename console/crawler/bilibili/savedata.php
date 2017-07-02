<?php
/**
 * 保存B站数据到mysql中
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-02 17:12
 */
include '../../init.php';
use models\db\db;
class saveData{
  public function run()
  {  	 
  	file_put_contents('./get.log',var_export($_REQUEST,true),FILE_APPEND); 
	if(empty($_REQUEST)){
		  exit('数据为空');
	}
	$data=[];
	$filed = array('title','href');
	array_map(function($key) use (&$data){
	    if(isset($_REQUEST[$key]) && !empty($_REQUEST[$key])){
	    	 $data[$key] = $_REQUEST[$key]; 
	     }
	},$filed);
	 
	$data['add_time'] = time(); 
	$conn = db::getinstance();
	$result  = $conn->insert('bilibili', $data);
	return $result; 
  } 
}

$class = new saveData();
$class->run();

 


