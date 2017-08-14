<?php
/**
 * @name FunnyController
 * @author zxr
 * @desc B站视频栏目控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
 
use Base\Tools;     //libary 
use controllers\Video\Bili;
use controllers\Cat\Category;
use vhost\www\controllers\Traits\DataTraits; 
use Base\Base;
 
 
class FunnyController extends InitController {

	use DataTraits;

	public  $constant = null ;	
	public  $_Bili    = null;
	private $_Cat     = null;

	public function init(){
		      parent::init();		     
		      $this->_Bili    = new Bili();
		      $this->_Cat     = new Category();
	} 
	
	public function indexAction()
	{    
	   $sing = $this->getRequest()->getQuery("sing", "");
	   $cat_id = Tools::parameterDecryption($sing);  
	   if(empty($cat_id)){
	   	      Base::notFound();
	   } 
        $data = $this->getBiliDetailsVideo($cat_id);  
		$this->getView()->assign("data", $data); 
		$this->assignOptions('index_index');
		$viewPath = VIEWPATH.'/www/funny/funny.phtml'; 
		$this->getView()->display($viewPath); 
	}  
}
