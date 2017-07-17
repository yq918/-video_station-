<?php
namespace Base;

class Base
{
    /**
     * getConstant
     *
     * [»ñÈ¡ÏµÍ³³£Á¿]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public static function getConstant($case = 'constant.constant')
    {   
        $constant = \YaConf::get($case);
        return $constant;
    }

    /**
     * [catTypeData description]
     * @return [type] [description]
     * 分类信息
     */
    public static function getCatTypeData($cat_name = '')
    {
        $data = array(
              'funny'   => '1',
              'popular' => '2', 
              'music'   => '3',
              'sports'  => '4',
            ); 
        if(empty($cat_name)){
            return $data;
        } 
        return isset($data[$cat_name]) ? $data[$cat_name] : ''; 
    }

     /**
      * [notFound description]
      * @return [type] [description]
      * 手动给404页面
      */
    public function notFound()
    {
        header("HTTP/1.1 404 Not Found");  
        header("Status: 404 Not Found");  
        exit;  
    }





}
