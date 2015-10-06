<?php

class Galery_ImageDelete extends DeleteItemForm {

    public $imd;

    public function __construct($data) {
        $this->imd = $data['imageID'];

        parent::__construct($data);
    }

    public function init() {
        $this->setAction(APP_URL . '/galery/delete-image?imageID=' . $this->imd);
        $this->setModalTitle($this->translate->_('Delete image'));
        parent::init();
    }

    public function createElements() {
        $this->addElement('hidden', 'imageID', array('value' => $this->imd));
        $this->addWarningText($this->translate->_('Are you sure that you want to delete this image?'));
    }
}