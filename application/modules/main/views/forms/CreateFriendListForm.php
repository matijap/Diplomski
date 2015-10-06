<?php

class CreateFriendListForm extends Sportalize_Form_Base {

    public function init() {
        $this->setAction(APP_URL . '/index/friends');
        parent::init();
    }
    public function createElements() {
        parent::createElements();
        $this->addElement('text', 'title', array(
            'class'       => '',
            'placeholder' => $this->translate->_('New list title'),
            'class'       => 'create-fl-input',
            'required'    => true
        ));

        $this->addElement('submit', 'submit', array(
            'class' => 'blue-button create-fl-submit',
            'label' => $this->translate->_('Create')
        ));
    }
}