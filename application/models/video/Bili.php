<?php
namespace video;
use Db\Db;

class Bili
{
    private  $db;
    const BILITABLE = 'bilibili';
    const BILIDOWNTABLE = 'cra_bilibili_download';
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
    public function getBiliVideoList($cat_id,$start,$limit,$order)
    {
        $fields = array('where'=>"video_down=1 and video_img_down=1 AND status=1 AND cat_id={$cat_id} ", 
             'limit'=> "{$start},{$limit}", 
             'order'=> $order, 
             'field'=>'id',
             'table'=> self::BILITABLE);

        $data =  $this->db->fList($fields);
        if(empty($data)){
              return array('data' => $data);
        }

        $b_id_list = array_column($data,'id');
        $b_id_str  = implode(',',$b_id_list);
        $fields = array('where' => "img_upload=1 and video_upload=1 AND status=1 AND b_id in ({$b_id_str}) ",
                        'limit' => "{$start},{$limit}",
                        'order' => $order,
                        'field' => 'id,video_title,image_file_name,video_file_name',
                        'table' => self::BILIDOWNTABLE);
        $b_data = $this->db->fList($fields);
        return array('b_data' => $b_data);
    }

}
