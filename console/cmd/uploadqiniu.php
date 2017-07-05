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


public function uploadVideo()
{
   //上传视频到七牛   
     $Upload = new Upload();
     $bill   = new bili(); 
     $data   = $bill->getbiliData();
     if(empty($data)){
       echo '没有数据';
       exit;
     }  
   foreach ($data as $key => $value) {
      $video_path = $value['video_path'];
      if(empty($video_path)){
          continue;
      } 
     if(file_exists($video_path)){
           //上传七牛
            $pathArr   =  explode('/',$video_path);
            $file_name =  array_pop($pathArr);
            $ret =  $Upload -> upload_file('bilibili-videos',$video_path,$file_name);                     
       }

          $where   =  sprintf("t_id = %d",$value['id']);
          $imgData = $bill->getImages('id',$where);
          $img_id  = isset($imgData[0]['id']) ? $imgData[0]['id'] : 0;
         //写进数据库    
           $db_data = array(
                 't_id'       => $value['id'],
                 'img_id'     => $img_id,
                 'video_path' => $video_path,
                 'video_name' => $file_name 
              );  
              if($ret){
                  $db_data['is_qiniu'] = 1;
                  $db_data['add_qiniu_time'] = time();
              } 
            $v_id =  $bill->addVideos($db_data);   

            if($v_id){
              //重新查一次v_id
               $where   =  sprintf("t_id = %d",$value['id']);
               $videoData =$bill->getVideos('id',$where);
               $v_id = isset($videoData[0]['id']) ? $videoData[0]['id'] : 0;
               $up_data = array(
                   'v_id' => $v_id,
                   'img_id' => $img_id
                  );
                $where = array('id' => $value['id']);
                $bill->updateBiliBili($up_data,$where);
                $updata['v_id'] =    $v_id;
                $where   =  array('t_id' => $value['id']);
                $bill->updateBiliImage($updata,$where); 
            }

    } 
    
} 
}  

$class = new qiliu();
$class->uploadVideo();


