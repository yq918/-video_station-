<?php
namespace Base;
class Base
{
    const RPC_COOD = '1000';
    const RPC_ERROR_COOD = '1001';
    /**
     * parseIni
     *
     * [½âÎöINIÎÄ¼þ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $file
     */
    public static  function parseIni($file)
    {
      if(!file_exists($file)){
               return [];
        }        
         $fileArr = parse_ini_file($file,true);
         return    $fileArr;
    }

   /**
    * [returnData description]
    * @param  array   $data   [description]
    * @param  integer $status [description]
    * @return [type]          [description]
    * 返回数据的格式
    */
   public static function returnData($data = array(),$msg = '',$status = 1)
   {
    if($status){
        return array('code'=>self::RPC_COOD,'msg' => $msg,'data' => $data);
     }
        return array('code'=>self::RPC_ERROR_COOD,'msg' => $msg,'data' => $data); 
   }  
}
