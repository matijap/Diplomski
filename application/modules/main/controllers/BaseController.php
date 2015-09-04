<?php

class Main_BaseController extends Sportalize_Controller_Action
{
    public function preDispatch() {
        parent::preDispatch();
        if(!Zend_Auth::getInstance()->hasIdentity()) {
            if ($this->request->isXmlHttpRequest()) {
               $status = array('url' => APP_URL . '/login/index/sign-in', 'status' => 'redirect');
               $this->_helper->json($status);
               $this->_helper->layout()->disableLayout();
            } else {
                return $this->_redirect('/login/index/sign-in');
            }
        }
        $info               = Zend_Auth::getInstance()->getIdentity();
        $this->user         = Main::fetchRow("User", Main::select("User")->where("email = ?", $info->email));
        $this->view->userID = $this->user->id;
        $this->_helper->layout->setLayout('main');
        $this->view->links = MenuLink::fetchMenuLinks();
        if ($this->request->isXmlHttpRequest()) {
            $this->isXHR = TRUE;
            $this->_helper->layout()->disableLayout();
        }
    }
}