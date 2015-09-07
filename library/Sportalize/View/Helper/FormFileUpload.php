<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormFileUpload extends Zend_View_Helper_FormElement
{
    public function formFileUpload($name, $value = null, $attribs = null, array $options = null)
    {
        $info = $this->_getInfo($name, $value, $attribs, $options);
        extract($info); // name, value, attribs, options

        $randomNumber = Utils::getRandomNumber();
        $xhtml =  '<input data-trigger="' . $randomNumber . '" id="' . $name . '" type="file" class="upload-change-it" />';
        $xhtml .= '<div class="list-with-button-upload upload-' . $randomNumber . '">
                    
                  </div>';
        return $xhtml;
    }
}