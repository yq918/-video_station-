<?php
/**
 * Created by PhpStorm.
 * @Copyright:event
 * @Author:zhangxuanru [zhangxuanru@eventmosh.com]
 * @Date: 2017/7/11 19:55
 */
function autoload($class)
{
    $filename = SITEBASE.'/'.str_replace('\\','/',$class).'.php';

    if (file_exists($filename)) {
        include $filename;
    } else {
        echo '�ļ�'.$filename.'������'.PHP_EOL;
    }
}