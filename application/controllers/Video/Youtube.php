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
     * getYoutubeVideo
     *
     * [»ñÈ¡youtubeÊÓÆµ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $fields
     * @param $where
     * @param $order
     * @param $limit
     * @param bool|true $cache
     */
    public function getYoutubeVideoList($cat_id,$start = 0,$limit = 6,$order = 'sort DESC')
    {
        //$data = $this->rpc_client->getYoutubeVideoList($cat_id,$start,$limit,$order);
       
         $data = $this->rpc_client->getClassifiedVideoData($cat_id,$start,$limit,$order);

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
   * [getCategory description]
   * @param  [type]  $pid   [description]
   * @param  integer $start [description]
   * @param  integer $limit [description]
   * @param  string  $order [description]
   * @return [type]         [description]
   * 获取分类栏目
   */
  public function getCategory($pid, $start= 0,$limit = 10,$order = 'sort DESC')
  {
      $data = $this->rpc_client->getCategory($pid,$start,$limit,$order); 
       if($data['code'] == RpcClient::RPC_COOD ){
             return $data['data']; 
       }
        return [];  
  }




}
