<?php 
namespace vhost\www\controllers\Traits;
use Base\Tools;
use Base\Base;
use controllers\User\User;

/*
用户相关 
*/

trait UserTraits{  
        /**
    * [generateSessionKey description]
    * @return [type] [生成session key 防止提交表单篡改]
    */
   public function generateSessionKey()
   {  
       session_start();
       $_SESSION['fromKey'] = md5(time().'zxr13520v.dingjiao'); 
       return  $_SESSION['fromKey'];
   }


   /**
    * [getSessionKey description]
    * @return [type] [获取session key ]
    */
   public function getSessionKey()
   {
     session_start();
     return isset($_SESSION['fromKey'])?$_SESSION['fromKey'] :''; 
   }

    
    /**
     * [getUserByWhere 获取用户数据]
     * @param  string $where  [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     */
   public function getUserByWhere($where='',$fields='*')
   {
   	  $UserObj = new User();
      $data = $UserObj->getUserByWhere($where,$fields);
      return $data; 
   }

   /**
    * [getUserByPhoneOrEmail 根据手机号或邮箱查询用户信息]
    * @param  string $phone [description]
    * @param  string $email [description]
    * @return [type]        [description]
    */
   public function getUserByPhoneOrEmail($phone='',$email=''){
   	  $UserObj = new User();
   	  $where = "phone='{$phone}' OR email='{$email}' ";
      $data = $UserObj->getUserByWhere($where);
      return $data; 
   }

   /**
    * [getUserInfoByUserIdList 根据user_id 获取用户列表]
    * @param  array  $userIdList [description]
    * @return [type]             [description]
    */
   public function  getUserInfoByUserIdList(array $userIdList)
   {
   	$UserObj = new User(); 
   	$userIdStr = implode(',',$userIdList);
   	$where = "id in ({$userIdStr}) ";
   	$fields = " `id`,`email`,`phone`,`rename` "; 
   	$data = $UserObj->getUserByWhere($where,$fields);
    return $data; 
   }

   /**
    * [getNickName 获取用户昵称]
    * @param  array  $userInfo [description]
    * @return [type]           [description]
    */
   public function getNickName(array $userInfo)
   {
   	$nickname = $userInfo['rename'];
   	if(!empty($nickname)){
   		  return $nickname;
   	}
   	if($userInfo['phone']){
   		$nickname = substr_replace($userInfo['phone'], "***", 3,5);
   	} 
   	return $nickname; 
   }


   /**
    * [checkUser 检查用户是否存在]
    * @param  [type] $username [description]
    * @param  [type] $passwd   [description]
    * @return [type]           [description]
    */
   public function checkUser($username,$passwd)
   { 
   	 $UserObj = new User();
     $where = "(phone='{$username}' OR email='{$username}') AND passwd='{$passwd}' ";
     $data = $UserObj->getUserByWhere($where); 
     return $data ; 
   } 

   /**
    * [addUser 注册用户]
    * @param array $data [description]
    */
   public function addUser(array $data )
   {
   	 $UserObj = new User();
   	 $saveData = array(
           'email' => $data['email'],
           'phone' => $data['phone'],
           'passwd' => md5(md5($data['pwd'])),
           'add_date' =>  time(),
           'rename'  => $data['nickname']
   	 	); 
   	 return  $UserObj ->add($saveData); 
   }



   






}
