<?php 
namespace models\BiliBili;
use models\db\db;

class bili{
	 private   $conn = NULL;	
	 private   $table = 'cra_bilibili_images';
   private   $video_table = 'cra_bilibili_videos';
   private   $title_table = 'cra_bilibili_title';
	 private   $M_table = 'bilibili';
   private   $Down_table = 'cra_bilibili_download';

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

  /**
   * [addDownData description]
   */
  public function addDownData($data)
  {
     $result  = $this->conn->insert($this->Down_table, $data); 
     return $this->conn->lastInsertId(); 
 }

/**
 * [addVideos description]
 * @param [type] $data [description]
 */
   public function addVideos($data)
   {
     $result  = $this->conn->insert($this->video_table, $data);
     return $this->conn->lastInsertId(); 
   }

/**
 * [getVideoss description]
 * @param  string $filds [description]
 * @param  string $where [description]
 * @return [type]        [description]
 */
   public function getVideos($filds='',$where='')
   {
    $sql = sprintf("select %s from  %s where %s",$filds,$this->video_table,$where);
    $data = $this->conn->fetchAll($sql);
    return $data;
   }  


/**
 * [getbiliData description]
 * @return [type] [获取 bilibili 表数据]
 */
     public function getbiliData()
     {
  	   $data = $this->conn->fetchAll("select * from  ".$this->M_table);
  	   return $data;
     }

   /**
    * [getbiliDataByWhere description]
    * @param  string $filds [description]
    * @param  string $where [description]
    * @return [type]        [description]
    * 传入字段与条件 获取bilibili表数据
    */
   public function getbiliDataByWhere($filds='',$where='')
   {
    $sql = sprintf("select %s from  %s where %s",$filds,$this->M_table,$where);
    $data = $this->conn->fetchAll($sql);
    return $data;
   }  

    /**
     * [addBiliBili description]
     * @param [type] $data [description]
     * 写入主表
     */
    public function addBiliBili($data)
     {
      $result  = $this->conn->insert($this->M_table, $data);
      return $this->conn->lastInsertId(); 
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


/**
 * [addTitleData description]
 * @param array $data [description]
 * 写入标题表
 */
  public function addTitleData($data = array())
  { 
    $result  = $this->conn->insert($this->title_table, $data);
    return $this->conn->lastInsertId(); 
  }

  /**
   * [updateTitleData description]
   * @param  [type] $data  [description]
   * @param  [type] $where [description]
   * @return [type]        [description]
   * 更改标题表
   */
  public function updateTitleData($data,$where)
  { 
         $result =  $this->conn->update($this->title_table, $data, $where);
         return $result;
 }
 

  /**
   * [getTitleDataByWhere description]
   * @param  string $filds [description]
   * @param  string $where [description]
   * @return [type]        [description]
   * 查询标题表
   */
  public function getTitleDataByWhere($filds='',$where='')
   {
    $sql = sprintf("select %s from  %s where %s",$filds,$this->title_table,$where);
    $data = $this->conn->fetchAll($sql);
    return $data;
   }  

 }
