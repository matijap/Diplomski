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
        $info                           = Zend_Auth::getInstance()->getIdentity();
        $this->user = $this->view->user = Main::fetchRow("User", Main::select("User")->where("email = ?", $info->email));
        $this->view->userID             = $this->user->id;
        $this->_helper->layout->setLayout('main');
        Zend_Registry::set('logged_user', $this->user);
        
        $this->view->links = MenuLink::fetchMenuLinks();
        if ($this->request->isXmlHttpRequest()) {
            $this->isXHR = TRUE;
            $this->_helper->layout()->disableLayout();
        }
        $this->view->friends = $this->user->getFriendList();
        $loc = $this->setLocParam();
        $this->setDatePickerFormat($loc);

        $language  = $this->user->getLanguage();
        $translate = new Zend_Translate('gettext', APPLICATION_PATH . '/languages/' . $language->file . '.mo', $loc, array('disableNotices' => true));
        $translate->setLocale($loc);
        Zend_Registry::set('Zend_Translate', $translate);
    }

    public function setNotificationMessage($message, $status = Sportalize_Controller_Action::NOTIFICATION_SUCCESS) {
        $session                      = new Zend_Session_Namespace(Sportalize_Controller_Action::SESSION_NAMESPACE_NOTIFICATION);
        $session->notificationStatus  = $status;
        $session->notificationMessage = $message;
    }

    public function getDatePickerFormat($phpFormat) {
        $return = array('d.M.yy.' => 'd.m.yy',
                        'M/d/yy'  => 'm/d/yy');
        return $return[$phpFormat];
    }

    public function setLocParam() {
        $userInfo = $this->user->getUserInfoList();
        $loc = 'en_US';
        if ($userInfo[0]->country_id) {
            if ($userInfo[0]->country_id == '189') {
                $loc = 'sr_RS';
            }
        }
        $this->loc = $loc;

        Zend_Registry::set('loc', $loc);
        Zend_Locale::setDefault($loc);
        $locale = new Zend_Locale($loc);
        Zend_Registry::set('Zend_Locale', $locale);
        return $loc;
    }

    public function setDatePickerFormat($loc) {
        $list = Zend_Locale::getTranslationList('Date', $loc);
        $this->view->datePickerFormat = $this->getDatePickerFormat($list['short']);
    }
}