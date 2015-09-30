<?php

require_once 'PrivacySetting/Row.php';

class PrivacySetting extends PrivacySetting_Row
{
    const PRIVACY_TYPE_POST    = 'POST';
    const PRIVACY_TYPE_PROFILE = 'PROFILE';
    const PRIVACY_TYPE_GALERY  = 'GALERY';

    const PRIVACY_SETTING_ALL                 = 'ALL';
    const PRIVACY_SETTING_FRIENDS_OF_FRIENDS  = 'FRIENDS_OF_FRIENDS';
    const PRIVACY_SETTING_FRIENDS_ONLY        = 'FRIENDS_ONLY';
    const PRIVACY_SETTING_SPECIFIC_LISTS      = 'SPECIFIC_LISTS';
    const PRIVACY_SETTING_SPECIFIC_FRIENDS    = 'SPECIFIC_FRIENDS';
    const PRIVACY_SETTING_SPECIFIC_NONE       = 'NONE';

    public static function getPrivacySettingsMultioptions() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return array(
            self::PRIVACY_SETTING_ALL                => $translate->_('All'),
            self::PRIVACY_SETTING_FRIENDS_OF_FRIENDS => $translate->_('Friends of a Friends'),
            self::PRIVACY_SETTING_FRIENDS_ONLY       => $translate->_('Friends Only'),
            self::PRIVACY_SETTING_SPECIFIC_LISTS     => $translate->_('Specific friend groups'),
            self::PRIVACY_SETTING_SPECIFIC_FRIENDS   => $translate->_('Specific Friends'),
            self::PRIVACY_SETTING_SPECIFIC_NONE      => $translate->_('None'),
        );
    }

    public static function createDefaultPrivacySetting($userID) {
        PrivacySetting::create(array('user_id' => $userID, 'type' => PrivacySetting::PRIVACY_TYPE_POST, 'setting' => PrivacySetting::PRIVACY_SETTING_ALL));
        PrivacySetting::create(array('user_id' => $userID, 'type' => PrivacySetting::PRIVACY_TYPE_PROFILE, 'setting' => PrivacySetting::PRIVACY_SETTING_ALL));
        PrivacySetting::create(array('user_id' => $userID, 'type' => PrivacySetting::PRIVACY_TYPE_GALERY, 'setting' => PrivacySetting::PRIVACY_SETTING_ALL));
    }

}