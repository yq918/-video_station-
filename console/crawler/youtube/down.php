<?php
/**
 * 下载youtube视频到本地
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '/home/zxr/video/video_station/console/init.php';
use models\db\db;
use models\Youtube\youtube;
use library\qiniu\Upload;

class download{
    /**
     * @var Upload
     */
    private $upload;

    public function  __construct()
    {
        $this->upload = new Upload();
    }

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

       $name = time()+mt_rand(0,1000)+$key+mt_rand(0,1000+$key);
       $file_path = "{$dir}video{$name}.mp4";
//       $call = "youtube-dl  {$value['video_url']}  -o {$file_path}";  	 
       $call = "youtube-dl ".escapeshellarg( $value['video_url'])."  -o ".escapeshellarg($file_path);
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


    /**
     * upDownloadData
     *
     * [修改upload表里的数据]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
   public  function upDownloadData()
   {
     $youtubeObj = new youtube();
     $data =  $youtubeObj->getDownloadData();
     if(empty($data)){
         echo '没有数据可处理';
         exit;
     }
     foreach ($data as $key => $value) {
           if(empty($value['video_path']) || $value['video_path'] == '0'){
                continue;
           }
           $id = $value['id'];
           $where = sprintf("you_id = %d",$id);
           $dowData =  $youtubeObj->getDownloadData('id',$where);
           $ret = false;
         //上传七牛
          if(file_exists($value['video_path'])){
             $file_Arr  = explode('/',$value['video_path']);
             $video_file_name = array_pop($file_Arr);
             $ret =  $this->upload->upload_file('youtube-videos',$value['video_path'],$video_file_name);
          }
        //下载图片
         $dir = "/data/images/youtube/".date('YmdH');
         if(!is_dir($dir) || !file_exists($dir)){
             mkdir($dir,0777);
         }
         $img_url  = $value['video_cover'];
         $urlInfo  = parse_url($img_url);
         $img_file_name = str_replace('/','strive',$urlInfo['path']);
         $path = $dir.'/'.$img_file_name;
         $ua = 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
         $call = "axel -n 2 -o ". escapeshellarg($path)."  -U {$ua}  ".escapeshellarg($img_url);

         echo $call;

         exec($call,$array); //执行命令
         usleep(1000);
         $img_ret = false;
         if(file_exists($path)){
             //保存本地成功，上传到七牛
             $img_ret =  $this->upload->upload_file('youtube-images',$path,$img_file_name);
         }

         if(empty($dowData)){
             $downData = array(
                 'you_id' => $id,
                 'video_title'    => $value['video_title'],
                 'play_duration'  => $value['play_duration'],
                 'playback_times' => $value['playback_times'],
                 'video_time_content' => $value['video_time_content'],
                 'image_file_name'  => $img_file_name,
                 'video_file_name' => $video_file_name,
                 'add_time' => time()
             );
             if($ret){
                  $downData['video_upload'] = 1;
             }
             if($img_ret){
                  $downData['img_upload'] = 1;
             }

             echo '<pre>';
             print_r($downData);
             exit;

             $result = $youtubeObj->addDownloadData($downData);
         }

     }
 }
}

$class = new download();
$class->upDownloadData();

 


