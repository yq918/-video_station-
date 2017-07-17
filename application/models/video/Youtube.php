<?php
namespace video;
use Db\Db;

class Youtube
{
    private  $db;
    const YOUTUBETABLE = 'youtube';
    const YOUTUBEDOWNTABLE = 'cra_youtube_download';
    const CATEGORYTABLE  = 'cra_category';
    const RPC_COOD = '1000';
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
    public function getYoutubeVideoList($cat_id,$start,$limit,$order,$where = '')
    {
         $fields = array('where'=>"video_down=1 and video_img_down=1 AND status=1 AND cat_id={$cat_id} ", 'limit'=>"{$start},{$limit}", 'order'=>$order, 'field'=>'id','table'=> self::YOUTUBETABLE); 

        if(empty($cat_id)){
            $fields = array('where'=>"video_down=1 and video_img_down=1 AND status=1 ", 'limit'=>"{$start},{$limit}", 'order'=>$order, 'field'=>'id,cat_id,category_id','table'=> self::YOUTUBETABLE);
        }
        if(!empty($where )){ 
            $fields['where'] .= $where;
        } 

        $data =  $this->db->fList($fields);
        if(empty($data)){
              return array('code'=>self::RPC_COOD,'data' => $data,'y_data' => $data);
        }
        $you_id_list = array_column($data,'id');
        $you_id_str  = implode(',',$you_id_list);
        $fields = array('where' => "img_upload=1 and video_upload=1 AND status=1 AND you_id in ({$you_id_str}) ",
                        'limit' => "{$start},{$limit}",
                        'order' => $order,
                        'field' => 'id,video_title,play_duration,image_file_name,video_file_name,video_time_content',
                        'table' => self::YOUTUBEDOWNTABLE);
        $you_data = $this->db->fList($fields);
        return array('code'=>self::RPC_COOD,'data' => $you_data,'y_data' => $data );
    }



   public function showsDetailsVideo($cat_id,$start,$limit,$order)
   {
          $category = $this->getCategory($cat_id,0,5);
          $category_data = $category['data']; 
          if(empty($category_data)){
                return  array('code'=>self::RPC_COOD,'data' => []); 
          }
          $cat_data = array_column($category_data,'category','id');

          $category_ids = array_column($category_data,'id');
          $category_id_str  = implode(',',$category_ids);
          $where   = " AND category_id in ({$category_id_str})";  
          $youData =  $this->getYoutubeVideoList(0,$start,$limit,$order,$where);  
          $data    = $youData['data'];
          $y_data  = $youData['y_data'];
          if(empty($data)){
             return  array('code'=>self::RPC_COOD,'data' => []); 
          }
         $result = [];
         foreach($y_data as $k => $v){
             $category_id  = $v['category_id'];
             $you_id  = $v['id'];
              foreach($data as $key => $val){
                if($val['id'] == $you_id){
                        $result[$category_id]['title'] = $cat_data[$category_id];
                         $val['cat_title'] = $cat_data[$category_id];
                         $result[$category_id][] = $val; 
                  }
              } 
         }

         return  array('code'=>self::RPC_COOD,'data' => $result); 
        
   } 



   /**
    * [getCategory description]
    * @param  [type] $pid   [description]
    * @param  [type] $start [description]
    * @param  [type] $limit [description]
    * @return [type]        [description]
    * 获取具体的分类名称
    */
   public function getCategory($pid, $start= 0,$limit = 10 )
   {

     $fields = array('where'=>"pid={$pid} and status=1 ", 'limit'=>"{$start},{$limit}", 'order'=>' sort DESC ',  'field'=>'id,category', 'table'=> self::CATEGORYTABLE );
        $data =  $this->db->fList($fields);
        return array('code'=>self::RPC_COOD,'data' => $data); 
   } 
}
