<?php

class Main_BaseController extends Sportalize_Controller_Action
{
    public function preDispatch() {
        $this->_helper->layout->setLayout('main');
        $this->view->links = MenuLink::fetchMenuLinks();
    }

    public function indexAction() {
        
    }
}