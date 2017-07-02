<?php 
/**
 * 下载B站视频到本地
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '../../init.php';
use models\db\db;
use library\HTTP\HttpCurl;
class download{
   public function run()
   { 
	$fp = fopen(INTERFACELOG,'r') or die('file error');
	while(!feof($fp)){
		 $line = fgets($fp);
		 if(empty($line)){
			     continue;
			 }
		 list($video_bili_url,$url) = explode('>',$line);		
	     $video_bili_url = trim($video_bili_url);
	     $url            = trim($url);   	          
		 $headers = $this->getHeader($url); 
		 $data = HttpCurl::request($url, 'get',false,$headers); 
		 if($data[2]['http_code'] != '200'){
			echo "interface地址异常";
			exit;
		}
	//表示interface地址正常，可获取具体视频地址 
	  $interfaceData = json_decode($data[0],true);
	  $durl = isset($interfaceData['durl']) ? $interfaceData['durl'] : array(); 
	  foreach($durl as $key => $val){
		   $val['video_interface'] = $url;
		   $val['accept_format']   = $interfaceData['accept_format'];
	 	   $this->saveDbData($val,$video_bili_url);
	    } 
	}    
} 	
 
//数据库操作
public function saveDbData($data = array(),$video_bili_url='m.pmd')
{
  $date = date('YmdH');
   if(isset($data['url']) &&  !empty($data['url'])){
	   $urlinfo =  parse_url($data['url']);
           $title = array_pop(explode('/',$urlinfo['path']));           
  	       $dir  = VIDEO_PATH.'bilibili'.'/'.$date.'/';
           if(!is_dir($dir) || !file_exists($dir)){
               mkdir($dir,0777,true);
           } 
  	        $path  = $dir.'/'.$title;
            if(file_exists($path)){
                $ret = true;
             }else {
	           //下载
	           $ret = $this->download($data['url'],$path);
             }

	   if($ret === false){
		   echo $val['url'].'下载失败';	
		   $content = array('url' =>$data['url'],'interface' => $data['video_interface'] );
		   file_put_contents(ERR_LOG,var_export($content,true),FILE_APPEND);
		   return false;
	    }
	   
       if($ret){  
		   $db['video_format'] = $data['accept_format'];
		   $db['video_size']   = $data['size'];
		   $db['video_path']   = $path;
		   $db['video_interface']  = $data['video_interface'];
		   $db['video_url']  = $data['url'];
		   $db['up_date']    = time();
		   $db['backup_url'] = json_encode($data['backup_url']);
    	   $where = array('video_bili_url' => $video_bili_url);

           $conn = db::getinstance();
    	   $result =  $conn->update('bilibili', $db, $where);
		   
  		   if($result == false){  
						 file_put_contents(ERR_DB_LOG,var_export(array('content'=>$db),true)."\r\n",FILE_APPEND);
						 return false;
			 };
		   return true; 
 		 } 
  } 
  return false;
 }
 
/*
下载操作
*/
public function download($url,$path)
{
	$headers = getHeader($url);
    $data = HttpCurl::request($url, 'get',false,$headers);
	$res  = false;
 	if($data[2]['http_code'] == '302'){
	   $url = $data[2]['redirect_url'];
	   $headers = getHeader($url);
	   $res = curl_download($url,$path,$headers);
  	}
	if($data[2]['http_code'] == '200'){
        $headers = getHeader($url);
	    $res = curl_download($url,$path,$headers);
	 }
	 
  return $res;
} 


public function getHeader($url)
{ 
 $urlInfo = parse_url($url);
 $Referer = "http://www.bilibili.com/";
 $Host = $urlInfo['host'];   
 $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';
 $headers[] = 'Host:'.$Host; 
 $headers[] = 'Referer:'.$Referer;   
 return $headers; 
}


public function curl_download($url, $dir,$header=array()) {    
       $call = "axel -n 2 -o  {$dir}  {$url}";  	 
	   exec($call,$array); //执行命令
	   usleep(1000);
	   if(file_exists($dir)){
           return true;
	   }
	   return false;


	$ch = curl_init($url);
	$fp = fopen($dir, "wb");
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	// 设置请求头
	if (count($header) > 0) {
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	} 		
	$res=curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	return $res;
} 
}


$class = new download();
$class->run();


