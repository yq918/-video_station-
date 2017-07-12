<?php 
namespace vhost\www\controllers\Traits;
use controllers\Video\Youtube;
use controllers\Video\Bili;

trait DataTraits{
	/**
	 * [popularNowadays description]
	 * @return [type] [description]
	 */
    public function popularNowadays()
    {
    	  $Video = new Youtube();
    	  $data =  $Video->getYoutubeVideoList(2);
    	  return $data;
    }
  
  /**
   * [funny description]
   * @return [type] [description]
   */
  public function funny()
  {
       $Video = new Bili();
       $data =  $Video->getBiliVideoList(1);
       return $data;
  } 


}
 ?>