<?php

class admin_ContentController extends My_Controller_Action {

    public function init() {
        parent::init();
        Zend_Layout::startMvc();
    }

    public function indexAction() {
        $this->view->title = 'Zarządzanie treścią';
        $cms = Doctrine_Query::create()
                ->from('Application_Model_CMS c')
                ->where('c.type = ?', 'karteczki')
                ->fetchArray();

        $this->view->kateczkiFormArray = array();

        foreach ($cms as $value) {
            $form = new Form_Karteczka(array('class' => $value['name']));
            $this->view->kateczkiFormArray[$value['name']] = $form;
            $postData = array();
            $postData['title'] = $value['title'];
            $postData['content'] = $value['content'];
            $form->populate($postData);
        }
        if ($this->_request->isPost()) {
            
            $postData = $this->_request->getPost();
            Zend_Debug::dump($postData);
            if (isset($postData['ident'])) {
                
                $form = $this->view->kateczkiFormArray[$postData['ident']];

                if ($form->isValid($postData)) {
                    $formData = $form->getValues();
                    
                    echo Doctrine_Query::create()
                            ->update('Application_Model_CMS c')
                            ->set('c.title',"'".$formData['title']."'")
                            ->set('c.content',"'".$formData['content']."'")
                            ->where('c.name = ?',$postData['ident'] )
                            ->execute();
                    //echo 'lala';
                    $this->_redirect($this->view->url());
                }
            }
        }
    }

}

