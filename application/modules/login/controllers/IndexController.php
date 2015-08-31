<?php

class Login_IndexController extends Zend_Controller_Action
{
    public function preDispatch() {
        $this->_helper->layout->setLayout('login');
    }

    public function indexAction() {
        $this->_redirect(APP_URL . '/login/index/sign-up');
    }
    public function signUpAction() {
        $this->view->form = new SignInForm();
    }
    public function processAction() {

    }
}