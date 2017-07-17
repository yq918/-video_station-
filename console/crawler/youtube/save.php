<?php
/**
 * 保存youtube数据到mysql中
 * @author zhangxuanru [strive965432@gmail.com]
 * @date   2017-07-01 23:12
 */
include '../../init.php';
use models\db\db;
use models\YoutuBe\youtube;
use models\Cat\Cat;
class saveData{ 
	public $req = array();
    public function run()
    {
		if(empty($_REQUEST)){
			  exit('数据为空');
		}
		$this->req = $_REQUEST;
		$cat_id = isset($_REQUEST['cat_id']) ? $_REQUEST['cat_id'] : 0; 
	    $data   = $this->collatingData($cat_id);   
		if( !isset($data['category_id']) || empty($data['category_id'])){
           exit('分类ID为空');
		}

		$youtubeObj = new youtube();
		$addYoutubeData = array(
			'video_url'   => $data['video_url'],
			'video_title' => $data['video_title'],
			'video_cover' => $data['video_cover'],
			'cat_id'      => $data['cat_id'],
			'category_id' => $data['category_id'],
			'add_time'    => time()
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
	 * collatingData
	 *
	 * [整理写表的数据]
	 * @author zhangxuanru  [zhangxuanru@eventmosh.com]
	 */
	public function collatingData($cat_id)
	{
		$req = $this->req;
        $play_time = isset($req['play_time']) ? strip_tags($req['play_time']) : 0; 
		$data['cat_title']     = isset($req['cat_title']) ? trim($req['cat_title']) : '' ; 
		$data['video_url']     = isset($req['href']) ? $req['href'] : '';
		$data['video_title']   = isset($req['title']) ? $req['title'] : '';
		$data['play_duration'] = trim($play_time);
		$data['video_time_content'] = isset($req['playback_times']) ? trim(strip_tags($req['playback_times'] )): '';
		$data['video_cover'] = isset($req['imgaddress']) ? $req['imgaddress'] : ''; 
		$data['category_id'] = $this->getCartGroyId($data['cat_title'],$cat_id);
		$data['cat_id'] = $cat_id; 
	    return  $data;
	}

   
   //查询具体分类ID
   public function getCartGroyId($cat_title = '',$pid = 0)
   {
   	if(empty($cat_title)){
   		 return 0;
   	} 
   	$catObj = new Cat();
   	$where = " category='{$cat_title}' ";
   	$cat_data = $catObj->getCatData('id',$where);
   	if(empty($cat_data)){
         $addData = array(
              'pid' => $pid,
              'category' => $cat_title 
         	); 
        $id =  $catObj->addCatData($addData);
        return $id; 
   	} 
      return $cat_data[0]['id']; 
   } 

}

$class = new saveData();
$class->run();

 


