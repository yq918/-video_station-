<?php 
namespace vhost\www\controllers\Traits;
use Base\Tools;
use Base\Base;

trait DataTraits{
	/**
	 * [popularNowadays description]
	 * @return [type] [description]
	 */
    public function popularNowadays()
    { 
    	  $data =  $this->_Youtube->getYoutubeVideoList(2,0,3);
    	  if(empty($data)){
    	       return [];
    	  }
    	  $constant = Base::getConstant();
    	  foreach($data as $key => &$val){
                $val['img_url'] = $constant['static_you_images'].'/'.$val['image_file_name'].'?v='.$constant['static_version'];
                $val['author'] = 'Small stone';
                $link = Tools::parameterEncryption($val['id']);
                $val['link'] = '/single/?sing='.$link;
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