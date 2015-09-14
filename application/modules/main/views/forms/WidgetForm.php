<?php

class WidgetForm extends Sportalize_Form_Modal {

    public function init() {
        $this->setAction(APP_URL . '/widget/new-widget');
        $this->setModalTitle($this->translate->_('Create New Widget'));
        parent::init();
    }

    public function createElements() {
        $this->addElement('text', 'a', array('value' => 'works'));
        parent::createElements();
    }
}