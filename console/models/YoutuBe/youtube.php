<?php 
namespace models\YoutuBe;
use models\db\db;

class youtube{
	 private   $conn = NULL;	
	 private   $table = 'youtube';
     private   $down_table = 'cra_youtube_download';

     public function __construct() 
     {
     	$this->conn = db::getinstance();  	 
     }

    /**
     * addYoutubeData
     *
     * [写入youtube主表]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $data
     * @return string
     */
     public function addYoutubeData($data)
     {
        $result  = $this->conn->insert($this->table, $data);
       return $this->conn->lastInsertId();
     }

    /**
     * getYoutubeData
     *
     * [查询主表数据]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param string $filds
     * @param string $where
     * @return array
     */
    public function getYoutubeData($filds='',$where='')
    {
        $sql = sprintf("select %s from  %s where %s",$filds,$this->table,$where);
        $data = $this->conn->fetchAll($sql);
        return $data;
    }

    /**
     * updateYoutubeData
     *
     * [修改youtube主表]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $data
     * @param $where
     * @return int
     */
    public function updateYoutubeData($data,$where)
    {
        $result =  $this->conn->update($this->table, $data, $where);
        return $result;
    }

    /**
     * addDownloadData
     *
     * [向下载表里添加数据]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $data
     * @return string
     */
    public function addDownloadData($data)
    {
        $result  = $this->conn->insert($this->down_table, $data);
        return $this->conn->lastInsertId();
    }

    /**
     * getDownloadData
     *
     * [获取下载数据]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param string $filds
     * @param string $where
     * @return array
     */
    public function getDownloadData($filds='*',$where='')
    {
        $sql = sprintf("select %s from  %s where %s",$filds,$this->down_table,$where);
        if(empty($where)){
            $sql = sprintf("select %s from  %s",$filds,$this->down_table);
        }
        $data = $this->conn->fetchAll($sql);
        return $data;
    }

    /**
     * updateDownData
     *
     * [修改下载表]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @param $data
     * @param $where
     * @return int
     */
    public function updateDownData($data,$where)
    {
        $result =  $this->conn->update($this->down_table, $data, $where);
        return $result;
    }

 }
