<?php
/**
 * 保存B站数据到mysql中
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-02 17:12
 */
include '../../init.php';
use models\db\db;
use models\BiliBili\bili;

class save{
  public function run()
  {  
	if(empty($_REQUEST)){
		  exit('数据为空');
	}
	$data = [];
	$filed = array('title','href','video_img');
	array_map(function($key) use (&$data){
	    if(isset($_REQUEST[$key]) && !empty($_REQUEST[$key])){
	    	 $data[$key] = trim($_REQUEST[$key]); 
	     }
	},$filed);
	 
	$data['add_time'] = time();
	$data['video_bili_url'] = $data['href'];
    unset($data['href']); 

	$biliObj = new bili();
	$where = sprintf("title='%s' ",$data['title']);
	$row = $biliObj->getbiliDataByWhere('id',$where);     
	//如果有数据，就不存了
    if(!empty($row)){    	 
        return true;
    }
    //写入主表
    $result =  $biliObj->addBiliBili($data);    
	return $result; 
  }   
}

$class = new save();
$class->run();

 


