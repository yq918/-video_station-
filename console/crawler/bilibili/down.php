<?php

/**
 * Class Down
 *
 * [����Bվ����Ƶ��ͼƬ]
 */
include 'download.php';
include '../../init.php';
use models\db\db;
use library\HTTP\HttpCurl;
use models\BiliBili\bili;
use library\qiniu\Upload;

class Down
{
    /**
     * @var bili|null
     */
    private  $biliObj = null;

    public  function __construct()
    {
      $this->biliObj = new bili();
      $this->upload = new Upload();
    }
    /**
     * downVideo
     *
     * [������Ƶ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function  downVideo()
    {
        $class = new download();
        $class->run();
    }

    /**
     * downImage
     *
     * [����ͼƬ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function downImage()
    {
      $data =   $this->getbiliData();
      if(empty($data)){
            echo 'û������';
            exit;
       }
        foreach ($data as $key => $value) {
          $video_img = $value['video_img'];
          if(empty($video_img)){
                continue;
          }
            $dir =  $this->getSaveDir();
            $urlInfo = parse_url($video_img);
            $pathArr = explode('/',$urlInfo['path']);
            $file_name = array_pop($pathArr);
            $file_path = $dir.'/'.$file_name;
            $is_result = $this->downcmd($file_path,$video_img);
            if($is_result){
                //�ϴ���ţ
                $ret =  $this->upload_file('bilibili-images',$file_path,$file_name);
            }
            //д�����ݿ�
            $db_data = array(
                't_id'       => $value['id'],
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
     * getbiliData
     *
     * [��ȡ��������]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @return array
     */
    public function getbiliData()
    {
        $data   = $this->biliObj->getbiliData();
        return $data;
    }

    /**
     * upDbData
     *
     * [�޸�����]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function upDbData()
    {

    }

    /**
     * upBiliData
     *
     * [��ȡ�ļ��е����ݣ�д������]
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
     * [��ȡ�����Ŀ¼]
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
     * [����������]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $file_path
     * @param $remove_path
     * @return bool
     */
    public function downcmd($file_path,$remove_path)
    {
        $call = "axel -n 2 -o  {$file_path}  {$remove_path}";
        exec($call,$array); //ִ������
        usleep(1000);
        if(file_exists($file_path)){
            return true;
        }
        return false;
    }

    /**
     * upload_file
     *
     * [�ϴ��ļ�����ţ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function upload_file($buck_name='',$file_path,$file_name)
    {
        $ret = $this->upload ->upload_file('bilibili-images',$file_path,$file_name);
        return $ret;
    }

}

$Down = new Down();
$Down->upBiliData();
$Down->downImage();