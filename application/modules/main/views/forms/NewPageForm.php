<?php

class NewPageForm extends Sportalize_Form_Modal {

    public function init() {
        $this->setAction(APP_URL . '/page/new-page');
        $this->setModalTitle($this->translate->_('Create New Page'));
        parent::init();
    }

    public function createElements() {
        parent::createElements();

        $this->addElement('select', 'type', array(
            'multioptions' => Page::getPageMultioptions(),
            'label'        => $this->translate->_('Page dedicated to'),
        ));

        $this->addElement('text', 'title', array(
            'label'    => $this->translate->_('Title'),
            'required' => true
        ));

        $this->addElement('text', 'description', array(
            'label'    => $this->translate->_('Description'),
            'required' => true
        ));

        $fileUpload = new Sportalize_Form_Element_FileUpload( 'logo', array(
            'label' => $this->translate->_('Logo'),
        ));
        $fileUpload->setMaxFileSize('50');
        $this->addElement($fileUpload);

        $this->getUserIDElement();
    }
}