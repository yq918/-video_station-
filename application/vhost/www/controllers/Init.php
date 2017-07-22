<?php
use  vhost\www\controllers\Traits\StaticConf;

use Base\Base;
use Base\Tools;

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
        Yaf\Dispatcher::getInstance()->disableView();
        $this->_req = $this->getRequest(); 
        $this->assignDefaultData();
        $this->generativeNavigation();
    }
 

    /**
     * assignDefaultData
     *
     * [ҳ�� ����Ĭ��ֵ]
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
     * [��ȡ��ʽ��JS]
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

    
    /**
     * [generativeNavigation description]
     * @return [type] [description]
     * ���ɵ���
     */
    public function generativeNavigation($display = true)
    {
        $arr = array(
            array(
                 'id'    => 0,
                 'title' => 'Home',
                 'class' => 'home-icon',
                 'spanClass' => 'glyphicon glyphicon-home' 
                ),
            array(
                 'id'   => Base::getCatTypeData('popular'),
                 'title' => 'Popular nowadays',
                 'class' => 'user-icon',
                 'spanClass' => 'glyphicon glyphicon-home glyphicon-hourglass' 
                ),
            array(
                 'id'    => Base::getCatTypeData('funny'),
                 'title' => 'Funny',
                 'class' => 'menu1',
                 'spanClass' => 'glyphicon glyphicon-film' 
                ),
            array(
                 'id'    => Base::getCatTypeData('music'),
                 'title' => 'Music',
                 'class' => 'song-icon',
                 'spanClass' => 'glyphicon glyphicon-music' 
                ),
            array(
                 'id'   =>  Base::getCatTypeData('sports'),
                 'title' => 'Sports',
                 'class' => 'menu',
                 'spanClass' => 'glyphicon glyphicon-film glyphicon-king' 
                ),
            array(
                 'id'    => 100,
                 'title' => 'News',
                 'class' => 'news-icon',
                 'spanClass' => 'glyphicon glyphicon-envelope' 
                ), 
            );

        foreach ($arr as $key => $value) {
            $value['link'] =  Tools::generateLinks('/shows/',$value['id']);
            $arr[$key] = $value;
        }  
       if($display){
            $this->getView()->assign('menu', $arr); 
       }
        return $arr; 
    }



}


