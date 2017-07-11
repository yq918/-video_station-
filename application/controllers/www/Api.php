<?php
/**
 * @name Api
 * @desc ��ȡ���ݿ����ݣ�ִ�����ݿⷽ��,���û������API���
 * @author zxr
 */
namespace controllers\www;
use controllers\Client\RpcClient;

class Api{
    protected $prc_model = 'video\Test';

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
    public function getYoutubeVideo($fields,$where,$order,$limit,$cache=true)
    {
        $data = $this->rpc_client->aa();
        print_r($data);
    }


    public function selectSample() {
        return 'AAAAAAAAAAA   AAAAAAAAAA  Model  Aa ';
    }

    public function insertSample($arrInfo) {
        return true;
    }
}
