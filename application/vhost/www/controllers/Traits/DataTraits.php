<?php 
namespace vhost\www\controllers\Traits;
use Base\Tools;
use Base\Base;

trait DataTraits{

    private static  $CAT_NUMBER = 5;

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
                $val['img_url'] = $this->generatePictureLinks($val['image_file_name'],2);
                $val['author'] = 'Small stone'; 
                $val['link'] =  Tools::generateLinks('/single/',$val['id']); 
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
           $value['img_url'] = $this->generatePictureLinks($value['image_file_name'],1);
           $value['author']  = 'Small stone';
           $n_number = mt_rand(1000,100000000);
           $value['play_count'] = number_format($n_number,2, ',', ' ').' views'; 
           $value['link'] =  Tools::generateLinks('/single/',$value['id']); 
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
                $val['img_url'] = $this->generatePictureLinks($val['image_file_name'],3);
                $val['author']  = 'Small stone';
                $val['link'] =  Tools::generateLinks('/single/',$val['id']);
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
                $val['img_url'] = $this->generatePictureLinks($val['image_file_name'],4);
                $val['author'] = 'Small stone'; 
                $val['link'] =  Tools::generateLinks('/single/',$val['id']);
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
     if($cat_id == '1'){
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
   * [popularNowadays description]
   * @return [type] [description]
   *栏目页当下流行栏目  
   */ 
    public function showsDetailsVideo($cat_id)
    {    
        $data     = $this->_Youtube->showsDetailsVideo($cat_id,0,16,self::$CAT_NUMBER);    
        $constant = Base::getConstant(); 
        foreach($data as $key => &$val){               
              $val['link'] =Tools::generateLinks('/shows/detail/',$cat_id.'_'.$key);  
             foreach ($val['list'] as $index => &$value) { 
                  $zoom   = false; 
                  $width  = 0;
                  $height = 0;
                 switch ($cat_id) {
                   case '3':
                     $zoom  = true;
                     $width = 250;
                     $height = 372;
                     break; 
                 } 
             $value['img_url'] = $this->generatePictureLinks($value['image_file_name'],$cat_id,$zoom,$width,$height); 
                 $value['author'] = 'Small stone'; 
                 $value['link'] =  Tools::generateLinks('/single/',$value['id']); 
                 $value['play_duration'] = $this->timeReplacement($value['play_duration']);
             } 
        } 
        return $data;
    }


    /**
     * [getCategory description]
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     * 获取分类数据
     */
    public function getCategory($cat_id)
    {
       $cateData = $this->_Youtube->getCategory($cat_id,self::$CAT_NUMBER,10); 
       if(!empty($cateData)){
          foreach ($cateData as $key => $value) {
             $value['link'] = Tools::generateLinks('/shows/detail/',$cat_id.'_'.$value['id']); 
             $value['column_name'] = trim($value['column_name']);
             $value['short_name']  = mb_substr($value['column_name'] ,0,12)."...";
             $cateData[$key] = $value;
            } 
       }  
       return $cateData; 
    }



}
 ?>