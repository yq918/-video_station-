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
    public static function getConstant($case)
    {
        //windows 临时配
/*        return array(
              'static_url' => 'http://static.video.cc'
            );

*/		 
        $constant = \YaConf::get($case);
        return $constant;
    }




}
