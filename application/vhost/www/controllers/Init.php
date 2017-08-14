<?php
use  vhost\www\controllers\Traits\StaticConf;
use User\User as LibUser;
use Base\Base;
use Base\Tools;

/**
 * @name InitController
 * @author root
 * @desc ËùÓÐ¿ØÖÆÆ÷¶¼¼Ì³Ð´ËÀà£¬¹«¹²µÄÒ»Ð©²Ù×÷¶¼·ÅÔÚ´ËÎÄ¼þÀï
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class InitController extends Yaf\Controller_Abstract {

  use StaticConf;

   protected $constant;
   protected $_req;

    /**
     * init
     *
     * [Ä¬ÈÏÖ´ÐÐµÄ·½·¨]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public function init(){ 
        // ¹Ø±Õ×Ô¶¯äÖÈ¾Ä£°å
        //Yaf\Dispatcher::getInstance()->disableView();
        Yaf\Dispatcher::getInstance()->disableView();
        $this->_req = $this->getRequest(); 
        $this->assignDefaultData();
        $this->generativeNavigation();
        $this->initUser();
    }
 

    /**
     * assignDefaultData
     *
     * [Ò³Ãæ ÉèÖÃÄ¬ÈÏÖµ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
   public function assignDefaultData()
  {
        $constant   = Base::getConstant('constant.constant');
        $this->constant = $constant;
        $static_url = isset($constant['static_url']) ? $constant['static_url'] : '';
        $static_str = isset($constant['static_version']) ? "?v=".$constant['static_version'] : '';
        
        $logo  = $static_url.'/images/logo.png';
        $this->getView()->assign("logo",$logo);

        $this->getView()->assign("static_url",$static_url);
        $this->getView()->assign("static_str",$static_str);
  }


    /**
     * options
     *
     * [»ñÈ¡ÑùÊ½ÓëJS]
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
     * Éú³Éµ¼º½
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
                 'title' => 'Hot',
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
            // array(
            //      'id'    => 100,
            //      'title' => 'News',
            //      'class' => 'news-icon',
            //      'spanClass' => 'glyphicon glyphicon-envelope' 
            //     ), 
            );

        foreach ($arr as $key => $value) {
            $value['link'] =  Tools::generateLinks('/shows/',array('sing' => $value['id']));
            if($value['id'] == Base::getCatTypeData('funny')){
                 $value['link'] =  Tools::generateLinks('/funny/', array('sing' => $value['id']));
            }
            if($value['id'] == '0'){
                 $value['link'] =  Tools::generateLinks('/',array('sing'=>'s'));
            } 

            $arr[$key] = $value;
        }  
       if($display){
            $this->getView()->assign('menu', $arr); 
       }
        return $arr; 
    }



    /**
     * [initUser 初始化用户信息]
     * @return [type] [description]
     */
    public function initUser()
    { 
        $user  = [];  
        $userInfo = LibUser::getCookieUser();
        if(empty($userInfo)){
            $this->getView()->assign('user', $user);
            return ; 
        } 
        $nickname = $userInfo['nickname'];
        if(empty($nickname)){ 
            $nickname = substr_replace($userInfo['phone'], '***', 4,8);
            $userInfo['nickname'] =  $nickname;
        }  
        $this->getView()->assign('user', $userInfo);  
    } 
}


