<?php

class Login_BaseController extends Sportalize_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
        $this->_helper->layout->setLayout('login');
    }

    public function redirectToSignUpPage() {
        $this->_redirect(APP_URL . '/login/index/sign-in');
    }
}