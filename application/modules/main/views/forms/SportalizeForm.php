<?php

class SportalizeForm extends Zend_Form {
    
    public $request;
    public $params;
    public $translate;

    public function init() {
        $this->request   = $request = Zend_Controller_Front::getInstance()->getRequest();
        $this->params    = $request->getParams();
        $this->translate = Zend_Registry::getInstance()->Zend_Translate;
        
        $this->createElements();
        $this->redecorate();
        $this->setMethod(Zend_Form::METHOD_POST);

        parent::init();
    }

    public function createElements() {

    }

    public function redecorate() {
        $this->clearDecorators()->setDecorators(array('FormElements', 'Form'));
        $elements = $this->getElements();
        foreach ($elements as $oneElement) {
            if ($oneElement->getType() == 'Zend_Form_Element_Hidden') {
                $oneElement->clearDecorators()->setDecorators(array('ViewHelper'));
            }
        }

    }
}