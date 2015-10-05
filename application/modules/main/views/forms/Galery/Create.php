<?php

class Galery_Create extends Sportalize_Form_Modal {

      public function init() {
        $this->setAction(APP_URL . '/galery/create-galery');
        $this->setModalTitle($this->translate->_('Create galery'));
        parent::init();
    }

    public function createElements() {
        $this->addElement('text', 'title', array(
            'label'    => $this->translate->_('Title'),
            'required' => true,
        ));
        $this->getUserIDElement();
    }
}