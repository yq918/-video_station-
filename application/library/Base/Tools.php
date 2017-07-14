<?php
namespace  Base;

use Base\Base;


/**
 * Class Tools
 * @package library\Base
 *
 * [工具类]
 */

class Tools
{
    /**
     * style
     *
     * [处理样式路径]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     * @return string
     */
    public static function style()
    {
         $constant = self :: getConstantAttr();
         $static_url     =  $constant['static_url'] ;
         $tag = $constant['static_version'];
         $cssTag = "?v=" . $tag;
         $res = func_get_args();
         if (count($res) != count($res, 1)) {
                $res = $res[0];//如果不是一维数组转成一维
         }
            $styleArray = array_map(function ($aliases) use ($cssTag,$static_url) {
                $cssUrl =  $static_url.$aliases;
                return '<link href="' . $cssUrl . $cssTag . '" rel="stylesheet"  media="all" onerror="_cdnFallback(this)" />';
            }, $res);
             if(!is_array($styleArray)){
                 return '';
             }
            return implode('', array_filter($styleArray));
    }


    /**
     * 脚本别名加载（支持批量加载，后期可拓展为自动多文件压缩合并）
     * @return string
     * @modify jingwentian 新增了数组的传入方式 script(['a.js','b.js'])
     */
    public static function script()
    {
            $constant = self :: getConstantAttr();
            $static_url =  $constant['static_url'] ;
            $jsTag = "?v=".$constant['static_version'];
            $res = func_get_args();
            if (count($res) != count($res, 1)) {
                $res = $res[0];
            }
            $styleArray = array_map(function ($aliases) use($jsTag,$static_url) {
                $jsUrl =  $static_url.$aliases;
                return '<script src="'.$jsUrl.$jsTag.'" onerror="_cdnFallback(this)"></script>';
            }, $res);
            return implode('', array_filter($styleArray));
    }


    public static function getConstantAttr()
    {
        $constant       =   Base::getConstant('constant.constant');
        $static_url     =   isset($constant['static_url']) ? $constant['static_url'] : '';
        $static_version =   isset($constant['static_version']) ? $constant['static_version'] : '';
        return compact('static_url','static_version');
    }


    /**
     * [ParameterEncryption description]
     * @param [type] $str [description]
     */
    public function parameterEncryption($str)
    {
         $str = "13520v".$str."13520v";
         $str = base64_encode( $str);
         return $str; 
    }

    public function parameterDecryption($str)
    {
        $str = base64_decode($str);
        $str = substr($str,6);
        $str = substr($str,0,-6); 
        return $str; 
    }


  



    




}