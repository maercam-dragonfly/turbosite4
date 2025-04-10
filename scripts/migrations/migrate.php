<?php
function migrate(){
/////////////////////


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

$migrationPath =  APPLICATION_PATH . '/../doctrine/migrations';

$migration = new Doctrine_Migration($migrationPath);
$latestVersion = $migration->getLatestVersion();
$currentVersion = $migration->getCurrentVersion();

if ($latestVersion > $currentVersion) {
    echo "Migrating from v{$currentVersion} to v{$latestVersion}\n";

    try {
        $nextVersion = $currentVersion + 1;
        $migration->migrate($nextVersion);
        $migration->setCurrentVersion($latestVersion);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage() . "\n";
    }
} else {
    echo "No migrations were performed\n";
}
}