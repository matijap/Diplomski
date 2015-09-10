<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_PlainHtml extends Zend_Form_Element_Xhtml
{
    public $helper = 'formPlainHtml';

    public function isValid($data) {
        return true;
    }
}