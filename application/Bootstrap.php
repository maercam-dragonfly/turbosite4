<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    public function _initDoctrine() {
        $doctrineConfig = $this->getOption('doctrine');
        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $conn = Doctrine_Manager::connection($doctrineConfig['dsn'], 'doctrine');
        $conn->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);
        $conn->setCharset('utf8');
        return $conn;
    }

    protected function _initViewHelpers() {
        $this->bootstrap('view');

        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('My Application');
        $view->headLink()->appendStylesheet('/css/styl_2.css', 'screen', true, array('title' => 'styl2'))
                ->appendStylesheet('/css/normalize.css')
                ->appendStylesheet('/css/forms.css')
                ->appendStylesheet('/css/tables.css')
                ->appendStylesheet('/css/jquery.fancybox-1.3.4.css')
                ->appendStylesheet('/css/mirror.css')
                ->appendStylesheet('http://codemirror.net/theme/rubyblue.css')
                ->appendStylesheet('/css/paginate.css');

        $view->addHelperPath('ZendX/JQuery/View/Helper', 'ZendX_JQuery_View_Helper');

        $view->headScript()
                ->appendFile('http://jquery-ui.googlecode.com/svn-history/r3243/trunk/ui/i18n/jquery.ui.datepicker-pl.js')
                ->appendFile('/js/jquery.fancybox-1.3.4.pack.js')
                ->appendFile('/js/jquery.mousewheel-3.0.4.pack.js')
                ->appendFile('/js/jquery.maskedinput-1.3.min.js')
                ->appendFile('/js/jquery.tablesorter.min.js')
                ->appendFile('/js/sorttable.js')
                ->appendFile('/js/view.js')
                ->appendFile('/js/main.js')
                ->appendFile('/js/jquery.qtip.min.js')
                ->appendFile('/js/jquery.validate.js')
                ->appendFile('/js/jquery.paginate.js')
                ->appendFile('/js/jquery.bxSlider.min.js');
    }

    protected function _initNavigation() {

        /*   $this->bootstrap('layout');
          $layout = $this->getResource('layout');
          $view = $layout->getView();

          $config = $this->getOption('app');
          if ($_SERVER['HTTP_HOST'] == ($config['admin']['prefix'])) {
          $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/admin.navigation.xml', 'nav');
          } else {
          $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/www.navigation.xml', 'nav');
          }

          $navigation = new Zend_Navigation($config);
          $view->navigation($navigation);
          return $view; */
    }

    protected function _initRoutes() {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->removeDefaultRoutes();

        $basicRoute = new Zend_Controller_Router_Route(
                        ':action',
                        array(
                            'controller' => 'index',
                            'action' => 'index'
                        )
        );

        $artilceRoutePage = new Zend_Controller_Router_Route(
                        'aktualnosci/strona/:page',
                        array(
                            'controller' => 'index',
                            'action' => 'list'
                        )
        );

        $indexRoute = new Zend_Controller_Router_Route(
                        '/',
                        array(
                            'controller' => 'index',
                            'action' => 'index',
                            'page' => 1
                        )
        );
        $artilceRoute = new Zend_Controller_Router_Route_Regex(
                        'aktualnosci/([a-zA-Z0-9\-_!,]+)\.html',
                        array(
                            'controller' => 'index',
                            'action' => 'news'
                        ),
                        array(
                            1 => 'slug'
                        ),
                        'aktualnosci/%s.html'
        );

        $downloadRouteBase = new Zend_Controller_Router_Route(
                        'doc/',
                        array(
                            'module' => 'default',
                            'controller' => 'index',
                            'action' => 'download'
                        )
        );
/*
        $galleryRoute = new Zend_Controller_Router_Route(
                        'galeria/:action/:slug',
                        array(
                            'module' => 'default',
                            'controller' => 'gallery',
                            'action' => 'index',
                            'slug' => null
                        )
        );
*/
        $downloadRoute = new Zend_Controller_Router_Route(
                        'doc/:actionName/:filename',
                        array(
                            'module' => 'default',
                            'controller' => 'index',
                            'action' => 'download'
                        )
        );
        /* ************************************************** */
        //admin
        $adminModuleName = 'zbardzodluganazwapaneluadministratora/';
        $adminRoute = new Zend_Controller_Router_Route(
                        $adminModuleName . ':controller/:action/*',
                        array(
                            'module' => 'admin',
                            'controller' => 'index',
                            'action' => 'index'
                        )
        );

        $articleEditRoute = new Zend_Controller_Router_Route(
                        $adminModuleName . 'article/:action/:slug',
                        array(
                            'module' => 'admin',
                            'controller' => 'article',
                            'action' => 'edit'
                        )
        );

        $studentEditRoute = new Zend_Controller_Router_Route(
                        $adminModuleName . 'zapisy/:action/:id',
                        array(
                            'module' => 'admin',
                            'controller' => 'zapisy',
                            'action' => 'edit'
                        )
        );
        $konkursEditRoute = new Zend_Controller_Router_Route(
                        $adminModuleName . 'konkurs/:action/:id',
                        array(
                            'module' => 'admin',
                            'controller' => 'konkurs',
                            'action' => 'edit'
                        )
        );

        $galleryEditRoute = new Zend_Controller_Router_Route(
                        $adminModuleName . 'gallery/:action/:id',
                        array(
                            'module' => 'admin',
                            'controller' => 'gallery',
                            'action' => 'edit'
                        )
        );

        $contentEditRoute = new Zend_Controller_Router_Route(
                        $adminModuleName . 'content/:action/:id',
                        array(
                            'module' => 'admin',
                            'controller' => 'content',
                            'action' => 'edit'
                        )
        );

        $router->addRoute('default', $basicRoute);
        $router->addRoute('downloadbase', $downloadRouteBase);
        $router->addRoute('download', $downloadRoute);
        $router->addRoute('article', $artilceRoute);
        $router->addRoute('articlePaginate', $artilceRoutePage);
        $router->addRoute('indexRoute', $indexRoute);
       // $router->addRoute('gallery', $galleryRoute);
        /******************************************************* */
        $router->addRoute('admin', $adminRoute);
        //$router->addRoute('adminmigration',$migrationRoute); 
        $router->addRoute('admineditgallery', $galleryEditRoute);
        $router->addRoute('adminarticleedit', $articleEditRoute);
        $router->addRoute('adminstudent', $studentEditRoute);
        $router->addRoute('adminkonkurs', $konkursEditRoute);
    }

    protected function _initAutoloader() {
        $autoloader = Zend_Loader_Autoloader::getInstance();

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                    'basePath' => APPLICATION_PATH,
                    'namespace' => '',
                    'resourceTypes' => array(
                        'form' => array(
                            'path' => 'forms/',
                            'namespace' => 'Form_'
                        ),
                        'model' => array(
                            'path' => 'models/',
                            'namespace' => 'Model_'
                        ),
                    )
                ));
        return $autoloader;
    }

    public function _initMail() {
        if ('development' === APPLICATION_ENV) {
            $smtp_server = 'smtp.gmail.com';
            $config = array(
                'ssl' => 'ssl',
                'port' => 465,
                'auth' => 'login',
                'username' => 'oskautoturbo@gmail.com',
                'password' => 'malpa1234'
            );
            $transport = new Zend_Mail_Transport_Smtp($smtp_server, $config);
        } else {
            $transport = new Zend_Mail_Transport_Sendmail();
        }

        Zend_Mail::setDefaultTransport($transport);
        Zend_Mail::setDefaultFrom('no-reply@oskautoturbo.pl', 'OSK Auto Turbo');
    }

    protected function _initPlugins() {
        $this->bootstrap("frontController");
        $this->frontController->registerPlugin(new My_ModuleNavigation);
    }
	


}

