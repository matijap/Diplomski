<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_WidgetTableNewData extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formWidgetTableNewData';

    public function isValid($data) {
        return true;
    }
}