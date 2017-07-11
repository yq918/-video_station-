<?php
include "../config/conf.init.php";

spl_autoload_register('autoload');

$res = new \rpc\Server($ip='*', SERVICE_PORT);


