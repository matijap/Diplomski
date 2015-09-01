<?php

class Sportalize_Controller_Action extends Zend_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
        $this->request = $this->getRequest();
        $this->params  = $this->request->getParams();
        $this->_helper->layout->setLayout('layout');
    }

    public function validateForm($form) {
        $response = array('isValid' => false, 'isPost' => false);
        if($this->request->isPost()) {
            $response['isPost']  = true;
            $response['isValid'] = $form->isValid($this->params);
        }
        return $response;
    }

}