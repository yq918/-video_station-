<?php
namespace video;
use Db\Db;

class Youtube
{
    private  $db;
    public function __construct()
    {
        $this->db =  Db::instance();
    }

    /**
     * getYoutubeVideoList
     *
     * []
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $cat_id
     * @param $start
     * @param $limit
     * @return array
     */
    public function getYoutubeVideoList($cat_id,$start,$limit,$order)
    {
        $fields = array('where'=>"video_down=1 and video_img_down=1 AND status=1 AND cat_id={$cat_id} ", 'limit'=>"{$start},{$limit}", 'order'=>$order, 'field'=>'id','table'=> 'youtube');
        $data =  $this->db->fList($fields);
        return array('key1' => 'A', 'key2' => 'B','data' => $data);
    }
}
