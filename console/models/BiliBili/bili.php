<?php 
namespace models\BiliBili;
use models\db\db;

class bili{
	 private   $conn = NULL;	
	 private   $table = 'cra_bilibili_images';
   private   $video_table = 'cra_bilibili_videos';
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

     return $this->conn->lastInsertId(); 
     }


  /**
   * [getImages description]
   * @param  string $filds [description]
   * @param  string $where [description]
   * @return [type]        [description]
   * 获取图片表具体数据
   */
   public function getImages($filds='',$where='')
   {
    $sql = sprintf("select %s from  %s where %s",$filds,$this->table,$where);
    $data = $this->conn->fetchAll($sql);
    return $data;
   }  


   public function addVideos($data)
   {
     $result  = $this->conn->insert($this->video_table, $data);
     return $this->conn->lastInsertId(); 
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



   /**
    * [updateBiliBili description]
    * @param  [type] $data  [description]
    * @param  [type] $where [description]
    * @return [type]        [description]
    * 修改主表
    */
    public function updateBiliBili($data,$where)
    { 
         $result =  $this->conn->update($this->M_table, $data, $where);
         return $result;
    }


   /**
    * [updateBiliImage description]
    * @param  [type] $data  [description]
    * @param  [type] $where [description]
    * @return [type]        [description]
    * 修改图片表
    */
  public function updateBiliImage($data,$where)
    { 
         $result =  $this->conn->update($this->table, $data, $where);
         return $result;
    }



 
 }
