<?php
namespace Db;
use Db\Mysql;

class Db
{
    static function instance($driver = 'mysql')
    {
        $db = NULL;
        switch($driver){
            case 'mysql':
                $db =  Mysql::instance();
                break;
        }
        return $db;
    }
}

