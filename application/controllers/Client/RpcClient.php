<?php

namespace controllers\Client;


/**
 * RpcClient Rpc客户端
 */

class RpcClient
{

	/**
	 * 发送数据和接收数据的超时时间 单位S
	 * @var integer
	 */

	//配置参数
	const HEADER_SIZE           = 16;
	const HEADER_STRUCT         = "Nlength/Ntype/Nuid/Nserid";
	const HEADER_PACK           = "NNNN";
	const DECODE_PHP            = 1;   //使用PHP的serialize打包
	const DECODE_JSON           = 2;   //使用json_encode打包
	const RPC_COOD              = 1000;

	protected $packet_maxlen = 2097152;   //最大不超过2M的数据包

    protected static $_time_out = 2;   //连接超时时间

	/**
	 * swoole客户端设置
	 */
	protected $swooleClientSets = array(
		'open_length_check' => true,
		'package_max_length' => 2097152,
		'package_length_type' => 'N',
		'package_body_offset' => 16,
		'package_length_offset' => 0,
	);

	/**
	 * 服务端地址
	 * @var array
	 */
	protected static $addressArray = array();


	/**
	 * 同步调用实例
	 * @var string
	 */
	protected static $instances = array();

	/**
	 * 到服务端的socket连接
	 * @var resource
	 */
	protected $connection = null;

	/**
	 * 到服务端的swoole client连接
	 * @var resource
	 */
	protected $swooleClient = null;

	/**
	 * 实例的服务名
	 * @var string
	 */
	protected $serviceName = '';

	/**
	 * 使用swoole方式
	 * @var string
	 */
	protected static $useSwoole = true;

	/**
	 * 设置/获取服务端地址
	 * @param array $address_array
	 */
	public static function config($address_array)
	{
		if (empty($address_array)) {
			self::$addressArray = include SITEBASE.'/conf/Rpc.conf.php';

		}else{
			self::$addressArray = $address_array;
		}
		return self::$addressArray;
	}

	/**
	 * 获取一个实例
	 * @param string $service_name
	 * @return instance of RpcClient
	 */
	public static function instance($service_name,$time_out=10,$address_array=array())
	{
		if($time_out){
			self::$_time_out = $time_out;
		}
		self::config($address_array);
		if (! isset(self::$instances[$service_name])) {
			self::$instances[$service_name] = new self($service_name);
		}
		return self::$instances[$service_name];
	}

	/**
	 * 构造函数
	 * @param string $service_name
	 */
	protected function __construct($service_name)
	{
		$this->serviceName = $service_name;
	}

	public static function setSwooleClient()
	{
		self::$useSwoole = true;
	}

	/**
	 * 调用
	 *
	 * @param string $method
	 * @param array $arguments
	 * @throws Exception
	 * @return
	 *
	 */
	public function __call($method, $arguments)
	{
		$this->method = $method;
		$this->arguments = $arguments;

		// 同步发送接收
		$this->sendData($method, $arguments);
		$recvData =  $this->recvData();

		return $recvData;
	}

	/**
	 * 发送数据给服务端
	 *
	 * @param string $method
	 * @param array $arguments
	 */
	public function sendData($method, $arguments)
	{
		$data = array(
			'class' => $this->serviceName,
			'method' => $method,
			'param_array' => $arguments
		);

		//请求串号
		$requestId = self::getRequestId();

		$bin_data = self::encode($data, self::DECODE_PHP, 0, $requestId);

		$this->openConnection();
		if (self::$useSwoole)
		{
			return $this->swooleClient->send($bin_data);
		}
		else
		{
			return fwrite($this->connection, $bin_data) == strlen($bin_data);
		}

	}

	/**
	 * 从服务端接收数据
	 * @throws Exception
	 */
	public function recvData()
	{

		if (self::$useSwoole)
		{
			$request = $this->swooleClient->recv();
			$this->swooleClient->close();
		}
		else
		{
			$request = fgets($this->connection);
			$this->closeConnection();
		}
		if (! $request)
		{
			file_put_contents('/tmp/swoole.log','INFO:request 为空  TIME:'.date('Y-m-d H:i:s',time())."\r\n",FILE_APPEND);
			return null;
		}

		$header = unpack(self::HEADER_STRUCT, substr($request, 0, self::HEADER_SIZE));
		$body = self::decode(substr($request, self::HEADER_SIZE));

		//错误的包头
		if ($request === false || $header['length'] <= 0)
		{
			return null;
		}
		//错误的长度值
		elseif ($header['length'] > $this->packet_maxlen)
		{
			//TODO log 数据传输超过限制
			file_put_contents('/tmp/swoole.log','INFO:包体长度超过限制  TIME:'.date('Y-m-d H:i:s',time())."\r\n",FILE_APPEND);
			return null;
		}

		$body = self::decode(substr($request, self::HEADER_SIZE));
		if(empty($body)){
			//TODO log 包体为空
			file_put_contents('/tmp/swoole.log','INFO:包体为空  TIME:'.date('Y-m-d H:i:s',time())."\r\n",FILE_APPEND);
			return null;
		}

		if(isset($body['code']) and $body['code'] != 10000 and $body['code'] != 11000){

			$this->method;
			$this->arguments;
			$logData['serviceName'] = $this->serviceName;
			$logData['method'] = $this->method;
			$logData['arguments'] = $this->arguments;
			$logData['reponse'] = $body;
			$date = date("Y_m_d");
			$log_file = 'swoole_client'.$date.'.log';

			//-----------------获取错误栈
			$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 8);
			$traceLine = array();
			foreach ($trace as $index => $item) {

				$file = empty($item['file']) ? '' : $item['file'];
				$line = empty($item['line']) ? '' : $item['line'];
				$class = empty($item['class']) ? '' : $item['class'];
				$type = empty($item['type']) ? '' : $item['type'];
				$function = empty($item['function']) ? '' : $item['function'];
				if(strpos($file,'eventelib') !== false){
					continue;//过滤掉eventelib追溯，不需要记录
				}
				$traceLine[] = '#' . $index . ' ' . $file . '(' . $line . '): ' . $class . $type .$function.'()';
			}
			$logData['trace'] = $traceLine;
			$log_info = 'TIME:'.date('Y-m-d H:i:s',time()).' MSG['.json_encode($logData).']'."\r\n";
			file_put_contents('/home/mosh/prolog/moshlog/'.$log_file,$log_info,FILE_APPEND);
		}
		return $body;

	}

	/**
	 * 打开到服务端的连接
	 * @return void
	 */
	protected function openConnection()
	{
		$address = self::$addressArray[array_rand(self::$addressArray)];
		if (self::$useSwoole)
		{
			$address = explode(':', $address);

			$this->swooleClient = new \swoole_client(SWOOLE_SOCK_TCP);

			$this->swooleClient->set($this->swooleClientSets);

			if (!$this->swooleClient->connect($address[0], $address[1], self::$_time_out))
			{
				exit("connect failed. Error: {$this->swooleClient->errCode}\n");
			}
		}
		else
		{
			$this->connection = stream_socket_client($address, $err_no, $err_msg);

			if (! $this->connection)
			{
				throw new \Exception("can not connect to $address , $err_no:$err_msg");
			}
			stream_set_blocking($this->connection, true);

			stream_set_timeout($this->connection, self::$_time_out);
		}
	}

	/**
	 * 关闭到服务端的连接
	 *
	 * @return void
	 */
	protected function closeConnection()
	{
		fclose($this->connection);
		$this->connection = null;
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
		if(is_object($data)){
			file_put_contents('/tmp/decode.log',date('Y-m-d H:i:s',time).' '.$data,FILE_APPEND);
		}
        switch ($unseralize_type)
        {
            case self::DECODE_JSON:
                return json_decode($data, true);
            case self::DECODE_PHP;
            default:
                return unserialize($data);
        }
    }

	/**
	 * 生成请求串号
	 * @return int
	 */
	static function getRequestId()
	{
		$us = strstr(microtime(), ' ', true);
		return intval(strval($us * 1000 * 1000) . rand(100, 999));
	}
}
