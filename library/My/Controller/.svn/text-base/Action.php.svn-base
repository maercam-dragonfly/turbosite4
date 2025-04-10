<?php

class My_Controller_Action extends Zend_Controller_Action {

    public function init() {
        parent::init();
//        $cache =  realpath(APPLICATION_PATH . '/../cache');
//       // die();
//
//
//        $frontendOptions = array(
//            'lifetime' => 60
//        );
//
//// backend options
//        $backendOptions = array(
//            'cache_dir' => $cache // Directory where to put the cache files
//        );
//
//// make object
//        $cache = Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
//
//// make an id
//        $cacheID = 'user1';
//        echo !($cache->start($cacheID));
//// everything before this is not cached
//        if (!($cache->start($cacheID))) {
//// begin
    $this->view->karteczki = Doctrine_Query::create()
                ->from('Application_Model_CMS c')
                ->where('c.type = ?', 'karteczki')
                ->fetchArray();
//// end cache
//            $cache->end();
//        }else{
//            $cache->ge
//        }
//        
    }

}