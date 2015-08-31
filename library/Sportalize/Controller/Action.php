<?php

class Sportalize_Controller_Action extends Zend_Controller_Action
{
    public function preDispatch() {
        $this->view->links = MenuLink::fetchMenuLinks();
    }
}