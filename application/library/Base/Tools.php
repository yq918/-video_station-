<?php
namespace  Base;

use Base\Base;


/**
 * Class Tools
 * @package library\Base
 *
 * [¹¤¾ßÀà]
 */

class Tools
{
    const SECRET = '13520v';
    const SECRET_RIGHT = '520';
    const ROUND_STR = 'ZXR';
    /**
     * style
     *
     * [´¦ÀíÑùÊ½Â·¾¶]
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
                $res = $res[0];//Èç¹û²»ÊÇÒ»Î¬Êý×é×ª³ÉÒ»Î¬
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
     * ½Å±¾±ðÃû¼ÓÔØ£¨Ö§³ÖÅúÁ¿¼ÓÔØ£¬ºóÆÚ¿ÉÍØÕ¹Îª×Ô¶¯¶àÎÄ¼þÑ¹ËõºÏ²¢£©
     * @return string
     * @modify jingwentian ÐÂÔöÁËÊý×éµÄ´«Èë·½Ê½ script(['a.js','b.js'])
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
     * 拼URL， 拼执行参数加密 
     */
    public static function parameterEncryption(array $arr)
    {
        foreach ($arr as $key => $value) {
             if(is_numeric($value)){
                  $value = $value+100;
              }
             if(is_string($value)){
                 $value=$value.self::ROUND_STR;
             }
         $value = self::SECRET.$value.self::SECRET_RIGHT;
         $value = base64_encode( $value);
         $arr[$key] = $value;
        } 
       return http_build_query( $arr ); 
    }

    /**
     * [parameterDecryption description]
     * @param  [type] $str [description]
     * @return [type]      [description]
     * 解密函数
     */
    public static function parameterDecryption($str)
    {
        $str = base64_decode($str);
        $str = substr($str,6);
        $str = substr($str,0,-3); 
        if(is_numeric($str)){
            return $str-100;
        }
        if(is_string($str)){
            $str =  substr($str,0,-3); 
        }
        return $str; 
    }


/**
   * [generateLinks description]
   * @return [type] [description]
   * 拼URL
   */
  public static function generateLinks($url, array $param)
  {   
     $params = Tools::parameterEncryption($param); 
     $linkUrl = $url.'?'.$params;
     return $linkUrl; 
  }



  
    /**
     * ajax return data
     */
    public static function ajaxReturn($code=true, $msg='', $data=[], $subCode=0, $callBackName='')
    {
        $data = array(
                  'code' => $code ? 'success' : 'error',
                  'msg'  => $msg,
                  'data' => $data,
                  'sub_code' => $subCode,
                );
        if (empty($callBackName))
            self::returnAjaxJson($data);

        //jsonp
        die("$callBackName(".json_encode($data, true).")");
    }


/**
     * 获取json字符串
     *
     * @param $array
     * @return string
     */
    public static function returnAjaxJson($array) {
        if (!headers_sent()){
            header("Content-Type: application/json; charset=utf-8");
        }
        echo(json_encode($array));
        ob_end_flush();
        exit;
    } 


/**
 * [is_mobile 验证是否是手机]
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
public static function is_mobile($str) 
{ if(preg_match("/^1[34578]{1}\d{9}$/",$str)){  
         return true;
     }
     return false;
}
 



}
