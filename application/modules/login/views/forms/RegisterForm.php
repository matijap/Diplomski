<?php

class RegisterForm extends LoginForm {
    
    public function init() {
        parent::init();
        $this->setAction(APP_URL . '/login/index/register');
    }

    public function createElements() {
        parent::createElements();

        $this->addElement($this->getLoginEmailElement());
        $this->addElement($this->getLoginPasswordElement());
    }
}