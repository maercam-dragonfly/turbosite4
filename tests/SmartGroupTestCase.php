<?php

//require_once '../library/Zend/Test/PHPUnit/SmartGroupTestCase.php';
//require_once 'Zend/Loader/Autoloader.php';

class SmartGroupTestCase extends Zend_Test_PHPUnit_ControllerTestCase {

    /**     
     * @var Zend_Application
     */
    protected $application;

    protected function setUp() {
        
        // require_once 'Zend/Loader/Autoloader.php';
        //Zend_Loader_Autoloader::getInstance();
        $this->bootstrap = array($this, 'appBootstrap');
        //parent::setUp();
    }

    public function appBootstrap() {
        $this->getFrontController()->addControllerDirectory(APPLICATION_PATH . '/modules/default/controllers','default');
        $this->application = new Zend_Application(APPLICATION_ENV,
                        APPLICATION_PATH . '/configs/application.ini');
        
        $this->application->bootstrap();
        
    }

    

}
