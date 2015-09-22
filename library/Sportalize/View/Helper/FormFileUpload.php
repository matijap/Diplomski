<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormFileUpload extends Zend_View_Helper_FormElement
{
    public function formFileUpload($name, $value = null, $attribs = null, array $options = null)
    {
        $info = $this->_getInfo($name, $value, $attribs, $options);
        extract($info); // name, value, attribs, options
        $file              = isset($attribs['file']) ? $attribs['file'] : '';
        $divWithImageClass = empty($file) ? '' : 'height-50px';
        $class             = isset($attribs['class']) ? $attribs['class'] : '';
        $randomNumber      = Utils::getRandomNumber();
        $id                = isset($attribs['customID']) ? $attribs['customID'] : $name;

        $xhtml  =  '<input name="' . $name . '" data-trigger="' . $randomNumber . '" id="' . $id . '" type="file" class="upload-change-it ' . $class . '" />';
        $xhtml .= '<div class="' . $divWithImageClass . ' list-with-button-upload upload-' . $randomNumber . '">';

        if (!empty($file)) {
            $xhtml .= '<img src="' . APP_URL . '/' . $file . '" />';
        }
                    
        $xhtml .= '</div>';
        return $xhtml;
    }
}