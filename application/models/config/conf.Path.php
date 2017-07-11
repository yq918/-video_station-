<?php
/**
 * Created by PhpStorm.
 * @Copyright:event
 * @Author:zhangxuanru [zhangxuanru@eventmosh.com]
 * @Date: 2017/7/11 19:56
 */

$basepath = realpath(dirname(__FILE__).'/../') . '/';


define('SITEBASE',$basepath);  //站点系统根目录
define('SERVICE_LOGFILE','./prolog/rpc.log');	 //swoole 日志路径
