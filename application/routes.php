<?php
/**
 * Created by PhpStorm.
 * @Copyright:event
 * @Author:zhangxuanru [zhangxuanru@eventmosh.com]
 * @Date: 2017/4/7 12:05
 */

return array(
    'test_abc' => new Yaf\Route\Regex('#^/abc$#', ['controller' => 'Index', 'action' => 'abc'], []),
);