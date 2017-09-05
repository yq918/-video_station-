<?php
namespace vhost\www\controllers\Traits;

trait StaticConf{
    public  function options($node_case='',$flag='desktop',$append=array()){
          $option =  array(
               'comm' => array(
                   'style' => array(
                         '/css/bootstrap.min.css',
                         '/css/dashboard.css',
                         '/css/style.css',
                         '/fonts/open_sans.css',
                         '/fonts/poiret_one.css',
                         '/css/popuo-box.css'
                   ),
                   'script' => array(
                       //'/js/common/jquery-1.11.1.min.js'
                       //'/js/user.js'
                      )
               ),

                'index_index' => array(
                     'style' => array(
                         // '/css/bootstrap.min.css',
                         // '/css/dashboard.css',
                         // '/css/style.css',
                         // '/fonts/open_sans.css',
                         // '/fonts/poiret_one.css',
                         // '/css/popuo-box.css'
                     ),
                    'script' => array(
                            '/js/jquery-1.11.1.min.js',
                            '/js/modernizr.custom.min.js',
                            '/js/jquery.magnific-popup.js',
                            '/js/responsiveslides.min.js', 
                            '/js/bootstrap.min.js',

                    ),
                    'title' => '黑狗视频,个人视频,youtube视频,最新 最热 最爱的国外视频',
                    'keywords' => 'python,php,爬虫,youtube',
                    'description' => '个人爬虫网站',
                ),
               
               'single_index' => array(
                        'style' => array(),
                        'script' => array(
                            '/js/jquery-1.11.1.min.js',
                            '/js/modernizr.custom.min.js',
                            '/js/jquery.magnific-popup.js',
                            '/js/responsiveslides.min.js',  
                            '/js/bootstrap.min.js',
                            '/js/user.js'
                          ),
                    'title' => '个人视频网站,黑狗视频,个人视频,youtube视频,最新 最热 最爱的国外视频',
                    'keywords' => 'python,php,爬虫,youtube,github',
                    'description' => '我的个人爬虫网站',
                ),



            );

        if(isset($option[$node_case])){
                return array_merge( array_merge_recursive($option['comm'], $option[$node_case]),$append);
        }
        return [];
    }

}
 ?>