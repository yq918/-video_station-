<?php
namespace video;
use Db\Db;
use Base\Base;

class Bili
{
    private  $db;
    const BILITABLE = 'bilibili';
    const BILIDOWNTABLE = 'cra_bilibili_download';
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
    public function getBiliVideoList($cat_id,$start,$limit,$order,$where = '')
    {
        $fields = array(
             'where'=>"video_down=1 and video_img_down=1 AND status=1 AND cat_id={$cat_id} ", 
             'limit'=> "{$start},{$limit}", 
             'order'=> $order, 
             'field'=>'id,cat_column_id',
             'table'=> self::BILITABLE
             );
        if(!empty($where)){
            $fields['where'] .= ' AND '.$where;
        }

        $data =  $this->db->fList($fields);
        if(empty($data)){
             return  Base::returnData( $data );   
        }
        $col_id_list = array_column($data,'cat_column_id','id');
        $b_id_list = array_column($data,'id');
        $b_id_str  = implode(',',$b_id_list);
        $fields = array(
                  'where' => "img_upload=1 and video_upload=1 AND status=1 AND b_id in ({$b_id_str}) ",
                  'limit' => "{$start},{$limit}",
                  'order' => $order,
                  'field' => 'id,b_id,video_title,image_file_name,video_file_name,playback_times',
                  'table' => self::BILIDOWNTABLE
                  );
        $b_data = $this->db->fList($fields);  
        foreach ($b_data as $key => &$value) {
             $b_id = $value['b_id'];
             $value['cat_column_id'] = isset($col_id_list[$b_id]) ? $col_id_list[$b_id] : 0; 
             $value['id'] = $b_id;
        } 
        return  Base::returnData( $b_data );  
    }



    /**
     * [getVideoDataById description]
     * @param  [type] $id     [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     * 根据ID查询具体的数据
     */
    public function getVideoDataById($id,$field = '*')
    { 
          $fields = array(
             'where'=>"id={$id} ", 
             'limit'=> "0,1",  
             'field'=>'id,cat_column_id,cat_id',
             'table'=> self::BILITABLE
             );
         $data = $this->db->fList($fields);
         if(empty($data)){
             return  Base::returnData( $data );  
         }  
        $col_id_list = array_column($data,'cat_column_id','id');
        $cat_id_list = array_column($data,'cat_id','id'); 
        $b_id_list   = array_column($data,'id');
        $b_id_str    = implode(',',$b_id_list);  
        $fields = array(
                  'where' => " b_id in ({$b_id_str}) ",
                  'limit' => "0,1", 
                  'field' => $field ,
                  'table' => self::BILIDOWNTABLE
                  );
          $b_data = $this->db->fList($fields);   
          foreach ($b_data as $key => &$value) {
             $b_id = $value['b_id'];
             $value['cat_column_id'] = isset($col_id_list[$b_id]) ? $col_id_list[$b_id] : 0; 
             $value['cat_id'] = isset($cat_id_list[$b_id]) ? $cat_id_list[$b_id] : 0; 
             $value['id'] = $b_id;
         } 
        return  Base::returnData( $b_data );   
     } 

  
     /**
      * [getDataByWhere 根据条件获取数据]
      * @param  string $where  [description]
      * @param  string $fields [description]
      * @return [type]         [description]
      */
    public function getDataByWhere($where='',$fields='*',$start = 0, $limit = 20)
    { 
          $fields = array(
             'where' =>"{$where}",  
             'field' => $fields,
             'limit'=> "{$start},{$limit}",  
             'table' => self::BILIDOWNTABLE
             );
         $data = $this->db->fList($fields);
         return  Base::returnData( $data );   
     }
      

}
