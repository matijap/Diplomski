<?php

class DeleteFriendListForm extends DeleteItemForm {
    public $friendlistID = false;

    public function __construct($data = array()) {
        $this->friendlistID = $data['listID'];
        parent::__construct($data);
    }

    public function init() {
        $this->setModalTitle($this->translate->_('Delete friend list'));
        parent::init();
        $this->setAction(APP_URL . '/index/delete-friend-list');
    }

    public function createElements() {
        parent::createElements();
        $this->addElement('hidden', 'listID', array(
            'value' => $this->friendlistID
        ));
        $this->addWarningText('Are you sure that you want to remove this friend list?');
    }
}