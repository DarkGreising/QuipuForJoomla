<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('IW_ERP_ESTADO_PEDIDO_FACTURADO',9001);
define('QUIPU_IS_J2',version_compare(JVERSION, '3.0.0', '<'));
define('QUIPU_IS_J3',version_compare(JVERSION, '3.0.0', '>='));

$_p = JPATH_COMPONENT_ADMINISTRATOR . DS . 'services' . DS;
JLoader::register('IWDate', $_p . 'IWDate.php');
JLoader::register('IWUtils', $_p . 'IWUtils.php');
JLoader::register('IWConfig', $_p . 'IWConfig.php');
JLoader::register('IWBrowser', $_p . 'IWBrowser.php');
JLoader::register('IWRequest', $_p . 'IWRequest.php');
JLoader::register('IWPDFService', $_p . 'IWPDFService.php');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root() . 'components/com_quipu/assets/css/quipu.css');

$language = JFactory::getLanguage();
//$language->load('com_quipu', JPATH_COMPONENT_ADMINISTRATOR . DS , $language->getTag(), true);
$language->load('com_quipu', JPATH_ADMINISTRATOR . DS , $language->getTag(), true);

$controller = JControllerLegacy::getInstance('Quipu');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
