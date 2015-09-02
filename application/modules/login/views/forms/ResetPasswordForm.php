<?php

class ResetPasswordForm extends LoginForm {
    
    public function init() {
        parent::init();
        $this->setAction(APP_URL . '/login/index/reset-password');
    }

    public function createElements() {
        parent::createElements();
        
        $this->addElement($this->getLoginPasswordElement());
        $this->addElement('hidden', 'token', array('value' => $this->params['token']));
    }
}