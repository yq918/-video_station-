<?php
/**
 * 下载youtube视频到本地
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '../init.php';
include '../config/conf.php';

use models\db\db;
use library\qiniu\Upload;

class qiliu{
  public function run()
  { 
  	$conn = db::getinstance();  	 
  	$data = $conn->fetchAll("select id,video_img,video_path from bilibili ");
  	if(empty($data)){
  		echo '没有视频可下载';
  		exit;
  	}  

    $Upload = new Upload();

         foreach ($data as $key => $value) {
  	       $video_img = $value['video_img'];
  	       if(!empty($video_img)){
                  //下载到本地
                $dirname = date('YmdH');               
                $dir = "/data/images/".$dirname;
                if(!is_dir($dir) || !file_exists($dir)){
                    mkdir($dir,0777,true);
                 }
                 $urlInfo = parse_url($video_img);
                 $pathArr = explode('/',$urlInfo['path']);
                 $file_name = array_pop($pathArr);
                 $file_path = $dir.'/'.$file_name;                  
                 $call = "axel -n 2 -o  {$file_path}  {$video_img}";                   
                 exec($call,$array); //执行命令
	               usleep(1000);
                 if(file_exists($file_path)){
                  //上传七牛
                  $Upload -> upload_file($dirname,$file_path,$file_name);
                   
                 }
               


            }

     }
  }
}

$class = new qiliu();
$class->run();

 


