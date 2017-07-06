<?php
/**
 * 保存youtube数据到mysql中
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '../../init.php';
use models\db\db;
use models\Youtube\youtube;
class saveData{
  public function run()
  {
	if(empty($_REQUEST)){
		  exit('数据为空');
	}
	$data=[];
	$filed = array('video_url','video_title','play_duration','video_time_content','playback_times','video_cover');
	array_map(function($key) use (&$data){
	    if(isset($_REQUEST[$key]) && !empty($_REQUEST[$key])){
	    	 $data[$key] = trim($_REQUEST[$key]);
	     }
	},$filed);
	 
	$str = str_replace(',','',$data['video_time_content']); 
	preg_match_all('/\d+/is',$str,$arr); 
	$data['playback_times']    = isset($arr[0][1]) ? $arr[0][1] : 0; 
	$data['add_time'] = time();

	$youtubeObj = new youtube();
	$addYoutubeData = array(
		'video_url'   => $data['video_url'],
		'video_title' => $data['video_title'],
		'video_cover' => $data['video_cover'],
		'add_time' => time()
	);
	$lastId = $youtubeObj->addYoutubeData($addYoutubeData);
	if($lastId){
		$downData = array(
            'you_id' => $lastId,
			'video_title'    => $data['video_title'],
			'play_duration'  => $data['play_duration'],
			'playback_times' => $data['playback_times'],
			'video_time_content' => $data['video_time_content']
		);
		$result = $youtubeObj->addDownloadData($downData);
	}
	return $result; 
  }
}

$class = new saveData();
$class->run();

 


