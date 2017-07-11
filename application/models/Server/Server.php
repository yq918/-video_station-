<?php
define('DEBUG', 'on');
define('WEBPATH', realpath(__DIR__ . '/../'));
require dirname(__DIR__) . '/libs/lib_config.php';

use  models\Server\RPCServer;


//����PID�ļ��Ĵ洢·��
Swoole\Network\Server::setPidFile(__DIR__ . '/app_server.pid');

/**
 * ��ʾUsage����
 * php app_server.php start|stop|reload
 */
Swoole\Network\Server::start(function ()
{
    $AppSvr = new RPCServer();
    $AppSvr->setLogger(new \Swoole\Log\EchoLog(true)); //Logger

    /**
     * ע��һ���Զ���������ռ䵽SOA������
     * Ĭ��ʹ�� apps/classes
     */
    $AppSvr->addNameSpace('Module', __DIR__ . '/video');

    /**
     * IP����������
     */
    $AppSvr->addAllowIP('127.0.0.1');

    /**
     * �����û�������
     */
  //  $AppSvr->addAllowUser('chelun', 'chelun@123456');

    Swoole\Error::$echo_html = false;

    $server = Swoole\Network\Server::autoCreate('127.0.0.1', 8888);
    $server->setProtocol($AppSvr);
    $server->daemonize(); //��Ϊ�ػ�����
    $server->run(
        array(
            //TODO�� ʵ��ʹ���б�����������
            'worker_num' => 4,
            'max_request' => 5000,
            'dispatch_mode' => 3,
            'open_length_check' => 1,
            'package_max_length' => $AppSvr->packet_maxlen,
            'package_length_type' => 'N',
            'package_body_offset' => RPCServer::HEADER_SIZE,
            'package_length_offset' => 0,
        )
    );
});

