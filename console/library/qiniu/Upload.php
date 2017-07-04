<?php
/**
 * 七牛上传文件类
 */

namespace library\qiniu;

// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class Upload
{
	public function upload_file($bucket,$filePath,$key)
	{

		// 需要填写你的 Access Key 和 Secret Key
		$accessKey = Access_Key;
		$secretKey = Secret_Key;

		// 构建鉴权对象
		$auth = new Auth($accessKey, $secretKey);

		// 要上传的空间
		//$bucket = 'images';

		// 生成上传 Token
		$token = $auth->uploadToken($bucket); 

		// 初始化 UploadManager 对象并进行文件的上传。
		$uploadMgr = new UploadManager();

		// 调用 UploadManager 的 putFile 方法进行文件的上传。
		list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
		echo "\n====> putFile result: \n";
		if ($err !== null) {
		    var_dump($err);
		} else {
		    var_dump($ret);
		}

   }

}
