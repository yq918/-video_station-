<?php
use  vhost\www\controllers\Traits\StaticConf;

use Base\Base;

/**
 * @name InitController
 * @author root
 * @desc 所有控制器都继承此类，公共的一些操作都放在此文件里
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class InitController extends Yaf\Controller_Abstract {

  use StaticConf;

   protected $constant;
   protected $_req;

    /**
     * init
     *
     * [默认执行的方法]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function init(){
        // 关闭自动渲染模板
        //Yaf\Dispatcher::getInstance()->disableView();
        Yaf\Dispatcher::getInstance()->disableView();
        $this->_req = $this->getRequest(); 
        $this->assignDefaultData();
    }
 

    /**
     * assignDefaultData
     *
     * [页面 设置默认值]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
   public function assignDefaultData()
  {
        $constant   = Base::getConstant('constant.constant');
        $this->constant = $constant;
        $static_url = isset($constant['static_url']) ? $constant['static_url'] : '';
        $static_str = isset($constant['static_version']) ? "?v=".$constant['static_version'] : '';
        $this->getView()->assign("static_url",$static_url);
        $this->getView()->assign("static_str",$static_str);
  }


    /**
     * options
     *
     * [获取样式与JS]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function assignOptions($node_case = '')
    {
      if(empty($node_case)){
          $node_case = strtolower( $this->_req->getControllerName().'_'.$this->_req->getActionName() );
      } 
        $options   = $this->options($node_case);
        $this->getView()->assign('options', $options);
    }


}


