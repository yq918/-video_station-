<?php 
namespace vhost\www\controllers\Traits;


trait DataTraits{
	/**
	 * [popularNowadays description]
	 * @return [type] [description]
	 */
    public function popularNowadays()
    { 
    	  $data =  $this->_Youtube->getYoutubeVideoList(2);
    	  if(empty($data)){
    	      return [];
    	  }
    	  $constant = $this->constant;
    	  foreach($data as $key => &$val){
                $val['img_url'] = $constant['youimages'].'/'.$val['image_file_name'];
    	  }
    	  return $data;
    }
  
  /**
   * [funny description]
   * @return [type] [description]
   */
  public function funny()
  {      
       $data =  $this->_Bili->getBiliVideoList(1);
       return $data;
  }

  public function music()
  { 
     $data =  $this->_Youtube->getYoutubeVideoList(3);
     return $data;
  } 


}
 ?>