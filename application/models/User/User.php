<?php
namespace User;
use Db\Db;
use Base\Base;

class User
{
    private  $db;
    private $tablename = 'cra_user';  
    public function __construct()
    {
       $this->db =  Db::instance();
       $this->db->tablename = $this->tablename;
    }

   /**
    * [add 添加数据]
    * @param [type] $data [description]
    */
    public function add($data){
         $id =  $this->db->insert($data);
         $result = array('id' => $id );
         return  Base::returnData( $result );   
    }
     
    /**
     * [getUserByWhere description]
     * @param  [type] $id     [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     * 根据条件查询用户数据
     */
    public function getUserByWhere($where='',$fields='*')
    { 
          $fields = array(
             'where'=>"{$where}",  
             'field'=>$fields,
             'table'=> $this->tablename
             );
         $data = $this->db->fList($fields);
         return  Base::returnData( $data );   
     } 

}
