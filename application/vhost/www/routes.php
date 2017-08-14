<?php
/**
 * Created by PhpStorm.
 * @Copyright:event
 * @Author:zhangxuanru [zhangxuanru@eventmosh.com]
 * @Date: 2017/4/7 12:05
 */

return array(
    'test_abc' => new Yaf\Route\Regex('#^/abc$#', ['controller' => 'Index', 'action' => 'abc'], []),
    'transitPage' => new Yaf\Route\Regex('#^/transitpage/([a-zA-Z-_0-9]+)$#', ['controller' => 'Index', 'action' => 'transitPage'], [1=>'page']),
);