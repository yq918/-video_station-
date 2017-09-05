<?php 
namespace vhost\www\controllers\Traits;
use controllers\Comment\Comment; 
use Base\Tools;
use Base\Base;



trait DataTraits{

    private static  $CAT_NUMBER = 5;
    public static   $TYPE_BILI = 'bili';
    public static   $TYPE_YOUTUBE = 'youtube';

	/**
	 * [popularNowadays description]
	 * @return [type] [description]
   *首页当下流行栏目  
	 */ 
    public function popularNowadays()
    {  
        $cat_id = Base::getCatTypeData('popular'); 
    	  $data   = $this->_Youtube->getYoutubeVideoList($cat_id,0,3); 
    	  $constant = Base::getConstant();
    	  foreach($data as $key => &$val){  
                $val['img_url'] = $this->generatePictureLinks($val['image_file_name'],$cat_id);
                $val['author'] = 'Small stone'; 
                $val['link'] =  Tools::generateLinks('/single/',array('sing' => $val['id'],'type' => self::$TYPE_YOUTUBE) );  
            }
    	  return $data;
    }
  
  /**
   * [funny description]
   * @return [type] [description]
   * 搞笑栏目数据
   */
  public function funny()
  {      
       $cat_id = Base::getCatTypeData('funny');
       $data   = $this->_Bili->getBiliVideoList($cat_id,0,4);
       $constant =  Base::getConstant();
       foreach ($data as $key => &$value) {
           $value['img_url'] = $this->generatePictureLinks($value['image_file_name'],$cat_id);
           $value['author']  = 'Small stone';
           $n_number = mt_rand(1000,100000000);
           $value['play_count'] = number_format($n_number,2, ',', ' ').' views'; 
           $value['link'] =  Tools::generateLinks('/single/',array('sing' => $value['id'],'type' => self::$TYPE_BILI )); 
       }
       return $data;
  }

  /**
   * [music description]
   * @return [type] [description]
   * 音乐栏目数据
   */
  public function music()
  { 
     $cat_id = Base::getCatTypeData('music');
     $data   = $this->_Youtube->getYoutubeVideoList($cat_id,0,8); 
     if(empty($data)){
        return [];
     }
     $constant = Base::getConstant();
     foreach($data as $key => &$val){
                $val['img_url'] = $this->generatePictureLinks($val['image_file_name'],$cat_id);
                $val['author']  = 'Small stone';
                $val['link'] =  Tools::generateLinks('/single/',array('sing' => $val['id'],'type' => self::$TYPE_YOUTUBE) );
                $val['play_time'] =  $this->timeReplacement($val['play_duration']);
        } 
     return array_chunk($data,4);  
  } 

  /**
   * [sports description]
   * @return [type] [description]
   * 体育栏目数据
   */
  public function sports()
  {
     $cat_id = Base::getCatTypeData('sports');
     $data =  $this->_Youtube->getYoutubeVideoList($cat_id,0,8); 
     if(empty($data)){
         return [];
     }
     $constant = Base::getConstant();
     foreach($data as $key => &$val){
                $val['img_url'] = $this->generatePictureLinks($val['image_file_name'],$cat_id);
                $val['author'] = 'Small stone'; 
                $val['link'] =  Tools::generateLinks('/single/', array('sing' => $val['id'],'type' => self::$TYPE_YOUTUBE) );
                $val['play_time'] = $this->timeReplacement($val['play_duration']);
        } 
     return array_chunk($data,4);  
  }


  /**
   * [timeReplacement description]
   * @param  [type] $play_duration [description]
   * @return [type]                [description]
   * 播放时间格式化
   */
  public function timeReplacement($play_duration)
  { 
    $play_time = str_replace(array('- Duration:','minutes','seconds',',',' ','.'), array('',':','','','',''), $play_duration);
     if(strpos($play_time,':') === false){ 
         $play_time = "0:".$play_time;
     }
    return $play_time; 
  }

  /**
   * [generatePictureLinks description]
   * @param  [type] $image_file_name [description]
   * @param  [type] $cat_id          [description]
   * @return [type]                  [description]
   * 生成图片地址
   */
  public function generatePictureLinks($image_file_name,$cat_id,$zoom = false,$widht = 0,$height = 0)
  {
     $constant = Base::getConstant();
     $domain   = $constant['static_you_images'];
     if($cat_id == Base::getCatTypeData('funny')){
        $domain = $constant['static_bili_images'];
     }
     $zoomStr = "";
     if($zoom){
         $zoomStr = '&imageView2/1/w/'.$widht.'/h/'.$height.'/q/80|imageslim';
     } 
     $img_url = $domain.'/'.$image_file_name.'?v='.$constant['static_version'].$zoomStr;
     return $img_url;
  } 


 /**
  * [generateVideoLinks description]
  * @param  [type]  $video_name      [description]
  * @param  [type]  $cat_id          [description]
  * @param  boolean $zoom            [description]
  * @param  integer $widht           [description]
  * @param  integer $height          [description]
  * @return [type]                   [description]
  * 生成视频路径
  */
  public function generateVideoLinks($video_name,$cat_id)
  {
     $constant = Base::getConstant();
     $domain   = $constant['static_you_video'];
     if($cat_id == Base::getCatTypeData('funny')){
        $domain = $constant['static_bili_video'];
     }
     $video_url = $domain.'/'.$video_name;
     return $video_url;
  }





  /**
   * [popularNowadays description]
   * @return [type] [description]
   *栏目页当下流行栏目  
   */ 
    public function showsDetailsVideo($cat_id,$start=0,$limit = 6,$order='sort DESC')
    {
          //获取有哪些分类
          $cateData = $this->_Cat->getCategory($cat_id,0,self::$CAT_NUMBER);  
          $c = count($cateData);
          if($c  == 1){
            $limit = 16;
          }
          if($c == 2){
             $limit = 8;
          } 
          $cat_list = array_column($cateData,'column_name','id'); 
          $list = array(); 
          foreach ($cateData as $k => $v) {
               $id    = $v['id'];
               $where = "cat_column_id = {$id} "; 
               $data  =  $this->_Youtube->getYoutubeVideoList($cat_id,$start,$limit,$order,$where); 
               $data  =  $this->ProcessingVideoData($cat_id,$data); 
               $list[$k]['link']  = Tools::generateLinks('/shows/detail/', array('sing' => $cat_id.'_'.$id, 'type' => self::$TYPE_YOUTUBE ));  
               $list[$k]['title'] = isset($cat_list[$id]) ? $cat_list[$id] : '';
               $list[$k]['list'] = $data;  
          }  
        return $list;
    }

    /**
     * [showsColumnDetailsVideo description]
     * @param  [type]  $id    [description]
     * @param  integer $start [description]
     * @param  integer $limit [description]
     * @param  string  $order [description]
     * @return [type]         [description]
     */
    public function showsColumnDetailsVideo($cat_id,$id,$start=0,$limit = 6,$order='sort DESC')
    {
       $cat_list =  $this->_Cat->getCategoryById($id,$start,$limit,$order);
       $list  = array(); 
       $where = "cat_column_id = {$id} "; 
       $data  =  $this->_Youtube->getYoutubeVideoList($cat_id,$start,$limit,$order,$where);
       $data  =  $this->ProcessingVideoData($cat_id,$data); 
       $list  =  array('cat_list' => $cat_list[0],'list' => $data ); 
       return $list; 
    }  

    /**
     * [ProcessingVideoData description]
     * @param [type] $cat_id [description]
     * @param array  $data   [description]
     */
   public function ProcessingVideoData($cat_id,$data = array() )
   {
      foreach ($data as $key => $value) {
             $zoom = $width = $height = 0;
             switch ($cat_id) {
                case Base::getCatTypeData('music'):
                       $zoom   = true;
                       $width  = 250;
                       $height = 372;
                       break; 
               } 
           $value['img_url'] = $this->generatePictureLinks($value['image_file_name'],$cat_id,$zoom,$width,$height); 
           $value['author'] = 'Small stone'; 
           $value['link'] =  Tools::generateLinks('/single/',array('sing' => $value['id'],'type' => self::$TYPE_YOUTUBE)); 
           $value['play_duration'] = $this->timeReplacement($value['play_duration']); 
           $data[$key] = $value;
       }  
      return $data;     
   }


 
    /**
     * [getBiliDetailsVideo description]
     * @param  [type]  $cat_id [description]
     * @param  integer $start  [description]
     * @param  integer $limit  [description]
     * @param  string  $order  [description]
     * @return [type]          [description]
     * 获取B站搞笑视频
     */
    public function getBiliDetailsVideo($cat_id,$start=0,$limit = 24,$order='sort DESC',$img_width = 0,$img_height = 0)
    { 
         //获取有哪些分类
          $cateData = $this->_Cat->getCategory($cat_id,0,5);  
          $c = count($cateData);
          if($c == 2){
             $limit = 12;
          }
          if($c == 3){
            $limit = 8;
          } 
          if($c > 3){
            $limit = 6;
          }

          if(empty($img_width)){
            $img_width = 213;
          }
          if(empty($img_height)){
             $img_height = 120;
          }

          $cat_list = array_column($cateData,'column_name','id');
          $list = array(); 
          foreach ($cateData as $key => $value) {
               $id = $value['id']; 
               $where = "cat_column_id ={$id} ";
               $data = $this->_Bili->getBiliVideoList($cat_id,$start,$limit,$order,$where);  
               foreach ($data as $key => $value) {
                  $value['link'] =  Tools::generateLinks('/single/',array('sing' => $value['id'],'type' => self::$TYPE_BILI)); 
                  $value['img_url'] = $this->generatePictureLinks($value['image_file_name'],$cat_id,true,$img_width,$img_height); 
                  $value['author']  = 'Small stone'; 
                  $value['playback_times'] = number_format($value['playback_times'],2,",",".");
                  $data[$key] = $value;
               } 
               $list[$key]['column_name'] = isset($cat_list[$id]) ? $cat_list[$id] : '';
               $list[$key]['list'] = $data; 
          }  
          return $list; 
    }

   

      /**
       * [getVideoDataById description]
       * @param  [type] $id    [description]
       * @param  string $field [description]
       * @return [type]        [description]
       * 根据ID 获取视频详细数据
       */
      public function getVideoDataById($id,$type='')
      { 
        if($type == self::$TYPE_BILI){
             $data =  $this->getVideoDetailByBili($id);
         }else{
             $data = $this->getVideoDetailByYoutuBe($id); 
         } 
         return $data; 
      }

  
     /**
      * [getVideoDetailByBili B站视频详情页]
      * @param  [type] $id    [description]
      * @param  string $field [description]
      * @return [type]        [description]
      */
     public function getVideoDetailByBili($id,$field ='')
     { 
        $data = $this->_Bili->getVideoDataById($id,$field);   
          if(empty($data)){
             return $data;
          }
          foreach ( $data as $key => &$value) {
              $value['img_url']   = $this->generatePictureLinks($value['image_file_name'],$value['cat_id'],true,710,370); 
              $value['video_url'] = $this->generateVideoLinks($value['video_file_name'],$value['cat_id']);
          } 
         $data =  array_pop($data);  
         $recommend = $this->getBiliDetailsVideo($data['cat_id'],0,15,'sort DESC',320,180);  
         if(empty($recommend)){
             $data['recommend'] = $recommend;
         }else{
            $recommend  = array_pop($recommend);
             $data['recommend'] = $recommend['list'];
         } 
         return $data; 
     }

     /**
      * [getVideoDetailByYoutuBe youtube视频详情页]
      * @param  [type] $id [description]
      * @return [type]     [description]
      */
     public function getVideoDetailByYoutuBe($id,$field='')
     { 
       $data = $this->_Youtube->getVideoDataById($id,$field);  
       if(empty($data)) return $data;
       foreach ( $data as $key => &$value) {
              $value['img_url']   = $this->generatePictureLinks($value['image_file_name'],$value['cat_id'],true,710,370); 
              $value['video_url'] = $this->generateVideoLinks($value['video_file_name'],$value['cat_id']);
          } 
         $data =  array_pop($data);   
         $recommend  =  $this->_Youtube->getYoutubeVideoList($data['cat_id'],0,15); 
         foreach ($recommend as $key => $v) {
              $v['link'] =  Tools::generateLinks('/single/',array('sing' => $v['id'],'type' => self::$TYPE_YOUTUBE)); 
              $v['img_url'] = $this->generatePictureLinks($v['image_file_name'],$data['cat_id'],true,320,180); 
              $v['author']  = 'Small stone'; 
              $v['playback_times'] = str_replace('views','',$v['video_time_content']);
              $recommend[$key]  = $v;
          }  
         $data['recommend'] = $recommend; 
        return $data; 
     }  

    /**
     * [getCommitList 获取评论列表]
     * @param  [type] $video_id [description]
     * @param  [type] $type     [description]
     * @return [type]           [description]
     */
    public function getCommitList($video_id,$type,$start = 0,$limit = 20)
    {
        $Comment = new Comment(); 
        $where = "video_id={$video_id} AND video_type={$type} AND status=1 "; 
        $fields = " `id`,`video_id`,`video_type`,`comment`,`user_id` ";
        $list = $Comment->getCommitByWhere($where,$fields,$start,$limit);
        return $list;
    }



    
 
    /**
     * [getCategory description]
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     * 获取分类数据
     */
    public function getCategory($cat_id,$start = 0, $limit = 10)
    {
      //$cateData = $this->_Cat->getCategory($cat_id,self::$CAT_NUMBER,10); 
       $cateData = $this->_Cat->getCategory($cat_id,$start,$limit); 
       if(!empty($cateData)){
          foreach ($cateData as $key => $value) {
             $value['link'] = Tools::generateLinks('/shows/detail/',array('sing' => $cat_id.'_'.$value['id'])); 
             $value['column_name'] = trim($value['column_name']);
             $value['short_name']  = mb_substr($value['column_name'] ,0,12)."...";
             $cateData[$key] = $value;
            } 
       }  
       return $cateData; 
    }





}
