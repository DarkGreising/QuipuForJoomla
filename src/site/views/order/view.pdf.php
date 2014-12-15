<?php
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'order' . DS . 'pdforderview.php';

/**
 * Resuse view logic and templates from admin.
 */
class QuipuViewOrder extends PDFOrderView {
    
    /**
     * 
     * @param type $tpl
     */
    public function display($tpl = null) {        
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'views' . DS . 'order' . DS . 'tmpl');
        parent::display($tpl);
    }
}
