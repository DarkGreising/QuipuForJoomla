<?php

/**
 * @copyright	Nacho Brito
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');

/**
 * Quipu Form Field class for the Quipu component
 */
abstract class IWActionsList extends JFormField {

    protected abstract function getActions();

    /**
     * 
     */
    protected function getInput() {
        $option = IWRequest::getCmd('option');
        $id = $this->form->getValue('id');

        $html = array();
        if ($id) {
            $acciones = $this->getActions();
            $html[] = '<h4>' . JText::_('COM_QUIPU_AVAILABLE_ACTIONS') . '</h4>';
            if (count($acciones)) {
                $html[] = '<ul class="quipu-actions">';
                foreach ($acciones as $accion) {
                    $extra = '';
                    if (QUIPU_IS_J3) {
                        $accion->css = $this->buildCSSForJ3($accion->css);
                    }
                    if (isset($accion->ajax)) {
                        $domID = rand();
                        $extra = isset($accion->confirm) ? (' data-confirm="' . $accion->confirm . '"') : '';
                        $extra .= 'data-loading-text="' . JText::_('COM_QUIPU_PLEASEWAIT') . '" autocomplete="off"';
                        if (isset($accion->postReload)) {
                            $extra .= ' data-postreload="1"';
                        }
                        $html[] = '<li><a id="' . $domID . '" class="ajx ' . $accion->css . '" href="' . $accion->url . '" data-method="' . (isset($accion->ajax_method) ? $accion->ajax_method : 'GET') . '" ' . $extra . '>' . $accion->texto . '</a>';
                        $html[] = '</li>';
                    } else {
                        if (isset($accion->target)) {
                            if ($accion->target == 'modal') {
                                $accion->css .= ' modal';
                                $target = ' rel="{size: {x: 700, y: 500}, handler:' . "'iframe'" . '}"';
                            } else {
                                $target = 'target="' . $accion->target . '"';
                            }
                        } elseif (QUIPU_IS_J3) {
                            $target = '';
                            $extra = 'data-loading-text="' . JText::_('COM_QUIPU_PLEASEWAIT') . '" autocomplete="off"';
                        } else {
                            $target = '';
                            $accion->css .= ' with-wait-msg';
                        }
                        $html[] = '<li><a class="' . $accion->css . '" href="' . $accion->url . '" ' . $target . $extra . '>' . $accion->texto . '</a></li>';
                    }
                }
            } else {
                $html[] = JText::_('COM_QUIPU_NO_ACTIONS');
            }
            $html[] = '</ul>';
        } else {
            $html[] = JText::_('COM_QUIPU_NO_ACTIONS');
        }
        return implode(' ', $html);
    }

    /**
     * 
     * @param type $css
     */
    private function buildCSSForJ3($css) {
        $f = array('quipu-cancel', 'quipu-refund', 'quipu-payment');
        $r = array('btn-danger', 'btn-warning', 'btn-success');
        $css = 'btn ' . str_replace($f, $r, $css);

        return $css;
    }

}
