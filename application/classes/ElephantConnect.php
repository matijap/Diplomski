<?php

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;

class ElephantConnect {
    
    public $elephant      = false;
    public $userID        = false;
    private $emitLocation = false;
    public $translate;

    public function __construct($data = array()) {
        $config         = Zend_Registry::get('config');
        $elephant       = new Elephant(new Version1X($config->services->socketIO->host));
        $this->elephant = $elephant;

        if (isset($data['userID'])) {
            $this->userID = $data['userID'];
        }
        $this->translate = Zend_Registry::getInstance()->Zend_Translate;
        return $this;
    }

    private function _send($data = array()) {
        $this->elephant->initialize();
        $this->elephant->emit($this->emitLocation, $data);
        $this->elephant->close();

    }

    public function initializePersonOnline() {
        $this->emitLocation = 'user_online';
        $user       = Main::buildObject('User', $this->userID);
        $friendList = $user->getFriendList(false, true);
        $this->_send(array('userID' => $this->userID, 'friendList' => $friendList));
    }

    public function notifyThatFriendRequestIsAccepted($toNotify) {
        $this->emitLocation = 'notify_accepted_friend_request';
        $toNotifyUser       = Main::buildObject('User', $toNotify);
        $userInfo           = $toNotifyUser->getUserInfo();
        $text               = $userInfo->getFullName() . ' ' . $this->translate->_('accepted your friend request');
        $this->_send(array('toNotify' => $toNotifyUser->id, 'text' => $text));
    }
    
}