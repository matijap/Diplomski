<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_Button extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formButton';

    public function isValid($data) {
        return true;
    }
}