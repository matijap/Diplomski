<?php
class BigLogoChangeForm extends Sportalize_Form_Base {

    public function init() {
        parent::init();
        $this->setAction(APP_URL . '/index/change-big-logo');
    }

    public function createElements() {
        parent::createElements();

        $this->addElement('file', 'big_logo', array(
            'class' => 'display-none'
        ));
        $this->addElement('hidden', 'user_id', array(
            'value' => $this->user->id
        ));
    }
}