<?php

class SportalizeForm extends Zend_Form {
    
    public function init()
    {
        parent::init();
        $this->createElements();
        $this->redecorate();
        $this->setMethod(Zend_Form::METHOD_POST);
    }

    public function createElements() {

    }

    public function redecorate() {
        $this->clearDecorators()->setDecorators(array('FormElements', 'Form'));

    }
}