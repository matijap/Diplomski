<?php

class LoginForm extends SportalizeForm {
    
    const PASSWORD_LENGTH = 6;
    public $clientID      = false;

    public function redecorate() {
        parent::redecorate();

        $this->addElement('submit','submit', array(
            'label' => 'Submit'
        ));

        $decorator = array(
            'ViewHelper',
            array('Label', array('class' => '')),
            array('Errors', array('class' => '')),
            array(array('All' => 'HtmlTag'), array('tag'  => 'div', 'class' => 'holder')),
        );
        $possibleElements = array('email', 'password');
        foreach ($possibleElements as $key => $value) {
            $el = $this->getElement($value);
            if ($el) {
                $el->setDecorators($decorator);
            }
        }

        $decorator = array('ViewHelper');
        $this->getElement('submit')->setDecorators($decorator);
    }

    public function getLoginPasswordElement() {
        $password = $this->createElement('password', 'password', array(
            'label'    => 'Password',
            'pattern'  => '.{' . self::PASSWORD_LENGTH . ',}',
            'required' => true,
            'title'    => 'Minimum ' . self::PASSWORD_LENGTH . ' characters',
        ));
        $password->addValidator('StringLength', false, array('min' => self::PASSWORD_LENGTH, 'messages' => 'Password should be at least ' . self::PASSWORD_LENGTH . ' characters long'));

        return $password;
    }

    public function getLoginEmailElement($addNoRecordValidator = false) {
        $email = $this->createElement('text', 'email', array(
            'label'    => 'Email',
            'required' => true,
        ));
        $email->addValidator('EmailAddress', false);
        if ($addNoRecordValidator) {
            $email->addValidator('Db_NoRecordExists', true,
                array(
                    'table'     => 'user',
                    'field'     => 'email',
                    'exclude' => array(
                          'field' => 'id',
                          'value' => isset($this->clientID) ? $this->clientID : ''
                        ),
                    'messages' => "Client with existing email already exist"
                ));
        }
        return $email;
    }
}