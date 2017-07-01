<?php
/**
 * 保存youtube数据到mysql中
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */

include '../../init.php'; 
file_put_contents('./get.log',var_export($_REQUEST,true),FILE_APPEND); 
if(empty($_REQUEST)){
	  exit('数据为空');
}
$data=[];
$filed = array('video_url','video_title','play_duration','video_time_content','playback_times','video_cover');
array_map(function($key) use (&$data){
    if(isset($_REQUEST[$key]) && !empty($_REQUEST[$key])){
    	 $data[$key] = $_REQUEST[$key]; 
     }
},$filed);
 
$str = str_replace(',','',$data['video_time_content']); 
preg_match_all('/\d+/is',$str,$arr); 
$data['playback_times']    = isset($arr[0][1]) ? $arr[0][1] : 0; 
$data['add_time'] = time(); 
$conn->insert('youtube', $data);


