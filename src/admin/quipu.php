<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

define('QUIPU_VERSION', '1.0.13');
define('QUIPU_IS_J2',version_compare(JVERSION, '3.0.0', '<'));
define('QUIPU_IS_J3',version_compare(JVERSION, '3.0.0', '>='));

define('IW_ERP_ESTADO_FACTURA_PENDIENTE', 1001);
define('IW_ERP_ESTADO_FACTURA_VENCIDA', 1002);
define('IW_ERP_ESTADO_FACTURA_COBRADA', 9001);
define('IW_ERP_ESTADO_FACTURA_REEMBOLSADA', 9002);
define('IW_ERP_ESTADO_FACTURA_REEMBOLSO', 9003);

define('IW_ERP_ESTADO_PEDIDO_FACTURADO', 9001);
define('IW_ERP_ESTADO_PEDIDO_CANCELADO', 9002);
define('IW_ERP_ESTADO_PEDIDO_PENDIENTE', 1001);
define('IW_ERP_ESTADO_PEDIDO_ENTREGADO', 1002);
define('IW_ERP_ESTADO_PEDIDO_REEMBOLSADO', 2001);

define('IW_ERP_ESTADO_PURCHASE_INVOICE', 9001);
define('IW_ERP_ESTADO_PURCHASE_CANCELLED', 9002);
define('IW_ERP_ESTADO_PURCHASE_PENDING', 1001);
define('IW_ERP_ESTADO_PURCHASE_DELIVERED', 1002);
define('IW_ERP_ESTADO_PURCHASE_REFUND', 2001);

JTable::addIncludePath(dirname(__FILE__) . DS . 'tables');
JLoader::register('QuipuHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'quipu.php');
JLoader::register('IWSequences', dirname(__FILE__) . DS . 'services' . DS . 'IWSequences.php');
JLoader::register('IWDate', dirname(__FILE__) . DS . 'services' . DS . 'IWDate.php');
JLoader::register('IWExcelImporter', dirname(__FILE__) . DS . 'services' . DS . 'IWExcelImporter.php');
JLoader::register('IWUtils', dirname(__FILE__) . DS . 'services' . DS . 'IWUtils.php');
JLoader::register('IWConfig', dirname(__FILE__) . DS . 'services' . DS . 'IWConfig.php');
JLoader::register('IWFile', dirname(__FILE__) . DS . 'services' . DS . 'IWFile.php');
JLoader::register('IWBrowser', dirname(__FILE__) . DS . 'services' . DS . 'IWBrowser.php');
JLoader::register('IWPDFService', dirname(__FILE__) . DS . 'services' . DS . 'IWPDFService.php');
JLoader::register('IWRequest', dirname(__FILE__) . DS . 'services' . DS . 'IWRequest.php');
JLoader::register('QuipuWidgets', dirname(__FILE__) . DS . 'services' . DS . 'QuipuWidgets.php');

//JLoader::register('SynchronizationManager', dirname(__FILE__) . DS . 'services' . DS . 'synchronization' . DS . 'SynchronizationManager.php');

JLoader::register('IWItemEditView',dirname(__FILE__) . DS . 'views' . DS . 'IWItemEditView.php');
JLoader::register('IWItemListView',dirname(__FILE__) . DS . 'views' . DS . 'IWItemListView.php');

// import joomla controller library
jimport('joomla.application.component.controller');

//1 - If no config is found, redirect the user to create one
$config = IWConfig::getInstance();
$controller = JControllerLegacy::getInstance('Quipu');
$vName = JFactory::getApplication()->input->get('view');
if (!isset($config) && !$controller->isConfig && $vName != 'config') {
    JFactory::getApplication()->enqueueMessage(JText::_('COM_QUIPU_NO_CONFIG'));
    JFActory::getApplication()->redirect(JRoute::_('index.php?option=com_quipu&task=config.edit', false));
}

// Perform the Request task
$controller->execute(IWRequest::getCmd('task'));

$doc = JFactory::getDocument();
//javascript i18n:
$js[] = 'window.iw.IS_J3=' . (int)QUIPU_IS_J3 . ';';
$js[] = 'window.iw.i18n.pleasewait = "' . JText::_('COM_QUIPU_PLEASEWAIT') . '";';
$js[] = 'window.iw.i18n.unsavedchanges = "' . JText::_('COM_QUIPU_UNSAVEDCHANGES') . '";';
$js[] = "window.iw.i18n.poweredby = \"Powered by <strong>Quipu ERP v" . QUIPU_VERSION . "</strong> - <a onclick='window.open(this.href);return false;' href='https://github.com/NachoBrito/QuipuForJoomla?utm_source=ExpertosJoomla&utm_medium=QuipuAdminEmbedded&utm_campaign=Quipu1' title='Quipu, the ERP for Joomla'>https://github.com/NachoBrito/QuipuForJoomla/quipu-the-erp-for-joomla.html</a>\";";
$doc->addScriptDeclaration(implode('', $js));

if (QUIPU_IS_J3) {   
    JHtml::_('bootstrap.framework');
    JHtml::_('behavior.framework','more');    
    $doc->addStyleSheet(JURI::root() . 'administrator/components/com_quipu/assets/css/flexigrid.pack.css');
    $doc->addStyleSheet(JURI::root() . 'administrator/components/com_quipu/assets/css/quipu_j3.css');
    $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/dyntable_j3.js');
    $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/iw_behavior_j3.js');
    $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/flexigrid.pack.js');
}
else{
    JHtml::_('behavior.mootools');    
    $doc->addStyleSheet(JURI::root() . 'administrator/components/com_quipu/assets/css/omnigrid.css');    
    $doc->addStyleSheet(JURI::root() . 'administrator/components/com_quipu/assets/css/quipu.css');   
    $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/dyntable-u.js');        
    $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/iw_behavior.js');
    $doc->addScript(JURI::root() . 'administrator/components/com_quipu/assets/js/omnigrid.js');
}



// Redirect if set by the controller
$controller->redirect();
