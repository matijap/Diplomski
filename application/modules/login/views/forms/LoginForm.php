<?php

class LoginForm extends SportalizeForm {
    

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
        $this->getElement('email')->setDecorators($decorator);
        $this->getElement('password')->setDecorators($decorator);

        $decorator = array('ViewHelper');
        $this->getElement('submit')->setDecorators($decorator);
    }
}