<?php

class admin_KonkursController extends My_Controller_Action {

    public function init() {
        parent::init();
        Zend_Layout::startMvc();
    }

    public function indexAction() {
        $this->view->title = 'Zarządzanie konkursem';
        $this->view->quizUsers = Doctrine_Query::create()
                ->from('Application_Model_QuizUser u')
                ->orderBy('u.nazwisko DESC')
                ->execute();
    }

    public function addAction() {
        $this->view->title = 'Dodaj osobę na konkurs';
        $form = new Form_Zapisy();

        $postData = array();

//    // czy formularz zostal wyslany
        if ($this->_request->isPost()) {
//        // pobranie danych post
            $postData = $this->_request->getPost();
// 
            // walidacja danych post
            if ($form->isValid($postData)) {
                // pobranie danych wyslanych z formularza
                $formData = $form->getValues();
                $quizUsers = Doctrine_Query::create()
                        ->from('Application_Model_QuizUser u')
                        ->where('u.email = ?', $formData['email'])
                        ->execute();
                if ($quizUsers->count() > 0) {
                    $this->view->komunikat = 'Osoba o podanym e-mail już istnieje';
                } else {
                    $data = $formData;
                    $data['date'] = new Doctrine_Expression('NOW()');
                    $data['registered'] = new Doctrine_Expression('FALSE');
                    $quizUser = new Application_Model_QuizUser();
                    $quizUser->add($data);
                    $this->_helper->redirector->gotoRoute(
                            array(
                                'action' => '',
                                'id' => '')
                    );
                }
            }
        }
        // uzupelnij formularz domyslnymi danymi
        else {
//        // jest to tylko przyklad. nie jest wymagane uzupelnianie
//        // formularza domyslnymi wartosciami
//        //$postData['firstname'] = 'Jan';
        }
// 
//    // uzupelnienie formularza danymi
//    $form->populate($postData);
// 
        $this->view->form = $form;
//    
    }

    public function editAction() {
        $this->view->title = 'Edytuj osobę na konkurs';
        $filter = new Zend_Filter_StripTags();
        $id = $filter->filter($this->getRequest()->getParam('id'));
        
        $quizUsers = Doctrine_Query::create()
                ->from('Application_Model_QuizUser u')
                ->where('u.id = ?', $id)
                ->execute();
        
        $quizUser = $quizUsers[0];
        $form = new Form_Zapisy();

        $postData = array();

        // czy formularz zostal wyslany
        if ($this->_request->isPost()) {
            // pobranie danych post
            $postData = $this->_request->getPost();

            // walidacja danych post
            if ($form->isValid($postData)) {
                // pobranie danych wyslanych z formularza
                $formData = $form->getValues();
                $data = $formData;
                $q = Doctrine_Query::create()
                        ->delete('Application_Model_QuizUser u')
                        ->where('u.id = ?', $id)
                        ->execute();

                $data['date'] = $quizUser['created'];
                $editQuizUser = new Application_Model_QuizUser;
                $editQuizUser->add($data);
                $this->_helper->redirector->gotoRoute(
                        array(
                            'action' => '',
                            'id' => '')
                );
            }
        }
        // uzupelnij formularz domyslnymi danymi
        else {

            $postData['imie'] = $quizUser->imie;
            $postData['nazwisko'] = $quizUser->nazwisko;
            $postData['email'] = $quizUser->email;
            $postData['telefon'] = $quizUser->telefon;
            $postData['registered'] = $quizUser->registered;
        }

        // uzupelnienie formularza danymi
        $form->populate($postData);
        $this->view->form = $form;
    }

    public function deleteAction() {
        $this->view->title = 'Usuń osobę na konkurs';
        $filter = new Zend_Filter_StripTags();
        $id = $filter->filter($this->getRequest()->getParam('id'));

        $students = Doctrine_Query::create()
                ->from('Application_Model_QuizUser u')
                ->where('u.id = ?', $id)
                ->execute();
        $student = $students[0];

        $this->view->student = $student;


        $q = Doctrine_Query::create()
                ->delete('Application_Model_QuizUser u')
                ->where('u.id = ?', $id)
                ->execute();



        $this->view->form = $form;
        $this->_helper->redirector->gotoRoute(
                array(
                    'action' => '',
                    'id' => '')
        );
    }

}

