<?php
namespace Base;

class Base
{
    /**
     * getConstant
     *
     * [获取系统常量]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public static function getConstant($case)
    {
        $constant = \YaConf::get($case);
        return $constant;
    }




}