<?php

class SignInForm extends LoginForm {
    
    public function init() {
        parent::init();
        $this->setAction(APP_URL . '/login/index/sign-in');
    }

    public function createElements() {
        $this->addElement($this->getLoginEmailElement());
        $this->addElement($this->getLoginPasswordElement());
    }
}