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
         $fileArr = parse_ini_file($file);
         return    $fileArr;
    }



}