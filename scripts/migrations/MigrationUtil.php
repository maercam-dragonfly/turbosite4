<?php

class MigrationUtil {

    private $_doctrine = null;

    public static function getInstance() {
        return new MigrationUtil();
    }

    private function __construct() {
        
    }
    
    private function init() {
        echo '<br />';
        echo APPLICATION_ENV;
          echo '<br />';

        defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../application'));

        set_include_path(implode(PATH_SEPARATOR, array(
                    realpath(APPLICATION_PATH . '/../library'),
                    get_include_path(),
                )));

        require_once 'Zend/Application.php';

        $application = new Zend_Application(
                        APPLICATION_ENV,
                        APPLICATION_PATH . '/configs/application.ini'
        );

        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->pushAutoloader(array('Doctrine_Core', 'autoload'));

        $application->getBootstrap()->bootstrap('doctrine');

        $this->_doctrine = $application->getBootstrap()->getOption('doctrine');
    }

    public function verisonListVersions() {
        $this->init();
        $yamlSchemaPath = $this->_doctrine['yaml_schema_path'] . '/';
        $currSchemaDir = new DirectoryIterator($yamlSchemaPath);
        $currSchema = array();
        foreach ($currSchemaDir as $schema) {
            if ($schema->isDot())
                continue;
            if (!$schema->isDir()) {
                $tmpSchema['name'] = (string) $schema;
                $tmpSchema['version'] = self::getVersionFromFileName($tmpSchema['name']);
                $currSchema = $tmpSchema;
            }
        }
        $allSchemas = array();
        $yamlSchemaOldPath = $this->_doctrine['yaml_schema_all_path'] . '/';
        $currSchemaOldDir = new DirectoryIterator($yamlSchemaOldPath);
        foreach ($currSchemaOldDir as $schema) {
            if ($schema->isDot())
                continue;
            if (!$schema->isDir()) {
                $tmpSchema['name'] = (string) $schema;
                $tmpSchema['version'] = self::getVersionFromFileName($tmpSchema['name']);
                $allSchemas[] = $tmpSchema;
            }
        }

        $migrationPath = APPLICATION_PATH . '/../doctrine/migrations';

        $migration = new Doctrine_Migration($migrationPath);
        $latestVersion = $migration->getLatestVersion();
         $currentVersion = $migration->getCurrentVersion();
        echo 'Ostania wesja bazy: ' . $latestVersion;
        echo '<br />';
        echo 'Wdrożona wersja bazy: ' . $currentVersion;
        return array(
            'currSchema' => $currSchema,
            'allSchemas' => $allSchemas
        );
    }

    public function initializeMigration($oldShemaFile, $newShemaFile) {
        $this->init();
        $yamlSchemaAllPath = $this->_doctrine['yaml_schema_all_path'] . '/';
        $newShemaFile = $yamlSchemaAllPath . $newShemaFile;
        $oldShemaFile = $yamlSchemaAllPath . $oldShemaFile;

        Doctrine_Core::generateMigrationsFromDiff($this->_doctrine['migrations_path'], $oldShemaFile, $newShemaFile);
    
        echo 'Gotowe';
    }

    public function migrate() {
                $this->init();
        $migrationPath = APPLICATION_PATH . '/../doctrine/migrations';
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

    private static function getVersionFromFileName($name) {
        $nameArray = explode('.', $name);
        $versionArraty = explode('-', $nameArray[0]);
        $version = $versionArraty[1];
        return $version;
    }

}
