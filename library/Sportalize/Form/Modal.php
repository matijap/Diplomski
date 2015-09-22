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

        $decorator     = $this->getDefaultModalDecorators();
        $toBeDecorated = array('Zend_Form_Element_Text', 'Zend_Form_Element_Textarea', 'Zend_Form_Element_Radio',
                               'Zend_Form_Element_Select');

        foreach ($this->getElements() as $key => $oneElement) {
            if (in_array($oneElement->getType(), $toBeDecorated)) {
                $this->clearDecoratorsAndSetDecorator($oneElement, $decorator);
            }
        }
        $this->redecorateFileUpload();
    }

    public function getUserIDElement($fieldName = 'user_id') {
        $this->addElement('hidden', $fieldName, array('value' => $this->user->id));
    }
    
    public function addDeleteHiddenElement() {
        $this->addElement('hidden', 'is_delete', array('value' => 1));    
    }
}