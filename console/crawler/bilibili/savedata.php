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
	$filed = array('title','href','video_img');
	array_map(function($key) use (&$data){
	    if(isset($_REQUEST[$key]) && !empty($_REQUEST[$key])){
	    	 $data[$key] = $_REQUEST[$key]; 
	     }
	},$filed);
	 
	$data['add_time'] = time();
	$data['video_bili_url'] = $data['href'];
    unset($data['href']);

	$conn = db::getinstance();
    
    $sql = sprintf("select id  from bilibili where video_bili_url='%s' ",$data['video_bili_url']);
    $row = $conn->fetchAll($sql);
    if(!empty($row)){
    	$result =  $conn->update('bilibili', array('video_img' => $data['video_img'],'title' => $data['title']), array('video_bili_url' => $data['video_bili_url']));
    	 return $result;
    }

	$result  = $conn->insert('bilibili', $data);
	return $result; 
  } 
}

$class = new saveData();
$class->run();

 


