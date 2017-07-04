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
use models\BiliBili\bili;

class qiliu{ 
  public function run()
  { 
     $Upload = new Upload();
     $bill   = new bili(); 
  	 $data   = $bill->getbiliData();
  	 if(empty($data)){
  		 echo '没有数据';
  		 exit;
  	 }  
   foreach ($data as $key => $value) {
  	  $video_img = $value['video_img'];
      if(empty($video_img)){
         continue;
      }
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
                  $ret =  $Upload -> upload_file('bilibili-images',$file_path,$file_name);                     
                 }
                    //写进数据库    
                     $db_data = array(
                        't_id'       => $value['id'],
                        'image_path' => $file_path,
                        'image_name' => $file_name 
                      );  
                   if($ret){
                      $db_data['is_qiniu'] = 1;
                      $db_data['add_qiniu_time'] = time();
                    } 
                  $bill->addImages($db_data);   
            } 
     }
}  
$class = new qiliu();
$class->run();


