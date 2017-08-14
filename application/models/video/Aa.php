<?php
namespace video;
use Db\Db;
use Base\Base;

class Aa
{
    private  $db;
    const YOUTUBETABLE     = 'youtube';
    const YOUTUBEDOWNTABLE = 'cra_youtube_download';
    const CATEGORYTABLE    = 'cra_cat_column';
    
    public function __construct()
    {
        $this->db =  Db::instance();
    }



    /**
     * getClassifiedVideoData
     *
     * []
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $cat_id
     * @param $start
     * @param $limit
     * @return array
     * 获取具体分类视频数据
     */
    public function getClassifiedVideoData($cat_id,$start,$limit,$order,$condition = '')
    { 
        $where = "cat_id={$cat_id} ";  
        if(!empty($condition)){
           $where.= "AND ".$condition;
        }
        $list  = $this->getYoutubeData('id,cat_id,cat_column_id',$where,$start,$limit);
        $data  = isset($list['data']) ? $list['data'] : [] ; 
        if(empty($data)){
             return  Base::returnData( $data ); 
        } 
        $you_id_list = array_column($data,'id');
        $you_id_str  = implode(',',$you_id_list);
        $where       = " you_id in ({$you_id_str}) ";
        $rowsData    = $this->getDownloadData([],$where,$start,$limit,$order);  
        $rows =  isset($rowsData['data']) ?  $rowsData['data'] : [];

        $cat_id_list = array_column($data, 'cat_id','id');
        $cat_column_list = array_column($data, 'cat_column_id','id'); 
        foreach ($rows as $key => &$value) {
           $you_id = $value['you_id'];
           $value['cat_id'] = isset( $cat_id_list[$you_id] ) ? $cat_id_list[$you_id] : 0;
           $value['cat_column_id'] = isset( $cat_column_list[$you_id] ) ? $cat_column_list[$you_id] : 0;
        } 
        return  Base::returnData( $rows );  
    }


   /**
    * [getYoutubeData description]
    * @param  string  $field [description]
    * @param  string  $where [description]
    * @param  integer $start [description]
    * @param  integer $limit [description]
    * @return [type]         [description]
    * 获取视频主表数据
    */
   public function getYoutubeData($field='',$where='',$start = 0,$limit = 10,$order = '')
   { 
    if(empty($field)){
         $field = 'id';
    }
    $whereStr = "video_down=1 and video_img_down=1 AND status=1";
    if(empty($where)){
          $where = $whereStr;
    } 
    if(!empty($where)){
        $where = $whereStr.' AND '.$where;
    } 
     $fields = array( 'where'  => $where,
                      'limit'  => "{$start},{$limit}",  
                      'field'  => $field,
                      'table'  => self::YOUTUBETABLE
                    );   
    if(!empty($order)){
           $fields['order'] = $order;
    } 
    $data =  $this->db->fList($fields);
    return  Base::returnData( $data );   
 }


  /**
   * [getYoutubeDownData description]
   * @param  string  $fields [description]
   * @param  string  $where  [description]
   * @param  integer $start  [description]
   * @param  integer $limit  [description]
   * @return [type]          [description]
   * 获取下载的视频的数据
   */
  public function getDownloadData($field='',$where='',$start = 0,$limit = 10,$order = 'sort DESC ' )
  {
     if(empty($field)){
          $field = 'id,you_id,video_title,play_duration,image_file_name,video_file_name,video_time_content';
      }
      $whereStr = "img_upload=1 and video_upload=1 AND status=1 "; 
      if(empty($where)){
           $where = $whereStr;
      } 
      if(!empty($where)){
          $where = $whereStr.' AND '.$where;
      } 
         $fields = array('where' => $where,
                         'limit' => "{$start},{$limit}",
                         'order' => $order,
                         'field' => $field,
                         'table' => self::YOUTUBEDOWNTABLE);
        $you_data = $this->db->fList($fields);
        return  Base::returnData( $you_data );    
  } 
 

   /**
    * [showsDetailsVideo description]
    * @param  [type] $cat_id     [description]
    * @param  [type] $cat_number [description]
    * @param  [type] $start      [description]
    * @param  [type] $limit      [description]
    * @param  [type] $order      [description]
    * @return [type]             [description]
    * 获取栏目详情数据 
    */
   public function showsDetailsVideo($cat_id,$start,$limit,$order,$cat_number = 5)
   { 
          $category = $this->getCategory($cat_id,0,$cat_number); 
          $category_data = isset( $category['data'] ) ? $category['data'] : [] ;  

          if(empty($category_data)){
             return  Base::returnData( $category_data );
          }  
          $cat_data     = array_column($category_data,'column_name','id');
          $category_ids = array_column($category_data,'id');
          $category_id_str  = implode(',',$category_ids); 

          $where = "cat_column_id in ({$category_id_str})";    
          $list  = $this->getClassifiedVideoData($cat_id,$start,$limit,$order,$where);   
          $data  = isset($list['data']) ? $list['data'] : [];  
          if(empty($data)){
             return  Base::returnData( $data );
          } 
         $result = [];
         foreach($data as $k => $v){
             $cat_id  = $v['cat_id']; 
             $cat_column_id = $v['cat_column_id'];
             $result[$cat_column_id]['title']  = $cat_data[$cat_column_id];  
             $result[$cat_column_id]['list'][] = $v;  
         } 
         return  Base::returnData( $result ); 
   }  

  /**
   * [getColumnVideoData description]
   * @param  [type] $column_id [description]
   * @param  [type] $start     [description]
   * @param  [type] $limit     [description]
   * @param  [type] $order     [description]
   * @return [type]            [description]
   * 获取具体分类栏目下的数据
   */ 
   public function getColumnVideoData($cat_id,$column_id,$start,$limit,$order)
   {
      $where = "cat_column_id = {$column_id} ";     
      return $list  = $this->getClassifiedVideoData($cat_id,$start,$limit,$order,$where);   
   }



   /**
    * [getCategory description]
    * @param  [type] $pid   [description]
    * @param  [type] $start [description]
    * @param  [type] $limit [description]
    * @return [type]        [description]
    * 获取具体的分类名称
    */
   public function getCategory($pid, $start= 0,$limit = 10,$order = 'sort DESC' )
   { 
     $fields = array( 'where'=>"pid={$pid} and status=1 ", 
                      'limit'=>"{$start},{$limit}", 
                      'order'=> $order,  
                      'field'=>'id,pid,column_name', 
                      'table'=> self::CATEGORYTABLE 
                    );
        $data =  $this->db->fList($fields);
        return  Base::returnData( $data );   
   } 




}
