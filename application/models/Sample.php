<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author root
 */
//namespace models;

class SampleModel {
    public function __construct() {
    }   
    
    public function selectSample() {
        return 'Model Hello World!';
    }

    public function insertSample($arrInfo) {
        return true;
    }
}
