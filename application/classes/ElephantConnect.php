<?php

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;

class ElephantConnect {
    
    public $elephant      = false;
    public $userID        = false;
    private $emitLocation = false;

    public function __construct($data = array()) {
        $config         = Zend_Registry::get('config');
        $elephant       = new Elephant(new Version1X($config->services->socketIO->host));
        $this->elephant = $elephant;

        if (isset($data['userID'])) {
            $this->userID = $data['userID'];
        }
        return $this;
    }

    private function _send($data = array()) {
        $this->elephant->initialize();
        $this->elephant->emit($this->emitLocation, $data);
        $this->elephant->close();

    }

    public function initializePersonOnline() {
        $this->emitLocation = 'person_online';
        $this->_send(array('userID' => $this->userID));
    }
    
}