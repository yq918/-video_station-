<?php 
namespace vhost\www\controllers\Traits; 
use Base\Tools;
use Base\Base;
use controllers\Video\Bili;
use controllers\Video\Youtube; 

trait SearchTraits{

      public static   $TYPE_BILI = 'bili';
      public static   $TYPE_YOUTUBE = 'youtube';

     /**
      * [getSearchData 搜索视频]
      * @param  [type] $keyword [description]
      * @return [type]          [description]
      */
    public function getSearchData($keyword)
    {
         $searchBiliData = $this->getSearchDataByBili($keyword);
         $searchYoutubeData =   $this->getSearchDataByYoutube($keyword);
         return array_merge($searchBiliData,$searchYoutubeData); 
    }

   
    /**
     * [getSearchDataByBili 搜索B站的视频]
     * @param  [type] $keyword [description]
     * @return [type]          [description]
     */
    public function getSearchDataByBili($keyword)
    {
      $Bili = new Bili();
      $where  = "video_title like '%{$keyword}%' AND img_upload=1 AND video_upload=1";
      $fields = 'id,b_id,playback_times,video_title,image_file_name,video_title';
      $data = $Bili->getDataByWhere($where,$fields);
      $cat_id =   Base::getCatTypeData('funny');
      $data = $this->processData($data,$cat_id);   
      return $data;
    }


    /**
     * [getSearchDataByBili 搜索youtube的视频]
     * @param  [type] $keyword [description]
     * @return [type]          [description]
     */
    public function getSearchDataByYoutube($keyword)
    {
      $youtubeObj = new Youtube();
      $where  = "video_title like '%{$keyword}%' AND img_upload=1 AND video_upload=1";
      $fields = 'id,you_id,playback_times,video_title,image_file_name,video_title';
      $data = $youtubeObj->getDataByWhere($where,$fields);
      $cat_id = Base::getCatTypeData('music');
      $data = $this->processData($data,$cat_id);  
      return $data;
    }

 
    public function processData($data,$cat_id)
    {
         $type = self::$TYPE_YOUTUBE;
        if($cat_id == Base::getCatTypeData('funny')){
           $type = self::$TYPE_BILI;
        }  
        foreach($data as $key => &$val){  
                $val['img_url'] = $this->generatePictureLinks($val['image_file_name'],$cat_id);
                $val['author'] = 'Small stone'; 
                $params = array('sing' => $val['id'],'type' => $type ) ;
                $val['link'] =  Tools::generateLinks('/single/',$params );  
                $val['play_time'] = $val['playback_times'];
            } 
         return $data; 
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
  
}
