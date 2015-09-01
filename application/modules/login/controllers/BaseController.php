<?php

class Login_BaseController extends Sportalize_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
        $this->_helper->layout->setLayout('login');
    }
}