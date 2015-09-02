<?php

class Main_BaseController extends Sportalize_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
        if(!Zend_Auth::getInstance()->hasIdentity()) {
            if ($this->getRequest()->isXmlHttpRequest()) {
               $status = array('url' => APP_URL . '/login/index/sign-up', 'status' => 'redirect');
               $this->_helper->json($status);
               $this->_helper->layout()->disableLayout();
            } else {
                return $this->_redirect('/login/index/sign-up');
            }
        }

        $this->_helper->layout->setLayout('main');
        $this->view->links = MenuLink::fetchMenuLinks();
        
    }

    public function indexAction() {
        
    }
}