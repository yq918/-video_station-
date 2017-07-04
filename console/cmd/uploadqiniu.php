<?php
/**
 * 下载youtube视频到本地
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '../init.php';
use models\db\db;

class qiliu{
  public function run()
  { 
  	$conn = db::getinstance();  	 
  	$data = $conn->fetchAll("select id,video_img,video_path from bilibili ");
  	if(empty($data)){
  		echo '没有视频可下载';
  		exit;
  	}      
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

                 echo $call;
                 exit;

                 exec($call,$array); //执行命令
	             usleep(1000);

            }

     }
  }
}

$class = new qiliu();
$class->run();

 


