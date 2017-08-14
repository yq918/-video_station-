<?php
namespace Cat;
use Db\Db;
use Base\Base; 

class Category
{
    private  $db; 
    const CATEGORYTABLE    = 'cra_cat_column';
    
    public function __construct()
    {
        $this->db =  Db::instance();
    }


/**
    * [getCategory description]
    * @param  [type] $pid   [description]
    * @param  [type] $start [description]
    * @param  [type] $limit [description]
    * @return [type]        [description]
    * 获取具体的分类名称
    */
   public function getCategory($pid, $start= 0,$limit = 10,$order = 'sort DESC' )
   { 
     $fields = array( 'where'=>"pid={$pid} and status=1 ", 
                      'limit'=>"{$start},{$limit}", 
                      'order'=> $order,  
                      'field'=>'id,pid,column_name', 
                      'table'=> self::CATEGORYTABLE 
                    );
        $data =  $this->db->fList($fields);
        return  Base::returnData( $data );   
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
     $fields = array( 'where'=>"id={$id} and status=1 ", 
                      'limit'=>"{$start},{$limit}", 
                      'order'=> $order,  
                      'field'=>'id,pid,column_name', 
                      'table'=> self::CATEGORYTABLE 
                    );
        $data =  $this->db->fList($fields);
        return  Base::returnData( $data );
   }

 }