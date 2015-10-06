<?php

class Galery_Delete extends DeleteItemForm {

    public $gid;

    public function __construct($data) {
        $this->gid      = $data['galeryID'];
        $Galery         = Main::buildObject('Galery', $this->gid);

        parent::__construct($data);
    }

    public function init() {
        $this->setAction(APP_URL . '/galery/delete-galery');
        $this->setModalTitle($this->translate->_('Delete galery'));
        parent::init();
    }

    public function createElements() {
        $this->addElement('hidden', 'galeryID', array('value' => $this->gid));

        $this->addWarningText($this->translate->_('Are you sure that you want to delete this galery? All pictures
                associated with this galery will be removed too.'));
    }
}