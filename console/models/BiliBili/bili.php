<?php 
namespace models\BiliBili;
use models\db\db;

class bili{
	 private   $conn = NULL;	
	 private   $table = 'cra_bilibili_images';
	 private   $M_table = 'bilibili';

     public function __construct() 
     {
     	$this->conn = db::getinstance();  	 
     }

/**
 * [addImages description]
 * @param [type] $data [写入cra_bilibili_images表]
 */
     public function addImages($data)
     {

      $result  = $this->conn->insert($this->table, $data);
      return $result;
     }


/**
 * [getbiliData description]
 * @return [type] [获取 bilibili 表数据]
 */
     public function getbiliData()
     {
  	   $data = $this->conn->fetchAll("select id,video_img,video_path from  ".$this->M_table);
  	   return $data;
     }



 
 }
