<?php

/**
 * Class Down
 *
 * [下载B站的视频与图片]
 */ 
//include '../../init.php';
include '/home/zxr/video/video_station/console/init.php';
include '/home/zxr/video/video_station/console/config/conf.php';
use models\db\db;
use library\HTTP\HttpCurl;
use models\BiliBili\bili;
use library\qiniu\Upload;
use crawler\bilibili\base\download;

class Down
{
    /**
     * @var bili|null
     */
    private  $biliObj = null;
    /**
     * [$upload description]
     * @var null
     */
    private  $upload  = null;
    /**
     * [$download description]
     * @var null
     */
    private  $downloadObj = null;

    public  function __construct()
    {
      $this->biliObj  = new bili();
      $this->upload   = new Upload();
      $this->downloadObj = new download();
    }
    /**
     * downVideo
     *
     * [下载视频]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function  downVideo()
    {
        $data =   $this->getbiliData();
        if(empty($data)){
            echo '没有数据';
            exit;
        }
        foreach ($data as $key => $value) {
          if($value['v_id'] > 0 || empty($value['video_interface'])){
             continue;
          } 
 
          $interface = $value['video_interface'];
          $video_url = $value['video_url'];
          $dbdata = $this->downloadObj->downloadVideo($interface,$video_url);
          if($dbdata == false || empty($dbdata)){
            echo $interface.'下载失败';
           file_put_contents(ERR_DB_LOG,var_export(array('content'=>$value),true)."\r\n",FILE_APPEND);
            continue;
          }

        //上传到七牛
        if(isset($dbdata['download']) && $dbdata['download'] == true && !empty($dbdata['video_path'])) {
                $video_path = $dbdata['video_path'];
                $file_name  = $dbdata['video_name'];
                $ret =  $this->upload_file('bilibili-videos',$video_path,$file_name);
        }   
       // $where   =  sprintf("t_id = %d",$value['id']);
       // $imgData = $this->biliObj->getImages('id',$where);
      //  $img_id  = isset($imgData[0]['id']) ? $imgData[0]['id'] : 0;
        
       //褰熉ヨt_id
            $tWhere = sprintf("b_id=%d",$value['id']);
            $tRow = $this->biliObj->getTitleDataByWhere('id',$tWhere);
            $t_id = isset($tRow[0]['id']) ? $tRow[0]['id'] : 0;
          
           $where   =  sprintf("t_id = %d",$t_id);
           $imgData = $this->biliObj->getImages('id',$where);
           $img_id  = isset($imgData[0]['id']) ? $imgData[0]['id'] : 0;

         //写入videos表
        $video_data = array(
             't_id'         => $t_id,
             'img_id'       => $img_id,
             'video_format' => isset($dbdata['video_format']) ? $dbdata['video_format'] : '',
             'video_size'   => isset($dbdata['video_size']) ? $dbdata['video_size'] : '',
             'video_path'   => isset($dbdata['video_path']) ? $dbdata['video_path'] : '',
             'video_name'   => isset($dbdata['video_name']) ? $dbdata['video_name'] : '' 
            );
          if($ret){
              $video_data['is_qiniu'] = 1;
              $video_data['add_qiniu_time'] = time();
              } 
          $v_id = $this->biliObj->addVideos($video_data); 

         //更改图片表
         if($v_id){
            //重新查一次v_id
               $where     =  sprintf("t_id = %d",$t_id);
               $videoData =  $this->biliObj->getVideos('id',$where);
               $v_id      = isset($videoData[0]['id']) ? $videoData[0]['id'] : 0;
               $up_data = array(
                   'v_id' => $v_id,
                   'img_id' => $img_id,                    
                   'video_url' => $dbdata['video_url']
                  );
                $where = array('id' => $value['id']);
                $this->biliObj->updateBiliBili($up_data,$where);
               
                $titleUp= array(
                   'v_id' => $v_id,
                   'img_id' => $img_id,
                  );
 
                $where = array('id' => $t_id );
                $this->biliObj->updateTitleData($titleUp,$where); 
               
                $updata['v_id'] = $v_id;
                $where   =  array('id' => $img_id);
                $this->biliObj->updateBiliImage($updata,$where);  
           }  
      }

    }

    /**
     * downImage
     *
     * [下载图片]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function downImage()
    {
      $data =   $this->getbiliData();
      if(empty($data)){
            echo '没有数据';
            exit;
       }
        foreach ($data as $key => $value) {
          $video_img = $value['video_img'];
          if(empty($video_img) || $value['img_id'] > 0){
                continue;
          }
            $dir =  $this->getSaveDir();
            $urlInfo = parse_url($video_img);
            $pathArr = explode('/',$urlInfo['path']);
            $file_name = array_pop($pathArr);
            $file_path = $dir.'/'.$file_name;
            if(file_exists($file_path)){
                continue;
            }
            $is_result = $this->downcmd($file_path,$video_img);
            if($is_result){
                //上传七牛
                $ret =  $this->upload_file('bilibili-images',$file_path,$file_name);
            }
           //鏌ヨt_id
            $tWhere = sprintf("b_id=%d",$value['id']);
            $tRow = $this->biliObj->getTitleDataByWhere('id',$tWhere);
            $t_id = isset($tRow[0]['id']) ? $tRow[0]['id'] : 0;

            //写进数据库
            $db_data = array(
                't_id'       => $t_id,
                'image_path' => $file_path,
                'image_name' => $file_name
            );
            if($ret){
                $db_data['is_qiniu'] = 1;
                $db_data['add_qiniu_time'] = time();
            }
            $this->biliObj->addImages($db_data);
        }

    }

  /**
     * addTitlData
     *
     * [鍐欏叆title琛╙
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function  addTitlData()
    {
        $data =   $this->getbiliData();
        if(empty($data)){
            echo '娌℃湁鏁版嵁';
            exit;
        }
        foreach ($data as $key => $value) {
            $title = trim($value['title']);
            $where = sprintf("title='%s'",$title);
            $titleRow =  $this->biliObj->getTitleDataByWhere('id',$where);
            if(!empty($titleRow)){
                $titleUp = array(
                    'b_id' => $value['id'],
                );
                $where = array('title' => $title );
                $this->biliObj->updateTitleData($titleUp,$where);
                continue;
            }
            $save = array(
                'title' => $title,
                'b_id'  => $value['id'],
                'v_id'  => $value['v_id'],
                'img_id' => $value['img_id'],
                'add_date' => time()
            );
           $t_id =  $this->biliObj->addTitleData($save);
           $db['t_id']  = $t_id;
           $where = array('id' => $value['id']);
           $this->biliObj->updateBiliBili($db,$where);
        }
    }


    /**
     * getbiliData
     *
     * [获取主表数据]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @return array
     */
    public function getbiliData()
    {
        $data   = $this->biliObj->getbiliData();
        return $data;
    }
 

    /**
     * upBiliData
     *
     * [读取文件中的数据，写入主表]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function upBiliData()
    {
        $fp = fopen(INTERFACELOG, 'r') or die('file error');
        while (!feof($fp)) {
            $line = fgets($fp);
            if (empty($line)) {
                   continue;
            }
            list($video_bili_url, $url) = explode('>', $line);
            $video_bili_url = trim($video_bili_url);
            $url = trim($url);
            $db['video_interface']  = $url;
            $where = array('video_bili_url' => $video_bili_url);
            $this->biliObj->updateBiliBili($db,$where);
        }
    }

    /**
     * getSaveDir
     *
     * [获取保存的目录]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param string $type
     * @return string
     */
    public function getSaveDir($type='images')
    {
        $dirname = date('YmdH');
        $dir = "/data/images/".$dirname;
        if($type == 'video'){
            $dir = "/data/videos/".$dirname;
        }
        if(!is_dir($dir) || !file_exists($dir)){
            mkdir($dir,0777,true);
        }
         return $dir;
    }

    /**
     * downcmd
     *
     * [命令行下载]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $file_path
     * @param $remove_path
     * @return bool
     */
    public function downcmd($file_path,$remove_path)
    {
        $call = "axel -n 2 -o  {$file_path}  {$remove_path}";
        exec($call,$array); //执行命令
        usleep(1000);
        if(file_exists($file_path)){
            return true;
        }
        return false;
    }

    /**
     * upload_file
     *
     * [上传文件到七牛]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function upload_file($buck_name='',$file_path,$file_name)
    {
        $ret = $this->upload ->upload_file($buck_name,$file_path,$file_name);
        return $ret;
    }

}

$down = new Down();

$down->upBiliData();
echo 'database ok';

$down->addTitlData();
echo ' title OK ';

$down->downImage();
echo ' images ok ';

$down->downVideo();
echo ' videos ok ';
