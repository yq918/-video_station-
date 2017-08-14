<?php
/**
 * @name Api
 * @desc µ÷È¡Êý¾Ý¿âÊý¾Ý£¬Ö´ÐÐÊý¾Ý¿â·½·¨,ÉèÖÃ»º´æ¿ÉÔÚAPI²ã¼Ó
 * @author zxr
 */
namespace controllers\Video;
use controllers\Client\RpcClient;

class Youtube{
    //protected $prc_model = 'video\Youtube';

   protected $prc_model = 'video\Youtube';

    protected $rpc_client = null;

    public function __construct($rpc_model = null)
    {
        if ($rpc_model) $this->prc_model = $rpc_model;
        $this->rpc_client = RpcClient::instance($this->prc_model);
    }

   /**
    * [getYoutubeVideoList description]
    * @param  [type]  $cat_id [description]
    * @param  integer $start  [description]
    * @param  integer $limit  [description]
    * @param  string  $order  [description]
    * @param  string  $where  [description]
    * @return [type]          [description]
    * 获取youtube视频列表
    */
    public function getYoutubeVideoList($cat_id,$start = 0,$limit = 6,$order ='sort DESC',$where = '')
    {
       $data = $this->rpc_client->getYoutubeVideoList($cat_id,$start,$limit,$order,$where); 
       if($data['code'] == RpcClient::RPC_COOD ){
            return $data['data']; 
        }
        return [];
   }

 

    /**
     * [showsDetailsVideo description]
     * @param  [type] $cat_id [description]
     * @param  [type] $start  [description]
     * @param  [type] $limit  [description]
     * @param  [type] $order  [description]
     * @return [type]         [description]
     * ÏêÇéÒ³»ñÈ¡ÊÓÆµÊý¾Ý
     */                           
    public function showsDetailsVideo($cat_id,$start,$limit,$cat_number = 5,$order = 'sort DESC' )
    {
        $data = $this->rpc_client->showsDetailsVideo($cat_id,$start,$limit,$cat_number,$order); 
        if($data['code'] == RpcClient::RPC_COOD ){
             return $data['data']; 
        }
        return [];
    }

  /**
   * [getColumnVideoData description]
   * @param  [type] $cat_id    [description]
   * @param  [type] $column_id [description]
   * @param  [type] $start     [description]
   * @param  [type] $limit     [description]
   * @param  [type] $order     [description]
   * @return [type]            [description]
   * 获取具体栏目的视频数据
   */
  public function getColumnVideoData($cat_id,$column_id,$start,$limit,$order)
  {
       $data = $this->rpc_client->getColumnVideoData($cat_id,$column_id,$start,$limit,$order); 
       if($data['code'] == RpcClient::RPC_COOD ){
             return $data['data']; 
       }
        return []; 
  }

   
    /**
     * [getVideoDataById description]
     * @param  [type] $id    [description]
     * @param  string $field [description]
     * @return [type]        [description]
     * 根据ID，获取具体的详细数据
     */
   public function getVideoDataById($id, $field = '')
    {
        if(empty($field)){
             $field = 'you_id,video_title,play_duration,playback_times,image_file_name,video_file_name,video_time_content';
         }
         $data = $this->rpc_client->getVideoDataById($id,$field);  
        if($data['code'] == RpcClient::RPC_COOD ){
            return $data['data']; 
        }
        return []; 
    } 


    /**
    * [getDataByWhere 根据条件获取数据]
    * @param  [type] $where  [description]
    * @param  string $fields [description]
    * @return [type]         [description]
    */
   public function getDataByWhere($where,$fields='*',$start = 0, $limit = 20)
   {
     $data = $this->rpc_client->getDataByWhere($where,$fields,$start, $limit); 
     if($data['code'] == RpcClient::RPC_COOD ){
            return $data['data']; 
        }
      return [];  
   }
   


}
