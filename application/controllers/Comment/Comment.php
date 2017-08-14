<?php
/**
 * @name Api
 * @desc 评论API
 * @author zxr
 */
namespace controllers\Comment;
use controllers\Client\RpcClient;

class Comment{
    protected $prc_model = 'Comment\Comment';

    protected $rpc_client = null;

    public function __construct($rpc_model = null)
    {
        if ($rpc_model) $this->prc_model = $rpc_model;
        $this->rpc_client = RpcClient::instance($this->prc_model);
    }

   /**
    * [add 添加评论]
    * @param array $data [description]
    */
    public function add(array $data){
        $data = $this->rpc_client->add($data);   
        if($data['code'] == RpcClient::RPC_COOD ){
                return $data['data']; 
            }
        return []; 
    }

     /**
     * [getUserByWhere 根据条件查询评论]
     * @param  string $where  [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     */
    public function getCommitByWhere($where='',$fields='*',$start,$limit)
    {
       $data = $this->rpc_client->getCommitByWhere($where,$fields,$start,$limit); 
       if($data['code'] == RpcClient::RPC_COOD ){
             return $data['data']; 
        }
        return [];
    }  

}
