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
    
        $this->loc = Zend_Registry::isRegistered('loc') ? Zend_Registry::get('loc') : 'en_US';
        
        $this->setDisableLoadDefaultDecorators(true);
        $this->createElements();
        $this->redecorate();
        $this->setMethod(Zend_Form::METHOD_POST);

        parent::init();
    }

    public function createElements() {}

    public function redecorate() {
        $this->clearDecorators()->setDecorators(array('FormElements', 'Form'));
        $elements = $this->getElements();
        foreach ($elements as $oneElement) {
            $viewOnlyHelperElements = array('Zend_Form_Element_Hidden', 'Sportalize_Form_Element_PlainHtml', 'Zend_Form_Element_Text');
            if (in_array($oneElement->getType(), $viewOnlyHelperElements)) {
                $this->clearDecoratorsAndSetViewHelperOnly($oneElement);
            }
            if ($oneElement->getType() == 'Zend_Form_Element_Select') {
                $oneElement->setRegisterInArrayValidator(false);
            }
            if ($oneElement->getType() == 'Zend_Form_Element_File') {
                $this->clearDecoratorsAndSetDecorator($oneElement, $this->getDefaultFileDecorators());
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

    public function getDefaultModalDecorators($labelClass = '', $mainDivClass = '', $modalElementClass = '') {
        return array(
            'ViewHelper',
            'Description',
            array('Errors'),
            array('HtmlTag', array('tag'  => 'div', 'class' => 'main-div ' . $mainDivClass)),
            array('Label', array('class' => 'main-label ' . $labelClass)),
            array(array('All' => 'HtmlTag'), array('tag'    => 'div', 'class'   => 'modal-element ' . $modalElementClass)),
        );
    }

    public function getDefaultFileDecorators() {
        return array(
            'File',
            'Errors',
        );
    }

    public function redecorateFileUpload() {
        $toBeDecorated = array('Sportalize_Form_Element_FileUpload');
        $decorator     = $this->getDefaultModalDecorators();
        foreach ($this->getElements() as $key => $oneElement) {
            if (in_array($oneElement->getType(), $toBeDecorated)) {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
        }
    }
}