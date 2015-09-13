<?php

class Sportalize_Form_Base extends Zend_Form {
    
    public $request;
    public $params;
    public $translate;
    public $user;
    public $loc;

   public function __construct($data = array()) {
        $this->translate = Zend_Registry::getInstance()->Zend_Translate;
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        parent::__construct();
    }

    public function init() {
        $this->request   = $request = Zend_Controller_Front::getInstance()->getRequest();
        $this->params    = $request->getParams();
        
        if (Zend_Registry::isRegistered('logged_user')) {
            $this->user = Zend_Registry::get('logged_user');
        }

        $this->loc = Zend_Registry::get('loc');
        
        $this->setDisableLoadDefaultDecorators(true);
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
                $this->clearDecoratorsAndSetViewHelperOnly($oneElement);
            }
            if ($oneElement->getType() == 'Zend_Form_Element_Select') {
                $oneElement->setRegisterInArrayValidator(false);
            }
        }
    }

    public function getViewHelperDecorator() {
        return array('ViewHelper');
    }

    public function clearDecoratorsAndSetViewHelperOnly($element) {
        $element = $this->clearDecoratorsAndSetDecorator($element, $this->getViewHelperDecorator());
        return $element;
    }

    public function clearDecoratorsAndSetDecorator($element, $decorators) {
        $element = $element->clearDecorators()->setDecorators($decorators);
        return $element;
    }

    public function addUserIDElement() {
        $this->addElement('hidden', 'userID', array('value' => $this->user->id));
    }
}