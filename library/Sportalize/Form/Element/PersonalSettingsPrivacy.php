<?php

require_once 'Zend/Form/Element/File.php';

class Sportalize_Form_Element_PersonalSettingsPrivacy extends Sportalize_Form_Element_Xhtml
{
    public $helper = 'formPersonalSettingsPrivacy';

    public function isValid($data) {
        $setting = $this->params['privacy_settings'][$this->getName()];

        $key = '';
        switch ($setting['visibility']) {
            case PrivacySetting::PRIVACY_SETTING_SPECIFIC_LISTS:
                $key = 'friend_list';
                break;
            case PrivacySetting::PRIVACY_SETTING_SPECIFIC_FRIENDS:
                $key = 'specific_friends';
                break;
        }
        
        if ($setting['visibility'] == PrivacySetting::PRIVACY_SETTING_SPECIFIC_LISTS
            || $setting['visibility'] == PrivacySetting::PRIVACY_SETTING_SPECIFIC_FRIENDS) {
            $selected = Utils::arrayFetch($setting, $key, false);
            if (!$selected) {
                $this->_messages[] = $this->translate->_('You must pick at least one item');
                $this->return = false;
            }
        }
        return $this->return;
    }
}