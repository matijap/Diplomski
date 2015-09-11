<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormButton extends Zend_View_Helper_FormElement
{
    public function formButton($name, $value = null, $attribs = null, array $options = null)
    {
        $info = $this->_getInfo($name, $value, $attribs, $options);
        extract($info); // name, value, attribs, options
        $text  = $attribs['text'];
        $href  = isset($attribs['href']) ? $attribs['href'] : 'javascript:void(0)';
        $class = isset($attribs['class']) ? $attribs['class'] : '';
        return '<a class="' . $class . '" href="' . $href . '">' . $text . '</a>';
    }
}