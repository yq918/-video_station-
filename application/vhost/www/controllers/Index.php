<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */

//use library\Aa\Cb;
use Aa\Bb;           //libary
use Base\Tools;     //libary
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

	public function  aaAction()
	{
          $data =  $this->popularNowadays();

           echo '<pre>';
          print_r($data);

         // $data = $this->funny();
 
          // print_r($data);
          exit; 
	}
	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/www/index/index/index/name/root 的时候, 你就会发现不同
     */
	public function indexAction()
	{
		$data['popular'] = $this->popularNowadays();
		$data['funny']   = $this->funny();
		$data['music']   = $this->music(); 
		$this->getView()->assign("data", $data);

        //当下流行
       // $data =  $Video->popularNowadays();

		/*
		 * 展示顺序为：
		 *   1.当下流行
		 *   2.搞笑
		 *   3.音乐
		 *   4.体育
		 *
		 * */
 
 
		//1. fetch query
		//$get = $this->getRequest()->getQuery("get", "default value");

		//$model = new Bb();
		//echo "library/Aa:".$model->selectSample();


      // $m = new SampleModel();
		//echo "<br/>models/Aa:".$m->selectSample();

		//2. fetch model
		// $model = new SampleModel();  //这里加载的是MODELS目录中的文件
	   //  echo "<br/>models:".$model->selectSample();

	 

		//3. assign
		//$this->getView()->assign("content", $model->selectSample());
		//$this->getView()->assign("name", $name);

		//$this->getView()->_tpl_dir=VIEWPATH;

		//var_dump($this->getView());exit;
 
	    $this->getView()->display(VIEWPATH.'/www/index/index.phtml');

		//4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
        //return true;
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
