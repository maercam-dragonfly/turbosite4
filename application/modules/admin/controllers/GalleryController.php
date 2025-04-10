<?php

class admin_GalleryController extends My_Controller_Action {

    public function init() {
        parent::init();
        Zend_Layout::startMvc();
        $this->view->gallery = array();
        $this->view->gallery['id'] = '';
        $this->view->gallery['name'] = '';
    }

    public function indexAction() {
        $this->view->title = 'Zarządzanie galeriami';
        $this->view->gallery = array();
        $this->view->gallery['add_url'] = $this->view->url(array('action' => 'add'));
        $this->view->gallery['delete_url'] = $this->view->url(array('action' => 'deletegallery'));
        $this->view->gallery['add_files'] = false;
        $this->view->gallery['add_description'] = 'Dodaj galerię';

        $galeries = Application_Model_Gallery::getGalleryList();
        $photos = array();
        foreach ($galeries as $gallery) {
            /* @var $gallery Application_Model_Gallery */
            if ($gallery->isDirNotExists()) {
                $gallery->delete();
                return;
            }

            $photo['mini'] = $gallery->isEmpty() ?
                    '/img/layout/partials/gallery/lack.jpg' : $gallery->getSampleMiniPhoto();
            $photo['content'] = $this->view->url(array('controller' => 'gallery', 'action' => 'edit', 'id' => $gallery->id));
            $photo['name'] = $gallery->getLabel();
            $photo['id'] = $gallery->id;
            $photos[] = $photo;
        }
        $this->view->gallery['photos'] = $photos;
    }

    public function addAction() {
        $this->view->title = 'Dodaj galerię';

        $this->view->gallery['add_url'] = './#';
        $this->view->gallery['delete_url'] = $this->view->url(array('action' => 'deletePhoto'));
        $this->view->gallery['add_files'] = true;
        $this->view->gallery['upload_url'] = $this->view->url(array('action' => 'smartupload'));
        $this->view->gallery['add_description'] = 'Dodaj zdjęcia';
        $this->view->gallery['add_label'] = 'Nazwa galerii';
        $this->view->gallery['submit_label'] = 'Zapisz';
        $this->view->gallery['delete_url'] = $this->view->url(array('action' => 'deletephoto'));
        $this->view->gallery['save_url'] = $this->view->url(array('action' => 'save'));
        $galleryNamespace = new Zend_Session_Namespace('Gallery');
        unset($galleryNamespace->galleryId);
    }

    public function editAction() {
        if ($this->_request->isGet()) {
            $filter = new Zend_Filter_StripTags();
            $id_gallery = $filter->filter($this->getRequest()->getParam('id'));
            $gallery = Application_Model_Gallery::getGalleryById($id_gallery);
            if ($gallery == null) {
                throw new Zend_Controller_Action_Exception('Strona nie istnieje', 404);
            }

            $this->addAction();
            $this->view->title = 'Edytuj galerię';
            $this->view->gallery['photos'] = $gallery->getPhotosList();
            $this->view->gallery['name'] = $gallery['name'];
            $this->view->gallery['id'] = $id_gallery;

            $galleryNamespace = new Zend_Session_Namespace('Gallery');
            $galleryNamespace->galleryId = $id_gallery;
        }
    }

    public function uploadAction() {


        $this->_helper->layout()->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
        if (!isset($_POST['id_gallery'])) {
            throw new Exception();
        }
        $id_gallery = $_POST['id_gallery'];

        $gallery = Application_Model_Gallery::getGalleryById($id_gallery);

        if ($gallery == null) {
            $gallery = Application_Model_Gallery::create($id_gallery);
            $gallery->save();
            $gallery->init();
        }

        Turbo_IO_Dir::makeDirectory($gallery->getPath(true));
        Turbo_IO_Dir::makeDirectory($gallery->getPhotoPath(true));
        Turbo_IO_Dir::makeDirectory($gallery->getMiniPath(true));
        if (!isset($_FILES['files']))
            throw new Exception();
        $files = $_FILES['files'];
        $size = count($files['name']);
        $photos = array();
        for ($i = 0; $i < $size; ++$i) {
            $basefileName = $files['name'][$i];
            $fileTmpName = $files['tmp_name'][$i];

            $fileName = Turbo_Utils::generateUniqueFileName($gallery->getPhotoPath(true), $basefileName);

            $filePath = $gallery->getPhotoPath(true) . $fileName;
            $miniFilePath = $gallery->getMiniPath(true) . $fileName;

            move_uploaded_file($fileTmpName, $filePath);

            Turbo_Gallery_Utils::gdThumbnailFile(
                    $filePath, 96, 96, $miniFilePath, 100
            );

            $photo = array();
            $photo['content'] = $gallery->getPhotoPath(false) . $fileName;
            $photo['mini'] = $gallery->getMiniPath(false) . $fileName;
            $photo['name'] = $fileName;

            $photos[] = $photo;
        }
        $this->view->photos = $photos;
    }

    public function deletephotoAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if (!isset($_POST['id_gallery']) || !isset($_POST['name'])) {
            throw new Exception();
        }
        $id_gallery = $_POST['id_gallery'];
        $name = $_POST['name'];

        $gallery = Application_Model_Gallery::getGalleryById($id_gallery);
        $photosPath = $gallery->getPhotoPath(true);
        $miniPath = $gallery->getMiniPath(true);

        unlink($photosPath . $name);
        unlink($miniPath . $name);
    }

    public function deletegalleryAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isPost()) {
            if (!isset($_POST['id_gallery']) || !isset($_POST['name'])) {
                throw new Exception();
            }
            $postData = $this->_request->getPost();
        }
        echo $postData['id_gallery'];

        $gallery = Application_Model_Gallery::getGalleryById($postData['id_gallery']);
        $gallery->delete();
    }

    public function saveAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        if ($this->_request->isPost()) {
            $postData = $this->_request->getPost();

            $galleryNamespace = new Zend_Session_Namespace('Gallery');
            if (!isset($galleryNamespace->galleryId)) {
                $galleryNamespace->galleryId = Turbo_Gallery_Utils::getUniqueId();
            }
            $galleryId = $galleryNamespace->galleryId;
            $gallery = Application_Model_Gallery::getGalleryById($galleryId);

            if ($gallery == null || $gallery->isEmpty()) {
                echo 'Dodaj zdjęcia do galerii';
                return;
            }

            if ($postData['name_gallery'] == '') {
                echo 'Podaj nazwę galerii';
                return;
            }
            $gallery->generateSamplePhoto();
            $gallery->name = $postData['name_gallery'];
            $gallery->id = $galleryId;
            Application_Model_Gallery::updateGallery($gallery);
            unset($galleryNamespace->galleryId);
            $url = $this->view->url(array('controller' => 'gallery', 'action' => 'edit', 'id' => $gallery->id));
            $this->_redirect($url);
        }
    }

    public function smartuploadAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $galleryNamespace = new Zend_Session_Namespace('Gallery');
        if (!isset($galleryNamespace->galleryId)) {
            $galleryNamespace->galleryId = Turbo_Gallery_Utils::getUniqueId();
        }
        $galleryId = $galleryNamespace->galleryId;
        $gallery = Application_Model_Gallery::getGalleryById($galleryId);

        if ($gallery == null) {
            $gallery = Application_Model_Gallery::create($galleryId);
            $gallery->save();
            $gallery->init();
        }
        Turbo_IO_Dir::makeDirectory($gallery->getPath(true));
        Turbo_IO_Dir::makeDirectory($gallery->getPhotoPath(true));
        Turbo_IO_Dir::makeDirectory($gallery->getMiniPath(true));
        /* @var Zend_File_Transfer_Adapter_Http $adapter */
        $adapter = new Zend_File_Transfer_Adapter_Http();
        $adapter->setDestination($gallery->getPhotoPath(true));
        $adapter->addValidator('Extension', false, 'jpg,png,gif');

        $datas = array();
        $files = $adapter->getFileInfo();

        $this->getUploadedFiles($datas, $gallery, $adapter);
        if (empty($files)) {
            $this->getExistingFiles($datas, $gallery, $adapter);
        }


        header('Pragma: no-cache');
        header('Cache-Control: private, no-cache');
        header('Content-Disposition: inline; filename="files.json"');
        header('X-Content-Type-Options: nosniff');
        header('Vary: Accept');
        echo json_encode(array('files' => $datas));
    }

    public function smartdeletephotoAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $name = $this->_request->getParam('name', '');
        if ($name == '') {
            echo json_encode(false);
            exit;
        }

        $galleryNamespace = new Zend_Session_Namespace('Gallery');
        if (!isset($galleryNamespace->galleryId)) {
            echo json_encode(false);
            exit;
        }

        $galleryId = $galleryNamespace->galleryId;

        $gallery = Application_Model_Gallery::getGalleryById($galleryId);
        $photosPath = $gallery->getPhotoPath(true);
        $miniPath = $gallery->getMiniPath(true);

        unlink($photosPath . $name);
        unlink($miniPath . $name);

        echo json_encode(true);
    }

    private function getUploadedFiles(array &$datas, $gallery, $adapter) {
        $files = $adapter->getFileInfo();
        foreach ($files as $file => $info) {
            $basefileName = $adapter->getFileName($file);
            $fileInfo = pathinfo($basefileName);
            $path = $fileInfo['dirname'];
            $fileName = $fileInfo['basename'];

            $fileName = Turbo_Utils::generateUniqueFileName($path, $fileName);

            $adapter->addFilter('rename', $fileName);
            // file uploaded & is valid
            if (!$adapter->isUploaded($file))
                continue;
            if (!$adapter->isValid($file))
                continue;

            // receive the files into the user directory
            $adapter->receive($file); // this has to be on top


            $filePath = $gallery->getPhotoPath(true) . $fileName;
            $miniFilePath = $gallery->getMiniPath(true) . $fileName;

            Turbo_Gallery_Utils::gdThumbnailFile(
                    $filePath, 96, 96, $miniFilePath, 100
            );


            $fileclass = new stdClass();

            // we stripped out the image thumbnail for our purpose, primarily for security reasons
            // you could add it back in here.
            $fileclass->name = $fileName;
            $fileclass->size = $adapter->getFileSize($file);
            $fileclass->type = $adapter->getMimeType($file);
            $fileclass->thumbnail_url = $gallery->getMiniPath(false) . $fileName;
            $fileclass->delete_url = $this->view->url(array('action' => 'smartdeletephoto', 'name' => $fileName));
            $fileclass->delete_type = 'GET';
            //$fileclass->error = 'null';
            $fileclass->url = $gallery->getPhotoPath(false) . $fileName;
            $datas[] = $fileclass;
        }
    }

    private function getExistingFiles(
    array &$datas, Application_Model_Gallery $gallery, Zend_File_Transfer_Adapter_Http $adapter) {
        $photoList = $gallery->getPhotosList();
        foreach ($photoList as $photo) {

            $fileclass = new stdClass();
            $fileclass->url = $photo['content'];
            $fileclass->thumbnail_url = $photo['mini'];
            $fileclass->name = $photo['name'];
            $fileclass->size = @filesize($fileclass->url);
            //   $fileclass->type = $adapter->getMimeType($gallery->getPhotoPath(true) . $photo['name']);
            $fileclass->delete_type = 'GET';
            $fileclass->delete_url = $this->view->url(array('action' => 'smartdeletephoto', 'name' => $fileclass->name));
            $datas[] = $fileclass;
        }
    }

}

