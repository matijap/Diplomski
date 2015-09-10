<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormPlainHtml extends Zend_View_Helper_FormElement
{
    public function formPlainHtml($name, $value = null, $attribs = null, array $options = null)
    {
        return $value;
    }
}