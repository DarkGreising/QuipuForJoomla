<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Quipu View
 */
class QuipuViewOrders extends JViewLegacy {

    /**
     * vista HTML 
     *
     * @param $tpl
     */
    public function display($tpl = null) {
        $ids = IWRequest::getString('id');
        if ($ids) {
            $ids = explode(',', $ids);
            jimport('joomla.utilities.arrayhelper');
            JArrayHelper::toInteger($ids);
            $this->items = $this->getModel()->getOrders($ids);
        } else {
            $this->items = $this->get('Items');
        }

        $this->config = IWConfig::getInstance();

        $file = $this->generate();
        $filename = 'Quipu-' . time() . '.pdf';
        IWUtils::downloadFile($file, $filename, 'application/pdf', true, true, true);
        unlink($file);
    }

    /**
     * 
     */
    public function generate($destFileName = false) {
        if (!$destFileName) {
            $destFileName = 'QUIPU_' . time() . '_' . rand() . '.pdf';
        }
        $model = JModelLegacy::getInstance('Order', 'QuipuModel');
        //$config = JFactory::getConfig();
        //$destFile = $config->getValue('config.tmp_path') . DS . $destFileName;
        $destFile = IWConfig::getJConfigValue('tmp_path') . DS . $destFileName;
        $title = JText::sprintf('COM_QUIPU');

        $pdf = IWPDFService::getInstance();
        $pdf->newDocument($destFile, $title,'COM_QUIPU_PDF_ORDER');

        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'order' . DS . 'tmpl');
        foreach ($this->items as $item) {
            $this->item = $item;
            $this->details = $model->getDetail($item->id);
            $html = $this->loadTemplate('order');
            $pdf->addHTMLPage($html);
        }


        $pdf->closeDocument();

        return $destFile;
    }

}
