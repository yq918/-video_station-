<?php
/**
 * @name SingleController
 * @author root
 * @desc 详情页控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */ 
use Base\Tools;     //libary
use Base\Base; 
use controllers\Video\Youtube;
use controllers\Video\Bili;
use vhost\www\controllers\Traits\DataTraits;  
use vhost\www\controllers\Traits\UserTraits; 
use controllers\Cat\Category;
 
class SingleController extends InitController {

	use DataTraits;
	use UserTraits;

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
		$type = $this->getRequest()->getQuery("type", "");
		if(empty($sing) || empty($type)) {
                Base::notFound();
		  }
        $id   = Tools::parameterDecryption($sing); 
        $type = Tools::parameterDecryption($type);  
        $detail = $this->getVideoDataById($id,$type);
        if(empty( $detail)){
        	 Base::notFound();
        }  
        $formKey = $this->generateSessionKey(); 
        $this->assignOptions('single_index');
        $this->getView()->assign('detail',$detail);
        $this->getView()->assign('id',$id);
        $this->getView()->assign('type',$type); 
        $this->getView()->assign('formKey',$formKey);
	    $this->getView()->display(VIEWPATH.'/www/single/single.phtml'); 
	}
 
}
