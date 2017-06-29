<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract{

    public function _initConfig() {
		//把配置保存起来
		$arrConfig = Yaf\Application::app()->getConfig();
		Yaf\Registry::set('config', $arrConfig);

		//关闭自动加载模板目录
       // Yaf_Dispatcher::getInstance()->autoRender(FALSE);
 	    Yaf\Dispatcher::getInstance()->catchException(TRUE);
        Yaf\Dispatcher::getInstance()->throwException(TRUE);

	}

	public function _initPlugin(Yaf\Dispatcher $dispatcher) {
		//注册一个插件
		//$objSamplePlugin = new SamplePlugin();
		//$dispatcher->registerPlugin($objSamplePlugin);
	}

	//在这里注册自己的路由协议,默认使用简单路由
	public function _initRoute(Yaf\Dispatcher $dispatcher) {
		//这里是读取 application.ini中的路由设置
	 	$router = Yaf\Dispatcher::getInstance()->getRouter();
		$router->addConfig(Yaf\Registry::get("config")->routes);

		$routers = require 'routes.php';
		foreach ($routers as $routekey => $route) {
			 $router->addRoute($routekey, $route);
		}

	}
	
	public function _initView(Yaf\Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
	}

	//通过YACONF 读取MYSQL的配置信息
	public function _initDB(Yaf\Dispatcher $dispatcher)
	{
		//$database = YaConf::get('db.db');
	}

}
