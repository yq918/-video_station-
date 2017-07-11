<?php
/**
 * 保存youtube数据到mysql中
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '../../init.php';
use models\db\db;
use models\YoutuBe\youtube;
class saveData{
	const POPULAR = '2'; //时下流行
	const MUSIC   = '3'; //音乐
	public $req = array();
    public function run()
    {
		if(empty($_REQUEST)){
			  exit('数据为空');
		}
		$this->req = $_REQUEST;
		$cat_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : 2;
		switch($cat_id) {
			case  self::POPULAR:
				$data =  $this->popularNowadays();
				break;
			case self::MUSIC:
				$data = $this->musicData();
				break;
		}
		$youtubeObj = new youtube();
		$addYoutubeData = array(
			'video_url'   => $data['video_url'],
			'video_title' => $data['video_title'],
			'video_cover' => $data['video_cover'],
			'cat_id'      => $data['cat_id'],
			'add_time' => time()
		);
		$lastId = $youtubeObj->addYoutubeData($addYoutubeData);
		if($lastId){
			$downData = array(
				'you_id' => $lastId,
				'video_title'    => $data['video_title'],
				'play_duration'  => $data['play_duration'],
				'playback_times' => isset($data['playback_times']) ? $data['playback_times'] : 0,
				'video_time_content' => $data['video_time_content']
			);
			$result = $youtubeObj->addDownloadData($downData);
		}
		return $result;
  }

	/**
	 * popularNowadays
	 *
	 * [时下流行]
	 * @author zhangxuanru  [zhangxuanru@eventmosh.com]
	 */
	public function popularNowadays()
    {
		$req = $this->req;
		$data = [];
		$filed = array('video_url','video_title','play_duration','video_time_content','playback_times','video_cover');
		array_map(function($key) use (&$data){
			if(isset($req[$key]) && !empty($req[$key])){
				$data[$key] = trim($req[$key]);
			}
		},$filed);
		$str = str_replace(',','',$data['video_time_content']);
		preg_match_all('/\d+/is',$str,$arr);
		$data['playback_times']    = isset($arr[0][1]) ? $arr[0][1] : 0;
		$data['add_time'] = time();
		return $data;
    }

	/**
	 * musicData
	 *
	 * [音乐]
	 * @author zhangxuanru  [zhangxuanru@eventmosh.com]
	 */
	public function musicData()
	{
		$req = $this->req;
        $play_time = isset($req['play_time']) ? trim($req['play_time']) : 0;
		if(!empty($play_time)){
			$play_time = str_replace(array(' - 时长：','秒','时长','。'),'',$play_time);
			$play_time = str_replace('分钟',':',$play_time);
		}
		$data['add_time'] = time();
		$data['video_url'] = isset($req['href']) ? $req['href'] : '';
		$data['video_title'] = isset($req['title']) ? $req['title'] : '';
		$data['play_duration'] = trim($play_time);
		$data['video_time_content'] = isset($req['playback_times']) ? trim(strip_tags($req['playback_times'] )): '';
		$data['video_cover'] = isset($req['imgaddress']) ? $req['imgaddress'] : '';
		$data['cat_id'] = '3';
	    return  $data;
	}
}

$class = new saveData();
$class->run();

 


