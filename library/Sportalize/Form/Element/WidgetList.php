<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_WidgetList extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formWidgetList';

    public function isValid($data) {
        return true;
    }
}