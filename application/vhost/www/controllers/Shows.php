<?php
/**
 * @name ShowsController
 * @author zxr
 * @desc youtube视频栏目控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
 
use Base\Tools;     //libary 
use controllers\Video\Youtube;
use controllers\Video\Bili;
use controllers\Cat\Category;
use vhost\www\controllers\Traits\DataTraits; 
use Base\Base;
 
 
class ShowsController extends InitController {

	use DataTraits;

	public  $constant = null ;
	public  $_Youtube = null;
	public  $_Bili    = null;
	private $_Cat     = null;
 
	public function init(){
		      parent::init();
		      $this->_Youtube = new Youtube();
		      $this->_Bili    = new Bili();
		      $this->_Cat     = new Category();
	}

	 
	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/www/index/index/index/name/root 的时候, 你就会发现不同
     */
	public function indexAction()
	{   
	   $sing = $this->getRequest()->getQuery("sing", "");
	   $cat_id = Tools::parameterDecryption($sing);  
	   if(empty($cat_id)){
	   	     Base::notFound();
	   }	 
	    $category = $this->getCategory($cat_id);  
        $data = $this->showsDetailsVideo($cat_id);  
		$this->getView()->assign("data", $data);
		$this->getView()->assign("category",$category);
		$this->assignOptions('index_index');
		$viewPath = VIEWPATH.'/www/shows/index.phtml';
		if($cat_id ==  Base::getCatTypeData('music') ){
             $viewPath = VIEWPATH.'/www/shows/movies.phtml';   
		}  
		$this->getView()->display($viewPath); 
	}
 
    /**
     * [detailAction description]
     * @return [type] [description]
     */
	public function detailAction()
	{
         $sing = $this->getRequest()->getQuery("sing", "");
	     $sing = Tools::parameterDecryption($sing); 
	     if(empty($sing)){
	     	 Base::notFound();
	     } 
	     list($cat_id,$column_id) = explode('_',$sing); 
	     $columnDatail = $this->showsColumnDetailsVideo($cat_id,$column_id,0,60);   
	     $category = $this->getCategory($cat_id);   
		 $this->getView()->assign("category",$category); 
		 $this->getView()->assign("columnDatail", $columnDatail); 
		 $this->getView()->assign("data", []);
         $viewPath = VIEWPATH.'/www/shows/index.phtml';
		 if($cat_id ==  Base::getCatTypeData('music') ){
             $viewPath = VIEWPATH.'/www/shows/movies.phtml';   
		 }  
	     $this->assignOptions('index_index');
	     $this->getView()->display($viewPath);  
	} 
 }
