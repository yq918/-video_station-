<?php
namespace vhost\www\controllers\Traits;

trait StaticConf{
    public  function options($node_case='',$flag='desktop',$append=array()){
          $option =  array(
               'comm' => array(
                   'style' => array(
                       '/css/common/bootstrap.css',
                   ),
                   'script' => array(
                       '/js/common/jquery-1.11.1.min.js'
                      )
               ),
                'index_index' => array(
                     'style' => array(
                         '/css/index/style.css',
                         '/css/index/flexslider.css',
                         '/css/index/animate.css',
                         '/css/common/Montserrat.css',
                     ),
                    'script' => array(
                           '/js/common/jquery.easydropdown.js',
                           '/js/common/jquery.flexslider.js',
                           '/js/index/wow.min.js'
                    ),
                    'title' => '开心一刻',
                    'keywords' => '',
                    'description' => '',
                ),
            );

        if(isset($option[$node_case])){
                return array_merge( array_merge_recursive($option['comm'], $option[$node_case]),$append);
        }
        return [];
    }

}
 ?>