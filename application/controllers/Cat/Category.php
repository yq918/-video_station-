<?php
/**
 * @name Api
 * @desc 获取分类信息
 * @author zxr
 */
namespace controllers\Cat;
use controllers\Client\RpcClient;

class Category{
     
   protected $prc_model = 'Cat\Category';

    protected $rpc_client = null;

    public function __construct($rpc_model = null)
    {
        if ($rpc_model) $this->prc_model = $rpc_model;
        $this->rpc_client = RpcClient::instance($this->prc_model);
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

  /**
   * [getCategoryById description]
   * @param  [type]  $id    [description]
   * @param  integer $start [description]
   * @param  integer $limit [description]
   * @param  string  $order [description]
   * @return [type]         [description]
   */
  public function getCategoryById($id, $start= 0,$limit = 10,$order = 'sort DESC')
  {
      $data = $this->rpc_client->getCategoryById($id,$start,$limit,$order); 
       if($data['code'] == RpcClient::RPC_COOD ){
             return $data['data']; 
       }
        return [];  
  }





}
