<?php
/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');
/**
 * Quipu Form Field class for the Quipu component
 */
class JFormFieldSequence extends JFormFieldText {

    /**
     * The field type.
     *
     * @var		string
     */
    protected $type = 'SEQUENCE';

    /**
     * 
     */
    protected function getInput() {
        $id = IWRequest::getInt('id');
        $this->familia = (string)$this->element['iw_familia'];
        $this->campo = (string)$this->element['iw_campo'];
        
        if(!$this->value){
            $this->value = IWSequences::nextValue($this->familia, $this->campo);
        }
        return parent::getInput();
    }

}


