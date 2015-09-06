<?php

class ErrorController extends Zend_Controller_Action
{
    public function errorAction()
    {   
        $errors             = $this->_getParam('error_handler');
        $request            = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            // ajax error
        } else {
            $this->view->translate  = $translate = Zend_Registry::getInstance()->Zend_Translate;
            $this->view->module     = 'core';
            $this->view->controller = 'error';
            $this->view->action     = 'error';
            $this->view->showLogin  = true;
            switch ($errors->type) {
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:                
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:                
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                    $this->getResponse()->setHttpResponseCode(404);
                    $this->view->code          = 404;
                    $this->view->messageStatus = $translate->_('No content here');
                    $this->view->messageText   = $translate->_('Server reported that the requested page could not be found. Please check your URL. In the meantime we will notify developers.');
                    break;
                default :
                    $this->getResponse()->setHttpResponseCode(500);
                    $this->view->code          = 500;
                    $this->view->messageStatus = $translate->_("Don't panic");
                    //$this->view->messageText   = $translate->_('A server error occurred. Please try again in a few minutes. In the meantime we will notify developers.');
                    $this->view->messageText  = $errors->exception->getMessage();
                    $message = $errors->exception->getMessage();
                    fb($message);
                    @list($type, $msg) = explode(":::", $message);
                    if ($type == "warn") {
                        $this->view->warn                 = true;
                        $this->view->warnMessage          = $msg;
                        $zendSession                      = new Zend_Session_Namespace(ErrorControll::SESSION_NOTIFICATION_MSG);
                        $zendSession->notificationMessage = $msg;
                    }
                    break;
            }
        }
    }
}