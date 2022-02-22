<?php
/**
 * Migration cli class
 *
 * Creating SQLite DB File from Schema file or Creating Schema file from SQLite DB file
 *
 * @access public
 * @author LH & Creatives
 * @version 0.0.2
 */

class Migration extends CommandApplication
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __init()
    {
        parent::__init();
    }

    public function help()
    {
        $messages = array(
            'Fegg migration class',
            'fegg-cli migration                      : Setup Database from Schema file',
            'fegg-cli migration:db2schema [filepath] : Create schema file from DB Structure',
        );
        FEGG_print($messages);
    }

    public function index()
    {
        // Get Database Structure
        $database = $this->getClass('Migration/Util/Database');
        $databaseStr = $database->getStructure();

        // Get Yaml Structure
        $yaml = $this->getClass('Migration/Util/Yaml');
        $yaml->setSchemaDir(FEGG_CODE_DIR.'/config/database/');
        $yamlStr = $yaml->getStructure();

        $compare = $yamlStr->compare($databaseStr);
        if(! $compare->isChange()) {
            FEGG_print('Fegg-cli migration diff : Not changed');
            return;
        }

        $compare->execute();
    }

    public function db2schema($filePath)
    {
        if(! $filePath) {
            FEGG_commandError('migration:db2schema not found argments');
        }

        // Get Database Structure
        $database = $this->getClass('Migration/Util/Database');
        $database->createYaml($filePath);
    }

}