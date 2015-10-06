<?php
class DeleteItemForm extends Sportalize_Form_Modal {
    public function init() {
        parent::init();
        $this->addDeleteHiddenElement();
    }

    public function addWarningText($text) {
        $message = new Sportalize_Form_Element_PlainHtml('hr', array(
            'value' => '<p>' . $text . '</p>'
        ));
        $this->addElement($message);
    }
}
        