<?php

class Galery_Delete extends Sportalize_Form_Modal {

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

        $message = new Sportalize_Form_Element_PlainHtml('hr', array(
            'value' => '<p>' . $this->translate->_('Are you sure that you want to delete this galery? All pictures
                associated with this galery will be removed too.') . '</p>'
        ));
        $this->addElement($message);
        $this->addDeleteHiddenElement();
    }
}