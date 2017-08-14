<?php
namespace User;

class User
{  
    public static $cookie_name = '520_user';
    public static $encryptKey  = '520';
    public static $domain      = '13520v.com';

    /**
     * [saveSessionregisterUser 保存用户数据到cookie]
     * @param  [type] $userInfo [description]
     * @return [type]           [description]
     */
    public static function saveCookieregisterUser($userInfo)
    { 
         $useren = self::userEncryption($userInfo,self::$encryptKey); 
         $ret = setcookie(self::$cookie_name,$useren,time()+3600,'/',self::$domain);   
     }

     /**
      * [getCookieUser 读取COOKIE]
      * @return [type] [description]
      */
     public static function getCookieUser()
     {
         $userInfo = [];
         $cookie_name = self::$cookie_name;
         $user_cookie =  isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
         if(!empty($user_cookie)){
             $userInfo = self::userdecrypt($user_cookie,self::$encryptKey);
         } 
         return $userInfo;
    }



     //加密保存cookie    
    public static function userEncryption($data, $key){
          $prep_code = serialize($data); 
        // $block = mcrypt_get_block_size('des', 'ecb'); 
        // if (($pad = $block - (strlen($prep_code) % $block)) < $block) { 
        // $prep_code .= str_repeat(chr($pad), $pad); 
        // } 
        // $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB); 
        return base64_encode($prep_code); 
    } 

  public static  function   userdecrypt($str, $key){
        $str = base64_decode($str); 
        // $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB); 
        // $block = mcrypt_get_block_size('des', 'ecb'); 
        // $pad = ord($str[($len = strlen($str)) - 1]); 
        // if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) { 
        // $str = substr($str, 0, strlen($str) - $pad); 
        // } 
        return unserialize($str); 
   } 



}
