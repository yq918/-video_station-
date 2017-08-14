<?php
/**
 * @name Api
 * @desc 调取数据库数据，执行数据库方法,设置缓存可在API层加
 * @author zxr
 */
namespace controllers\Video;
use controllers\Client\RpcClient;

class Bili{
    protected $prc_model = 'video\Bili';

    protected $rpc_client = null;

    public function __construct($rpc_model = null)
    {
        if ($rpc_model) $this->prc_model = $rpc_model;
        $this->rpc_client = RpcClient::instance($this->prc_model);
    }

    /**
     * getYoutubeVideo
     *
     * [获取B站视频]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $fields
     * @param $where
     * @param $order
     * @param $limit
     * @param bool|true $cache
     */
    public function getBiliVideoList($cat_id,$start = 0,$limit = 6,$order = 'sort DESC',$where = '')
    {
        $data = $this->rpc_client->getBiliVideoList($cat_id,$start,$limit,$order,$where);  
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
             $field = 'b_id,video_title,video_format,video_size,image_file_name,video_file_name,playback_times';
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
