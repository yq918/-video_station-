<?php
/**
 * Created by PhpStorm.
 * @Copyright:event
 * @Author:zhangxuanru [zhangxuanru@eventmosh.com]
 * @Date: 2017/7/7 10:25
 * 对youtube没有上传七牛成功的 重新尝试上传
 */
include '../init.php';
include '../config/conf.php';
use models\db\db;
use library\qiniu\Upload;
use models\BiliBili\bili;
use models\YoutuBe\youtube;

class uploadAgain
{
    private $upload;
    private $youtubeObj;

    public function  __construct()
    {
        $this->upload = new Upload();
        $this->youtubeObj = new youtube();
    }

    public function runYoutubeVideo()
    {
        $where = "video_upload=0 AND video_path!='' ";
        $fields = 'id,video_path,video_file_name';
        $data =  $this->youtubeObj->getDownloadData($fields,$where);
        if(empty($data)){
            echo '都已上传成功，没有要处理的数据';
            exit;
        }
        foreach($data as $key => $val){
             $video_path = trim($val['video_path']);
             $file_name  = trim($val['video_file_name']);
             $ret = $this->upload->upload_file('youtube-videos',$video_path,$file_name);
            if($ret == false){
                 usleep(1000);
                 $ret = $this->upload->upload_file('youtube-videos',$video_path,$file_name);
            }
            if($ret){
                $data  = array('video_upload' => 1);
                $where = array('id' => $val['id']);
                $this->youtubeObj->updateDownData($data,$where);
            }
        }
    }

    public function runYoutubeImages()
    {
        $where = "img_upload=0 AND image_path!='' ";
        $fields = 'id,image_path,image_file_name';
        $data =  $this->youtubeObj->getDownloadData($fields,$where);
        if(empty($data)){
            echo '都已上传成功，没有要处理的数据';
            exit;
        }
        foreach($data as $key => $val){
            $image_path = trim($val['image_path']);
            $image_file_name  = trim($val['image_file_name']);
            $ret = $this->upload->upload_file('youtube-images',$image_path,$image_file_name);
            if($ret == false){
                usleep(1000);
                $ret = $this->upload->upload_file('youtube-images',$image_path,$image_file_name);
            }
            if($ret){
                $data  = array('img_upload' => 1);
                $where = array('id' => $val['id']);
                $this->youtubeObj->updateDownData($data,$where);
            }
        }
    }
}

$uploadAgain = new uploadAgain();
$uploadAgain->runYoutubeVideo();
$uploadAgain->runYoutubeImages();