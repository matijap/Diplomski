<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_WidgetLweb extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formWidgetLweb';

    public function isValid($data) {
        return true;
    }
}