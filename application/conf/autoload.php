<?php
function load($class)
{ 
    $file = '';
    if(!empty($class)){
        $classFile = str_replace('\\',DIRECTORY_SEPARATOR,$class);
        $file  = SITEBASE.DIRECTORY_SEPARATOR.$classFile.'.php';
    }  
    if(!empty($file) && file_exists($file)){
         require $file;
    }
}
spl_autoload_register('load');
