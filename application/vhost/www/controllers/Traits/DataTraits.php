<?php 
namespace vhost\www\controllers\Traits;
use controllers\Video\Youtube;

trait DataTraits{
    public function popularNowadays()
    {
    	  $Video = new Youtube();
    	  $data =  $Video->getYoutubeVideoList(2);
    	  return $data;
    }
}
 ?>