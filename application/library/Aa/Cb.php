<?php
/**
 * @name SampleModel
 * @desc sample���ݻ�ȡ��, ���Է������ݿ⣬�ļ�������ϵͳ��
 * @author root
 */
namespace library\Aa;

class Cb{
    public function __construct() {
    }

    public function selectSample() {
        return 'LIBARY Hello World!';
    }

    public function insertSample($arrInfo) {
        return true;
    }

    public   function aa()
    {
        echo '__aaa';
    }
}
