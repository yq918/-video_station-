<?php
/**
 * 下载youtube视频到本地
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '../../init.php';
use models\db\db;

class download{
  public function run()
  { 
    $path = "youtube"; 	 
  	$conn = db::getinstance();  	 
  	$data = $conn->fetchAll("select id,video_url from youtube where video_path='0' ");
  	if(empty($data)){
  		echo '没有视频可下载';
  		exit;
  	}
  $count = 0; 	 
  foreach ($data as $key => $value) {
  	   $date = date('YmdH');
  	   $dir  = VIDEO_PATH.$path.'/'.$date.'/';
        if(!is_dir($dir) || !file_exists($dir)){
             mkdir($dir,0777,true);
         }
       $file_path = "{$dir}video{$key}.mp4";
  	   $call = "youtube-dl  {$value['video_url']}  -o {$file_path}";  	 
	   exec($call,$array); //执行命令
	   usleep(1000);
	   $result = false;
	   if(file_exists($file_path)){   
	   	 $result =  $conn->update('youtube', array('video_path' => $file_path), array('id' => $value['id']));
	   }
       if($result ){
       	  $count++;
        }
      }  

      echo "共需下载视频".count($data)."个下载成功:".$count.'个';
      exit;
   }
}

$class = new download();
$class->run();

 


