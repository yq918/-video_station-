<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

//use library\Aa\Cb; 
use Base\Tools;     //libary
use Base\Base;
use models\Aa\SampleModel; //models

use controllers\Video\Youtube;
use controllers\Video\Bili;
use vhost\www\controllers\Traits\DataTraits; 

 
 
class IndexController extends InitController {

	use DataTraits;

	public  $constant = null ;
	public  $_Youtube = null;
	public  $_Bili    = null;

	public function init(){
		      parent::init();
		      $this->_Youtube = new Youtube();
		      $this->_Bili    = new Bili();
	}
  
	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/www/index/index/index/name/root 的时候, 你就会发现不同
     */
	public function indexAction()
	{ 	  
		/*
		 * 展示顺序为：
		 *   1.当下流行
		 *   2.搞笑
		 *   3.音乐
		 *   4.体育 
		 * */ 
		$data['popular'] = $this->popularNowadays(); 
		$data['funny']   = $this->funny();
		$data['music']   = $this->music(); 
		$data['sports']  = $this->sports();
		$this->getView()->assign("data", $data);
		$this->assignOptions('index_index');   
	    $this->getView()->display(VIEWPATH.'/www/index/index.phtml'); 
		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        //return true;
	} 


    /**
     * [transitPageAction footer链接中转页面]
     * @return [type] [description]
     */
	public function transitPageAction()
	{
		$page = $this->getRequest()->getParam('page');
		$filePath = VIEWPATH.'/public/page/'.$page.'.phtml'; 
		if(file_exists($filePath)){
			 $this->assignOptions('index_index'); 
             $this->getView()->display($filePath); 
		}else{
             Base::notFound(); 
		}  
	}


}
