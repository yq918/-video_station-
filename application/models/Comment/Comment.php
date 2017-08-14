<?php
namespace Comment;
use Db\Db;
use Base\Base;

class Comment
{
    private  $db;
    private $tablename = 'cra_comment';  
    public function __construct()
    {
       $this->db =  Db::instance();
       $this->db->tablename = $this->tablename;
    }

   /**
    * [add 添加数据]
    * @param [type] $data [description]
    */
    public function add($data)
    {
         $id =  $this->db->insert($data);
         $result = array('id' => $id );
         return  Base::returnData( $result );   
    }

    /**
     * [getUserByWhere description]
     * @param  [type] $id     [description]
     * @param  string $fields [description]
     * @return [type]         [description]
     * 根据条件查询评论数据
     */
    public function getCommitByWhere($where='',$fields='*',$start,$limit)
    { 
          $fields = array(
             'where'=>"{$where}",  
             'limit'=> "{$start},{$limit}", 
             'field'=>$fields,
             'table'=> $this->tablename
             );
         $data = $this->db->fList($fields);
         return  Base::returnData( $data );   
   }  
 
}
