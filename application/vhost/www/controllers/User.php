<?php
/**
 * @name UserController
 * @author zxr
 * @desc 评论
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
 
use Base\Tools;     //libary 
use Base\Base;
use vhost\www\controllers\Traits\UserTraits; 
use User\User as LibUser;
 
class UserController extends InitController {
 
	use UserTraits; 

	public function init(){
		      parent::init();	 
	} 
    
    /**
     * [getFromkeyAction 获取登录的表单KEY]
     * @return [type] [description]
     */
    public function getFromkeyAction()
    {
        $key =  $this->generateSessionKey(); 
        Tools::ajaxReturn(true, 'success',['key' => $key]);
    }


   /**
    * [signupAction 注册]
    * @return [type] [description]
    */
    public function signupAction()
    { 
        $isPost = $this->getRequest()->isPost();
        if(!$isPost){
            Tools::ajaxReturn(false, '非法操作');
        }
        $postData = $this->getRequest()->getPost();
        if(empty($postData) || empty($postData['email']) || empty($postData['phone']) || empty($postData['pwd'])){
            Tools::ajaxReturn(false, '数据为空');
        }
        if($postData['formKey'] != $this->getSessionKey()){
            Tools::ajaxReturn(false, '非法请求');
        } 
       if(!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)){
           Tools::ajaxReturn(false, '邮箱地址不正确');
        } 
        if(Tools::is_mobile($postData['phone']) == false){
            Tools::ajaxReturn(false, '手机号不正确');
        }
        //查询手机或邮箱是否存在
        $userRow = $this->getUserByPhoneOrEmail($postData['phone'],$postData['email']);
        if(!empty( $userRow )){
           Tools::ajaxReturn(false, '用户已存在');
        }  
        $addInfo =  $this->addUser($postData);
        if(empty($addInfo)){
            Tools::ajaxReturn(false, '用户注册失败, 请稍后重试');
        } 
        $postData['id'] = $addInfo['id'];
        LibUser::saveCookieregisterUser($postData);  
        Tools::ajaxReturn(true, '注册成功'); 
    }



    /**
     * [logoutAction 退出]
     * @return [type] [description]
     */
    public function logoutAction()
    {
       LibUser::saveCookieregisterUser([]);  
       Tools::ajaxReturn(true, ''); 
    }


    /**
     * [loginAction 登录]
     * @return [type] [description]
     */
    public function loginAction()
    { 
        $isPost = $this->getRequest()->isPost();
        if(!$isPost){
            Tools::ajaxReturn(false, '非法操作');
        }
        $postData = $this->getRequest()->getPost();
        if(empty($postData) || empty($postData['username']) || empty($postData['passwd']) || empty($postData['token'])){
            Tools::ajaxReturn(false, '数据为空');
        }
        if($postData['token'] != $this->getSessionKey()){
            Tools::ajaxReturn(false, '非法请求');
        } 

    if(!filter_var($postData['username'], FILTER_VALIDATE_EMAIL)  &&  Tools::is_mobile($postData['username']) == false ){
           Tools::ajaxReturn(false, '用户名格式不正确');
        }
        //查询用户是否存在
        $userRow = $this->checkUser($postData['username'],md5(md5($postData['passwd']))); 
        if(!empty( $userRow ) && $userRow[0]['id'] > 0){ 
            $userRow[0]['nickname'] = $userRow[0]['rename'];
            LibUser::saveCookieregisterUser($userRow[0]);  
            Tools::ajaxReturn(true, 'success'); 
        }  
        Tools::ajaxReturn(false, '用户不存在');  
    } 
 
}
