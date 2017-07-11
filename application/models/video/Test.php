<?php
namespace video;

class Test
{
    static function test1($str = 'empty')
    {
        //var_dump(SOAServer::getClientEnv());
        //var_dump(SOAServer::getRequestHeader());
        return "hello-soa-finish: $str";
    }

    static function aa()
    {
        return array('key1' => 'A', 'key2' => 'B');
    }
}