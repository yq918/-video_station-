<?php
namespace Base;
class Base
{
    /**
     * parseIni
     *
     * [����INI�ļ�]
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



}
