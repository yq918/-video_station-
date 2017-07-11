<?php
include "../config/conf.init.php";

spl_autoload_register('autoload');

$res = new \models\rpc\Server($ip='*', '9625');


