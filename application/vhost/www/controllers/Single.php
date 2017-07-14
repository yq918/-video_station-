<?php
/**
 * @name SingleController
 * @author root
 * @desc 详情页控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

 
use Aa\Bb;           //libary
use Base\Tools;     //libary
use Base\Base;
use models\Aa\SampleModel; //models

use controllers\Video\Youtube;
use controllers\Video\Bili;
use vhost\www\controllers\Traits\DataTraits; 
 
 
class SingleController extends InitController {

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
		$sing = $this->getRequest()->getQuery("sing", "");
		if(empty($sing)){
                Base::notFound();
		  }
       $id  = Tools::parameterDecryption($sing); 
 
        $this->assignOptions('single_index');
	    $this->getView()->display(VIEWPATH.'/www/index/single.phtml');

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        //return true;
	}


	public function singleAction()
	{
        echo 'aaaa';
	}



	public function testAction()
	{

		$s = new \swoole_server();
		$s->after();

		echo 'test';
		return false;
	}


	public function abcAction()
	{
//		$database = YaConf::get('Mdatabases.db');
//
//		print_r($database);
//		exit;


		echo __FUNCTION__;
		return false;
	}

}
