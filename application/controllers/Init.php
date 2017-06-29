<?php
use  controllers\Traits\StaticConf;

use Base\Base;

/**
 * @name InitController
 * @author root
 * @desc ���п��������̳д��࣬������һЩ���������ڴ��ļ���
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class InitController extends Yaf\Controller_Abstract {

  use StaticConf;

   protected $constant;
   protected $_req;

    /**
     * init
     *
     * [Ĭ��ִ�еķ���]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function init(){
        // �ر��Զ���Ⱦģ��
        //Yaf\Dispatcher::getInstance()->disableView();
        $this->_req = $this->getRequest();
        $this->assignStatic();
        $this->assignDefaultData();
    }

    /**
     * getConstant
     *
     * [��ȡϵͳ����]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
//    public function getConstant()
//    {
//        Base::getConstant();
//        $constant = Yaf\Registry::get('constant');
//        $this->constant = $constant;
//    }


    /**
     * assignDefaultData
     *
     * [ҳ�� ����Ĭ��ֵ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
   public function assignDefaultData()
  {
        $constant   =   Base::getConstant('constant.constant');
        $static_url = isset($constant['static_url']) ? $constant['static_url'] : '';
        $static_str = isset($constant['static_version']) ? "?v=".$constant['static_version'] : '';
        $this->getView()->assign("static_url",$static_url);
        $this->getView()->assign("static_str",$static_str);
  }


    /**
     * assignStatic
     *
     * [��ȡ��ʽ��JS]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function assignStatic()
    {
    	$node_case = strtolower( $this->_req->getControllerName().'_'.$this->_req->getActionName() );
        $options   = $this->options($node_case);
        $this->getView()->assign('options', $options);
    }


}


