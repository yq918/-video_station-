<?php 
namespace models\Cat;
use models\db\db;

class Cat{
	 private   $conn = NULL;	
	 private   $table = 'cra_category'; 

     public function __construct() 
     {
     	$this->conn = db::getinstance();  	 
     }

    /**
     * addYoutubeData
     *
     * [写入分类主表]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $data
     * @return string
     */
     public function addCatData($data)
     {
        $result  = $this->conn->insert($this->table, $data);
        return $this->conn->lastInsertId();
     }

    /**
     * getCatData
     *
     * [查询分类数据]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param string $filds
     * @param string $where
     * @return array
     */
    public function getCatData($filds='*',$where='')
    {
        $sql = sprintf("select %s from  %s where %s",$filds,$this->table,$where);
       if(empty($where)){
           $sql = sprintf("select %s from  %s ",$filds,$this->table);
           }        
        $data = $this->conn->fetchAll($sql);
        return $data;
    } 
 }
