<?php

require_once 'Zend/View/Helper/FormElement.php';

class Sportalize_View_Helper_FormPersonalSettingsDreamTeam extends Zend_View_Helper_FormElement
{
    public function formPersonalSettingsDreamTeam($name, $value = null, $attribs = null, array $options = null)
    {
        $translate = Zend_Registry::getInstance()->Zend_Translate;

        $info = $this->_getInfo($name, $value, $attribs, $options);
        extract($info); // name, value, attribs, options

        $data         = $attribs['data'];
        $name         = $attribs['team_name'];
        $key          = $attribs['key'];
        $html         = '<h3 class="text-align-center">' . $name . '</h3>
                           <input type="hidden" name="personal_settings[dream_team][' . $key . '][name]" value="' . $name . '">
                           <i data-closest="personal-settings-container" class="fa fa-times remove-item remove-dream-team"></i>';
        $html        .= '<div>';
        $randomNumber = Utils::getRandomNumber();
        foreach ($data as $value) {
            $html .= '<input name="personal_settings[dream_team][' . $key . '][data][]" value="' . $value . '" type="text">';
        }
        $html .= '</div>';
        return $html;
    }
}