<?php
/**
 * @name Api
 * @desc 用户API
 * @author zxr
 */
namespace controllers\User;
use controllers\Client\RpcClient;

class User{
    protected $prc_model = 'User\User';

    protected $rpc_client = null;

    public function __construct($rpc_model = null)
    {
        if ($rpc_model) $this->prc_model = $rpc_model;
        $this->rpc_client = RpcClient::instance($this->prc_model);
    }

   /**
    * [add 添加用户]
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
     * [getUserByWhere 根据条件查询用户]
     * @param  string $where  [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     */
    public function getUserByWhere($where='',$fields='*')
    {
       $data = $this->rpc_client->getUserByWhere($where,$fields); 
       if($data['code'] == RpcClient::RPC_COOD ){
             return $data['data']; 
        }
        return [];
    } 
 
}
