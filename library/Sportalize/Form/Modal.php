<?php

class Sportalize_Form_Modal extends Sportalize_Form_Base {
    
    public $modalTitle = '';

    public function setModalTitle($title) {
        $this->modalTitle = $title;
    }
    public function getModalTitle() {
        return $this->modalTitle;
    }

    public function redecorate() {
        $this->addElement('hidden', 'modal_title', array('value' => $this->getModalTitle()));

        parent::redecorate();

        $decorator = array(
            'ViewHelper',
            'Description',
            array('Errors'),
            array('HtmlTag', array('tag'  => 'div', 'class' => 'main-div')),
            array('Label', array('class' => 'main-label')),
            array(array('All' => 'HtmlTag'), array('tag'    => 'div', 'class'   => 'modal-element')),
        );
        $toBeDecorated = array('Zend_Form_Element_Text', 'Zend_Form_Element_Textarea',
                               'Zend_Form_Element_Select', 'Sportalize_Form_Element_FileUpload');

        $decoratorFile = array(
            'File',
            'Description',
            array('Errors'),
            array('HtmlTag', array('tag'  => 'div', 'class' => 'main-div')),
            array('Label', array('class' => 'main-label')),
            array(array('All' => 'HtmlTag'), array('tag'    => 'div', 'class'   => 'modal-element')),
        );

        foreach ($this->getElements() as $key => $oneElement) {
            if (in_array($oneElement->getType(), $toBeDecorated)) {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
        }
    }
}