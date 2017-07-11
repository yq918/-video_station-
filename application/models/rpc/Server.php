<?php
namespace models\rpc;

class Server {
	
	const HEADER_SIZE           = 16;
    const HEADER_STRUCT         = "Nlength/Ntype/Nuid/Nserid";
    const HEADER_PACK           = "NNNN";
    const DECODE_PHP            = 1;   //使用PHP的serialize打包
    const DECODE_JSON           = 2;   //使用json_encode打包

	/*
	* 配置文件
	*/
	protected $config = array(
		'worker_num' => 300,
		'max_request' => 5000,
		'dispatch_mode' => 3,
		'open_length_check' => 1,
		'package_max_length' => 2097152,
		'package_length_type' => 'N',
		'package_body_offset' => 16,
		'package_length_offset' => 0,
		'user' => 'mosh',
		'group' => 'mosh',
		'heartbeat_check_interval' => 30,
		'heartbeat_idle_time' => 60,
		'log_file' => SERVICE_LOGFILE, //日志路径
		'daemonize' => false,    //守护进程改成true
	);
	
	/*
	* 实例化服务类对象数组
	*/
	protected $services = array(); 

	public function __construct($ip='*', $port='9501', $mode = SWOOLE_PROCESS)
	{
		$serv = new \swoole_server($ip, $port, $mode);

		$serv->set($this->config);
		$serv->config = $this->config;

		$serv->on('Start', array($this, 'onStart'));
		$serv->on('Connect', array($this, 'onConnect'));
		$serv->on('Receive', array($this, 'onReceive'));
		$serv->on('Close', array($this, 'onClose'));
		$serv->on('Shutdown', array($this, 'onShutdown'));
		$serv->on('WorkerStart', array($this, 'onWorkerStart'));
		$serv->on('WorkerStop', array($this, 'onWorkerStop'));
		//$serv->on('Timer', array($this, 'onTimer'));
		//$serv->on('Task', array($this, 'onTask'));
		//$serv->on('Finish', array($this, 'onFinish'));
		$serv->on('WorkerError', array($this, 'onWorkerError'));

		$serv->on('ManagerStart', function ($serv) {
			global $argv;
			swoole_set_process_name("php {$argv[0]}:swoole-manager-".SERVICE_PORT);
		});

		$serv->start();
	}
	
	public function onStart(\swoole_server $serv)
	{
		global $argv;

		swoole_set_process_name("php {$argv[0]}:swoole-master-".SERVICE_PORT);

		$this->log("MasterPid={$serv->master_pid}|Manager_pid={$serv->manager_pid}\n");
		$this->log("Server: start.Swoole version is [" . SWOOLE_VERSION . "]\n");
	}
	
	public function processRename($serv, $worker_id)
	{
		global $argv;
		if ($worker_id >= $serv->setting['worker_num']) 
		{
			swoole_set_process_name("php {$argv[0]}:swoole-task-".SERVICE_PORT);
		} 
		else 
		{
			swoole_set_process_name("php {$argv[0]}:swoole-worker-".SERVICE_PORT);
		}

		$this->log("WorkerStart: MasterPid={$serv->master_pid}|Manager_pid={$serv->manager_pid}");
		$this->log("|WorkerId={$serv->worker_id}|WorkerPid={$serv->worker_pid}\n");
	}
	
	public function setTimerInWorker(\swoole_server $serv, $worker_id)
	{
		if ($worker_id == 0)
		{
			$this->log("Start: " . microtime(true) . "\n");
			$serv->addtimer(3000);
		}
	}
	
	public function onShutdown($serv)
	{
		$this->log("Server: onShutdown\n");
	}
	
	public function onTimer($serv, $interval)
	{
		$this->log("Timer#$interval: " . microtime(true) . "\n");
		$serv->task("hello");
	}
	
	public function onClose($serv, $fd, $from_id)
	{
		$this->log("Worker#{$serv->worker_pid} Client[$fd@$from_id]: fd=$fd is closed");
	}
	
	public function onConnect(\swoole_server $serv, $fd, $from_id)
	{
		#var_dump($serv->connection_info($fd));
		$this->log("Worker#{$serv->worker_pid} Client[$fd@$from_id]: Connect.\n");
	}
	
	public function onWorkerStart($serv, $worker_id)
	{
		$this->processRename($serv, $worker_id);
		clearstatcache(1); // 清除文件状态缓存
		#setTimerInWorker($serv, $worker_id);
	}
	
	public function onWorkerStop($serv, $worker_id)
	{
		$this->log("WorkerStop[$worker_id]|pid=" . $serv->worker_pid . ".\n");
	}
	
	public function onReceive(\swoole_server $serv, $fd, $from_id, $data)
	{

		$header = unpack(self::HEADER_STRUCT, substr($data, 0, self::HEADER_SIZE));
		$body = substr($data, self::HEADER_SIZE);

		$request = self::decode($body);

		//判断数据是否正确
		if(empty($request['class']) || empty($request['method']) || !isset($request['param_array'])) 
		{
			//发送数据给客户端，请求包错误
			return $serv->send($fd,self::encode(array('code'=>'400', 'msg'=>'Bad Request,Data Error.', 'data'=>null), $header['type'], $header['uid'], $header['serid']));
		}

		//获得要调用的类、方法、及参数
		$class = $request['class'];
		$method = $request['method'];
		$param_array = $request['param_array'];

		if (! class_exists($class)) 
		{
			$code = '404';
			$msg = "Class $class Not Found";

			return $serv->send($fd,self::encode(array('code'=>'400', 'msg'=>$msg, 'data'=>null), $header['type'], $header['uid'], $header['serid']));
		}
		$this->services[$class] = new $class();
		
		//初始化返回给客户端的结果集
		$ret = array();
		
		//检查方法是否存在
		if (method_exists($this->services[$class], $method)) 
		{
			//调用类的方法
			$ret = call_user_func_array(array($this->services[$class], $method), $param_array);

			if(!empty($ret))
			{
				// 发送数据给客户端，调用成功，data下标对应的元素即为调用结果
				$serv->send($fd,self::encode($ret, $header['type'], $header['uid'], $header['serid']));
			}
			else
			{
				$ret['code'] ='100';

				$ret['message'] ='Data emtry';

				$serv->send($fd,self::encode($ret, $header['type'], $header['uid'], $header['serid']));
			}

		}
		else
		{
			$ret['code'] ='404';

			$ret['message'] ="Method {$method} No Found";

			$serv->send($fd,self::encode($ret, $header['type'], $header['uid'], $header['serid']));
		}
		
	}
	
	public function onTask(\swoole_server $serv, $task_id, $from_id, $data)
	{
		if ($data == 'taskwait') 
		{
			$fd = str_replace('task-', '', $data);
			$serv->send($fd, "hello world");

			return array(
				"task" => 'wait'
			);
		}
		else
		{
			$fd = str_replace('task-', '', $data);
			$serv->send($fd, "hello world in taskworker.");

			return;
		}
	}
	
	public function onFinish(\swoole_server $serv, $task_id, $data)
	{
		list ($str, $fd) = explode('-', $data);

		$serv->send($fd, 'taskok');

		$this->log("AsyncTask Finish: result={$data}. PID=" . $serv->worker_pid . PHP_EOL);
	}
	
	public function onWorkerError(\swoole_server $serv, $worker_id, $worker_pid, $exit_code)
	{
		$this->log("worker abnormal exit. WorkerId=$worker_id|Pid=$worker_pid|ExitCode=$exit_code\n");
	}
	
	//日志输出
	public function log($log)
	{
		$filename = 'swoole_server';
		//Log::simpleappend($filename, $log);
	}

	/**
     * 打包数据
     * @param $data
     * @param $type
     * @param $uid
     * @param $serid
     * @return string
     */
    static function encode($data, $type = self::DECODE_PHP, $uid = 0, $serid = 0)
    {
        switch($type)
        {
            case self::DECODE_JSON:
                $body = json_encode($data);
                break;
            case self::DECODE_PHP:
            default:
                $body = serialize($data);
                break;
        }
        return pack(self::HEADER_PACK, strlen($body), $type, $uid, $serid) . $body;
    }
    /**
     * 解包数据
     * @param string $data
     * @param int $unseralize_type
     * @return string
     */
    static function decode($data, $unseralize_type = self::DECODE_PHP)
    {
        switch ($unseralize_type)
        {
            case self::DECODE_JSON:
                return json_decode($data, true);
            case self::DECODE_PHP;
            default:
                return unserialize($data);
        }
    }


}
