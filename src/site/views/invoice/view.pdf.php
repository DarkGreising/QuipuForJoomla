<?php

defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'invoice' . DS . 'pdfinvoiceview.php';

/**
 * Resuse view logic and templates from admin.
 */
class QuipuViewInvoice extends PDFInvoiceView {
    
    /**
     * 
     * @param type $tpl
     */
    public function display($tpl = null) {        
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'invoice' . DS . 'tmpl');
        parent::display($tpl);
    }
}
