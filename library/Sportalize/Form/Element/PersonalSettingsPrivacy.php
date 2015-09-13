<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_PersonalSettingsPrivacy extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formPersonalSettingsPrivacy';

    public function isValid($data) {
        return true;
    }
}