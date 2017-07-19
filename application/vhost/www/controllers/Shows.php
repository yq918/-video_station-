<?php
/**
 * @name ShowsController
 * @author zxr
 * @desc 视频栏目控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
 
use Base\Tools;     //libary 
use controllers\Video\Youtube;
use controllers\Video\Bili;
use vhost\www\controllers\Traits\DataTraits; 
use Base\Base;
 
 
class ShowsController extends InitController {

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
	   $cat_id = Tools::parameterDecryption($sing); 
	   if(empty($cat_id)){
	   	     Base::notFound();
	   }  
        $data = $this->showsDetailsVideo($cat_id); 
		$this->getView()->assign("data", $data);
		$this->assignOptions('index_index');
		$viewPath = VIEWPATH.'/www/shows/index.phtml';
		if($cat_id ==  Base::getCatTypeData('music') ){
             $viewPath = VIEWPATH.'/www/shows/movies.phtml';   
		}
		$this->getView()->display($viewPath);



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
