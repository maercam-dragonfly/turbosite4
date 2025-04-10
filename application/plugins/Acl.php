<?php

class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        /* Lista kontroli dostępu */
        $acl = new Zend_Acl();
        $acl->addRole('guest');
        $acl->addRole('writer', 'guest');
        $acl->addRole('admin');
        $acl->addResource('default:index');
        $acl->addResource('default:gallery');
        $acl->addResource(':index');
        $acl->addResource('default:error');

        $acl->addResource('admin:index');
        $acl->addResource('admin:article');
        $acl->addResource('admin:konkurs');
        $acl->addResource('admin:zapisy');
        $acl->addResource('admin:content');
        $acl->addResource('admin:gallery');
        $acl->addResource('admin:migration');
        
        $acl->deny('guest', 'admin:index');
        $acl->deny('guest', 'admin:article');
        $acl->deny('guest', 'admin:konkurs');
        $acl->deny('guest', 'admin:zapisy');
        $acl->deny('guest', 'admin:content');
        $acl->deny('guest', 'admin:migration');
        
        $acl->allow('guest', ':index');
        $acl->allow('guest', 'default:index');
        $acl->allow('guest', 'default:gallery');
        $acl->allow('guest', 'admin:index', 'login');
        $acl->allow('guest', 'default:error');
        $acl->allow('writer');
        $acl->allow('admin');

        /*  Użytkownik */
        $user = Zend_Auth::getInstance()->getIdentity();
        if (null === $user) {
            $role = 'guest';
        } else {
            $role = $user->role;
        }
        //Zend_Debug::dump($user);
        /* Czy użytkownik ma prawo dostępu? */
        try {
            if (!$acl->isAllowed($role, $request->getModuleName() . ':'
                            . $request->getControllerName(), $request->getActionName())
            ) {

                //throw new Exception('',404);
                $request->setModuleName('admin');
                $request->setControllerName('index');
                $request->setActionName('index');
            }
        } catch (Exception $e) {
            $request->setModuleName('index');
            $request->setControllerName('index');
            $request->setActionName('index');
        }
    }

}