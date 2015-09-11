<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_Xhtml extends Zend_Form_Element_Xhtml
{
    public $translate;
    public $params;
    public $return;

    public function init() {
        $this->translate = Zend_Registry::getInstance()->Zend_Translate;
        $request         = Zend_Controller_Front::getInstance()->getRequest();
        $this->params    = $request->getParams();
        $this->_messages = array();
        $this->return    = true;
        parent::init();
    }
}