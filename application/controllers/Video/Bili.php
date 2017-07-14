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
     * [获取youtube视频]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $fields
     * @param $where
     * @param $order
     * @param $limit
     * @param bool|true $cache
     */
    public function getBiliVideoList($cat_id,$start = 0,$limit = 6,$order = 'sort DESC')
    {
        $data = $this->rpc_client->getBiliVideoList($cat_id,$start,$limit,$order);  
        if($data['code'] == RpcClient::RPC_COOD ){
            return $data['data']; 
        }
        return []; 
    }
}
