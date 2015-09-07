<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_FileUpload extends Zend_Form_Element_Xhtml
{
    public $helper = 'formFileUpload';

    public function isValid($value) {
        return true;
    }
}