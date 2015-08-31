<?php

class SignInForm extends LoginForm {
    
    public function init() {
        parent::init();

        $this->setAction(APP_URL . '/login/index/process');
    }

    public function createElements() {
        $el = $this->createElement('text', 'email', array(
            'label'    => 'Email',
            'required' => true,
        ));
        $el->setRequired(true);
        $this->addElement($el);

        $this->addElement('password', 'password', array(
            'label'    => 'Password',
            'pattern'  => '.{6,}',
            'required' => true,
            'title'    => 'Minimum 6 characters',
        ));
    }

    public function isValid($data) {
        $ok = parent::isValid($data);
        fb($ok, 'ok');
        return $ok;
    }
}