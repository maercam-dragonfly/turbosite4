<?php
function initializeMigration(){
    
//define('APPLICATION_ENV', 'development');
    echo '<br />';
echo APPLICATION_ENV;

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$loader = Zend_Loader_Autoloader::getInstance();
$loader->pushAutoloader(array('Doctrine_Core', 'autoload'));

$application->getBootstrap()->bootstrap('doctrine');


$doctrine = $application->getBootstrap()->getOption('doctrine');

$yamlSchemaPath = $doctrine['yaml_schema_path'] . '/';
$newShemaFile = $yamlSchemaPath . 'schema_new.yml';
$oldShemaFile = $yamlSchemaPath . 'schema.yml';

Doctrine_Core::generateMigrationsFromDiff($doctrine['migrations_path'], $oldShemaFile, $newShemaFile);

}
