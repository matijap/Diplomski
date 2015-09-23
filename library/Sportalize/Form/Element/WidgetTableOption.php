<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_WidgetTableOption extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formWidgetTableOption';

    public function isValid($data) {
        return true;
    }
}