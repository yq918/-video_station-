<?php
namespace Base;

class Base
{
    /**
     * getConstant
     *
     * [��ȡϵͳ����]
     * @author zhangxuanru  [zhangxuanru@eventmosh.com]
     */
    public static function getConstant($case)
    {
        $constant = \YaConf::get($case);
        return $constant;
    }




}