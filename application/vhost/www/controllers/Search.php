<?php
/**
 * @name SearchController
 * @author zxr
 * @desc 视频搜索
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
  
use vhost\www\controllers\Traits\SearchTraits; 
use Base\Base;
use Base\Tools;     //libary 
 
 
class SearchController extends InitController {

	use SearchTraits; 
	
	public function init(){
		      parent::init();	 
	} 
	
	public function indexAction()
	{
		$keyword = $this->getRequest()->getQuery("keyword", "");
		$searchData = $this->getSearchData($keyword); 
		$this->getView()->assign("data", $searchData); 
		$this->assignOptions('index_index');
		$viewPath = VIEWPATH.'/www/search/index.phtml'; 
		$this->getView()->display($viewPath); 
	}  
}
