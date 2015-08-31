<?php

class SportalizeForm extends Zend_Form {
    public function init()
    {
        parent::init();
        $this->createElements();
        $this->redecorate();
    }

    public function createElements() {

    }

    public function redecorate() {
        $this->clearDecorators()->setDecorators(array('FormElements', 'Form'));

    }
}