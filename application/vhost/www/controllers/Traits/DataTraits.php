<?php 
namespace vhost\www\controllers\Traits;
use Base\Tools;
use Base\Base;

trait DataTraits{
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
  public function generatePictureLinks($image_file_name,$cat_id)
  {
     $constant = Base::getConstant();
     $domain   = $constant['static_you_images'];
     if($cat_id == '1'){
        $domain = $constant['static_bili_images'];
     }
     $img_url = $domain.'/'.$image_file_name.'?v='.$constant['static_version'];
     return $img_url;
  } 




  /**
   * [popularNowadays description]
   * @return [type] [description]
   *栏目页当下流行栏目  
   */ 
    public function showsDetailsVideo($cat_id)
    {    
        $data   = $this->_Youtube->showsDetailsVideo($cat_id,0,16);  
        $constant = Base::getConstant(); 
        foreach($data as $key => &$val){
             foreach ($val['list'] as $index => &$value) {
                 $value['img_url'] = $this->generatePictureLinks($value['image_file_name'],2);
                 $value['author'] = 'Small stone'; 
                 $value['link'] =  Tools::generateLinks('/single/',$value['id']); 
                 $value['play_duration'] = $this->timeReplacement($value['play_duration']);
             } 
        } 
        return $data;
    }



}
 ?>