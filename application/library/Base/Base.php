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
        //windows 临时配
/*        return array(
              'static_url' => 'http://static.video.cc'
            );

*/		 
        $constant = \YaConf::get($case);
        return $constant;
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
