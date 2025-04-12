<?php

class GalleryController extends My_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        //parent::init();
        //Zend_Layout::startMvc();
        //$this->_helper->layout()->setLayout('layout', 'default');
    }

    public function indexAction() {
        //$galleries = Application_Model_Gallery::getPublicGalleryList();
        //$this->view->title = "Galeria";
        //$this->view->galleries = $galleries;
    }

    public function categoryAction() {
        //$request = $this->getRequest();
        //$slug = $request->get('slug', '');
        //if ($slug == '') {
        //    $this->_redirect($this->view->url(array('action' => 'index')));
        //}
        //$gallery = Application_Model_Gallery::getGalleryBySlug($slug);
        //if($gallery == null){
        //    throw new Zend_Controller_Action_Exception('Strona nie istnieje', 404);
        //}
        //if(!$gallery->isPublic()){
        //    $this->_redirect($this->view->url(array('action' => 'index')));
        //}
        //$this->view->title = 'Galeria - ' . $gallery->getGalleryName();
        //$this->view->gallery = $gallery;
    }

}
