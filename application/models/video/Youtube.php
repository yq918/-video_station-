<?php
namespace video;
use Db\Db;

class Youtube
{
    private  $db;
    const YOUTUBETABLE = 'youtube';
    const YOUTUBEDOWNTABLE = 'cra_youtube_download';
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
        $fields = array('where'=>"video_down=1 and video_img_down=1 AND status=1 AND cat_id={$cat_id} ", 'limit'=>"{$start},{$limit}", 'order'=>$order, 'field'=>'id','table'=> self::YOUTUBETABLE);
        $data =  $this->db->fList($fields);
        if(empty($data)){
            return $data;
        }
        $you_id_list = array_column($data,'id');
        $you_id_str  = implode(',',$you_id_list);
        $fields = array('where'=>"img_upload=1 and video_upload=1 AND status=1 AND you_id in ({$you_id_str}) ",
                        'limit'=>"{$start},{$limit}",
                        'field'=>'id,video_title,play_duration,image_file_name,video_file_name,video_upload,video_time_content',
                        'table'=> self::YOUTUBEDOWNTABLE);

        $you_data = $this->db->fList($fields);

        return array( 'data' => $data,'you_data' => $you_data);
    }

}
