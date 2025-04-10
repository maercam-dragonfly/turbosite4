<?php

include_once APPLICATION_PATH . '/../scripts/migrations/migrate.php';
include_once APPLICATION_PATH . '/../scripts/migrations/initialize.php';
include_once APPLICATION_PATH . '/../scripts/migrations/reset.php';
include_once APPLICATION_PATH . '/../scripts/migrations/MigrationUtil.php';

class admin_MigrationController extends My_Controller_Action {

    /**
     * @var MigrationUtil 
     */
    private $_migratons;

    public function init() {
        parent::init();
        Zend_Layout::startMvc();
    }

    public function preDispatch() {
        parent::preDispatch();
        $this->view->title = 'Migracje bazy danych';
        $this->_migratons = MigrationUtil::getInstance();
    }

    public function indexAction() {
        $this->view->schemas = $this->_migratons->verisonListVersions();
    }

    public function initializemigrationAction() {
        $this->_helper->viewRenderer->setNoRender();
        echo 'initialize<br/>';

        if ($this->getRequest()->isPost()) {

            if (!isset($_POST["newSchema"])) {
                echo 'Nie wybrales schematu do migracji';
                return;
            }
            $oldSchema = $_POST["oldSchema"];
            $newSchema = $_POST["newSchema"];

            if ($oldSchema == $newSchema) {
                echo "taka sama wersja schematu";
                return;
            }
            $this->_migratons->initializeMigration($oldSchema, $newSchema);
        }
    }

    public function migrateAction() {
        echo 'migrate';
        $this->_migratons->migrate();
        $this->_helper->viewRenderer->setNoRender();
    }
}

