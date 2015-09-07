<?php

class NewPostForm extends Sportalize_Form_Modal {
    
    public function init() {
        $this->setAction(APP_URL . '/post/new-post');
        $this->setModalTitle($this->translate->_('Create New Post'));
        parent::init();
    }

    public function createElements() {
        parent::createElements();

        $this->addElement('select', 'post-selection', array(
            'label'        => $this->translate->_('Type'),
            'multioptions' => array('text' => 'text', 'image' => 'image', 'video' => 'video'),
            'class'        => 'width-100px'
        ));

        $this->addElement('text', 'title', array(
            'label' => $this->translate->_('Title'),
        ));

        $this->addElement('textarea', 'text', array(
            'label' => $this->translate->_('Text'),
            'rows'  => 5,
        ));

        $this->addElement('text', 'image-url', array(
            'label' => $this->translate->_('Image URL'),
        ));

        $test = new Sportalize_Form_Element_FileUpload( 'image-upload', array(
            'label' => $this->translate->_('Upload Image'),
        ));
        $this->addElement($test);

        $this->addElement('text', 'video-url', array(
            'label' => $this->translate->_('Video URL'),
        ));
    }

    
}