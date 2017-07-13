<?php
//时区
date_default_timezone_set("PRC");

define('DEBUG_MODE',true);
define('SERVICE_PORT','9620');
define('RPC_COOD','1000'); //RPC成功的code值

require_once 'conf.Path.php';

include SITEBASE.'autoload.php';
