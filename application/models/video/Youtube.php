<?php
namespace video;
use Db\Db;
use Base\Base;
use Cat\Category;
class Youtube
{
    private  $db;
    private $_cat;
    const YOUTUBETABLE     = 'youtube';
    const YOUTUBEDOWNTABLE = 'cra_youtube_download';
     
    
    public function __construct()
    {
        $this->db =  Db::instance();
        $this->_cat = new Category();
    }

    /**
     * [getYoutubeVideoList description]
     * @param  [type] $cat_id [description]
     * @param  [type] $start  [description]
     * @param  [type] $limit  [description]
     * @param  [type] $order  [description]
     * @param  string $where  [description]
     * @return [type]         [description]
     * 获取youtube视频列表
     */
    public function getYoutubeVideoList($cat_id,$start,$limit,$order,$where = '')
    {
        $fields = array(
             'where'=>"video_down=1 and video_img_down=1 AND status=1 AND cat_id={$cat_id} ", 
             'limit'=> "{$start},{$limit}", 
             'order'=> $order, 
             'field'=>'id,cat_column_id',
             'table'=> self::YOUTUBETABLE
             );
        if(!empty($where)){
            $fields['where'] .= ' AND '.$where;
        }
        $data =  $this->db->fList($fields);
        if(empty($data)){
             return  Base::returnData( $data );   
        }
        $col_id_list = array_column($data,'cat_column_id','id');
        $you_id_list = array_column($data,'id');
        $you_id_str  = implode(',',$you_id_list);
        $fields = array(
            'where' => "img_upload=1 and video_upload=1 AND status=1 AND you_id in ({$you_id_str}) ",
            'limit' => "{$start},{$limit}",
            'order' => $order,
            'field' => 'id,you_id,video_title,play_duration,image_file_name,video_file_name,video_time_content',
           'table' => self::YOUTUBEDOWNTABLE
                  );
        $data = $this->db->fList($fields);  
        foreach ($data as $key => &$value) {
             $b_id = $value['you_id'];
             $value['cat_column_id'] = isset($col_id_list[$b_id]) ? $col_id_list[$b_id] : 0; 
             $value['id'] = $b_id;
        } 
        return  Base::returnData( $data );  
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
           $value['id'] = $you_id;
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
   public function showsDetailsVideo($cat_id,$start,$limit,$cat_number,$order)
   {  
          $category = $this->_cat->getCategory($cat_id,0,$cat_number); 
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
     * [getVideoDataById description]
     * @param  [type] $id     [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     * 根据ID查询具体的数据
     */
    public function getVideoDataById($id,$field = '*')
    { 
          $fields = array(
             'where' => "id={$id} ", 
             'limit' => "0,1",  
             'field' => 'id,cat_column_id,cat_id',
             'table' => self::YOUTUBETABLE
             );
         $data = $this->db->fList($fields);
         if(empty($data)){
             return  Base::returnData( $data );  
         }  
        $col_id_list = array_column($data,'cat_column_id','id');
        $cat_id_list = array_column($data,'cat_id','id'); 
        $b_id_list   = array_column($data,'id');
        $b_id_str    = implode(',',$b_id_list);  
        $fields      = array(
                        'where' => " you_id in ({$b_id_str}) ",
                        'limit' => "0,1", 
                        'field' => $field ,
                        'table' => self::YOUTUBEDOWNTABLE
                        );
          $b_data = $this->db->fList($fields);   
          foreach ($b_data as $key => &$value) {
             $b_id = $value['you_id'];
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
             'table' => self::YOUTUBEDOWNTABLE
             );
         $data = $this->db->fList($fields);
         return  Base::returnData( $data );   
     }
      
      



}
