<?php

class admin_ZapisyController extends My_Controller_Action {

    public function init() {
        parent::init();
        Zend_Layout::startMvc();
    }

    public function indexAction() {
        $this->view->title = 'Zarządzanie kursantami';
        $this->view->students = Doctrine_Query::create()
                ->from('Application_Model_Student s')
                ->orderBy('s.created DESC')
                ->execute();
    }

    public function addAction() {
        $this->view->title = 'Dodaj kursanta';
        $form = new Form_Zapisy();
        $rodzaj_kursu = $form->getElement('rodzaj_kursu');
        $rodzaj_kursu->addMultiOptions(My_CourseTypes::getAllCourseTypes());
        $postData = array();

        if ($this->_request->isPost()) {
            $postData = $this->_request->getPost();
            if ($form->isValid($postData)) {
                // pobranie danych wyslanych z formularza
                $formData = $form->getValues();
                $students = Doctrine_Query::create()
                        ->from('Application_Model_Student s')
                        ->where('s.email = ?', $formData['email'])
                        ->execute();
                if ($students->count() > 0) {
                    $this->view->komunikat = 'Osoba o podanym e-mail już istnieje';
                } else {
                    $data = $formData;
                    $data['date'] = new Doctrine_Expression('NOW()');
                    $data['registered'] = new Doctrine_Expression('FALSE');
                    $student = new Application_Model_Student();
                    $student->add($data);
                    $this->_helper->redirector->gotoRoute(
                            array(
                                'action' => '',
                                'id' => '')
                    );
                }
            }
        } else {
            
        }

        $this->view->form = $form;
    }

    public function editAction() {
        $this->view->title = 'Edytuj kursanta';
        $filter = new Zend_Filter_StripTags();
        $id = $filter->filter($this->getRequest()->getParam('id'));
        $students = Doctrine_Query::create()
                ->from('Application_Model_Student s')
                ->where('s.id = ?', $id)
                ->execute();
        $student = $students[0];
        $form = new Form_Zapisy();
        $rodzaj_kursu = $form->getElement('rodzaj_kursu');
        $rodzaj_kursu->addMultiOptions(My_CourseTypes::getAllCourseTypes());
        $rodzaj_kursu->setValue($student->rodzaj_kursu);
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
                    ->delete('Application_Model_Student s')
                    ->where('s.id = ?',$id)
                    ->execute();

                $data['created'] = $student['created'];
                $editStudent = new Application_Model_Student;
                $editStudent->add($data);
                $this->_helper->redirector->gotoRoute(
                        array(
                            'action' => '',
                            'id' => '')
                );
            }
        }
        // uzupelnij formularz domyslnymi danymi
        else {
            $postData = $student->toDto();
        }

        // uzupelnienie formularza danymi
        $form->populate($postData);
        $this->view->form = $form;
    }

    public function deleteAction() {
        $this->view->title = 'Usuń kursanta';
        $filter = new Zend_Filter_StripTags();
        $id = $filter->filter($this->getRequest()->getParam('id'));

        $students = Doctrine_Query::create()
                ->from('Application_Model_Student s')
                ->where('s.id = ?', $id)
                ->execute();
        $student = $students[0];

        $this->view->student = $student;
        
        $q = Doctrine_Query::create()
                ->delete('Application_Model_Student s')
                ->where('s.id = ?', $id)
                ->execute();
        
        $this->_helper->redirector->gotoRoute(
                array(
                    'action' => '',
                    'id' => '')
        );
    }

}

