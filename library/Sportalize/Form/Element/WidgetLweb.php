<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_WidgetLweb extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formWidgetLweb';

    public function init() {
        parent::init();

        // $validator = new Zend_Validate_File_IsImage();
        // $validator->setMessage($this->translate->_(vsprintf('Accepted formats are %s, %s and %s', array('JPG', 'PNG', 'GIF'))), Zend_Validate_File_IsImage::FALSE_TYPE);
        // $this->addValidator($validator);

        // $validator = new Zend_Validate_File_Size(array(
        //     'min' => '1kb', 'max' => '5kb'
        // ));
        // $this->addValidator($validator);
    }

    public function isValid($data) {
        return true;
    }
}