<?php

class ForgotPasswordForm extends LoginForm {
    
    public function init() {
        parent::init();
        $this->setAction(APP_URL . '/login/index/forgot-password');
    }

    public function createElements() {
        parent::createElements();

        $this->addElement($this->getLoginEmailElement());
    }
}