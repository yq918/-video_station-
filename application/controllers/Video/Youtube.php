<?php
/**
 * @name Api
 * @desc ��ȡ���ݿ����ݣ�ִ�����ݿⷽ��,���û������API���
 * @author zxr
 */
namespace controllers\Video;
use controllers\Client\RpcClient;

class Youtube{
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
     * [��ȡyoutube��Ƶ]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $fields
     * @param $where
     * @param $order
     * @param $limit
     * @param bool|true $cache
     */
    public function getYoutubeVideoList($cat_id,$start = 0,$limit = 6,$order = 'sort DESC')
    {
        $data = $this->rpc_client->getYoutubeVideoList($cat_id,$start,$limit,$order);
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
     * ����ҳ��ȡ��Ƶ����
     */
    public function showsDetailsVideo($cat_id,$start,$limit,$order = 'sort DESC')
    {
        $data = $this->rpc_client->showsDetailsVideo($cat_id,$start,$limit,$order);
        return $data; 
    }


}
