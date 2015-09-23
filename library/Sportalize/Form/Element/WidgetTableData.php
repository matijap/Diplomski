<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_WidgetTableData extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formWidgetTableData';

    public function isValid($data) {
        return true;
    }
}