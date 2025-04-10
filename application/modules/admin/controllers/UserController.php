<?php

class admin_UserController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
        Zend_Layout::startMvc();
    }

    public function indexAction()
    {
        $this->view->title = 'Zarządzanie użytkownikami';
    }
}

