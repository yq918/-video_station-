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
                $mysql = new Mysql();
                $db =  Mysql::instance();
                break;
        }
        return $mysql;
    }
}

