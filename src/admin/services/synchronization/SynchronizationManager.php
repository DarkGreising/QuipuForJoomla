<?php

defined('_JEXEC') or die('Restricted access');

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
class SynchronizationManager {

    const IMPORTER_CUSTOMERS = 'c';
    const IMPORTER_ORDERS = 'o';
    const IMPORTER_ITEMS = 'i';

    /**
     * data structure with compatible components.
     * @var type 
     */
    public static $COMPATIBLE_COMPONENTS = array(
        array('name'=>'VirtueMart', 'component' => 'com_virtuemart', 'min_version' => '2.0.16', 'importer' => 'IWVirtueMartImporter'),
    );
    private static $_instance = false;

    /**
     * 
     */
    private function __construct() {
        //$this->loadImporters();
    }

    /**
     * @return SynchronizationManager
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new SynchronizationManager();
        }
        return self::$_instance;
    }

    /**
     * Loads importer configurations for installed extensions, 
     * and stores list in session so that they will not
     * have to be re-read in every request.
     */
    private function loadImporters() {
        $json = JFactory::getSession()->get('importers', false, 'QuipuERP');
        if (!$json || !($importers = json_decode($json))) {
            $importers = array(
                self::IMPORTER_CUSTOMERS => array(),
                self::IMPORTER_ITEMS => array(),
                self::IMPORTER_ORDERS => array()
            );
            foreach(self::$COMPATIBLE_COMPONENTS as $meta){
                /** @todo */
            }
            JFactory::getSession()->set('importers', $importers, 'QuipuERP');
        }

        $this->importers = $importers;
    }

    /**
     * 
     */
    private function getImporterInstance($importer_metadata) {
        if (!is_object($importer_metadata->instance)) {
            JLoader::register($o->class_name, $o->file);
            $class_name = $importer_metadata->class_name;
            $importer_metadata->instance = $class_name::getInstance();
        }

        return $importer_metadata->instance;
    }

    /**
     * Searches what components among self::$COMPATIBLE_COMPONENTS are
     * currently installed. Also checks version numbers to be greater than
     * or equal to the min_version field.
     */
    public function getInstalledCompatibleComponents() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('e.element,e.manifest_cache');
        $query->from('#__extensions AS e');
        $db->setQuery($query);
        $options = array();
        foreach (self::$COMPATIBLE_COMPONENTS as $comp) {
            $options[] = $db->quote($comp['component']);
            //$version = $comp['min_version'];
        }
        $query->where('e.element in (' . implode(',', $options) . ')');
        $db->setQuery($query);
        $candidates = $db->loadAssocList('element', 'manifest_cache');
        $extensions = array();
        foreach (self::$COMPATIBLE_COMPONENTS as $comp) {
            $option = $comp['component'];
            $version = $comp['min_version'];
            $manifest = json_decode($candidates[$option]);
            if($manifest && $manifest->version && version_compare($manifest->version, $version, '>=')){
                $extensions[$option] = $comp;
            }
        }
        
        return $extensions;
    }

}
