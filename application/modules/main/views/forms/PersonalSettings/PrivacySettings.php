<?php

class PersonalSettings_PrivacySettings extends PersonalSettings_PersonalSettingsBaseForm {
    
    public function __construct() {
        $this->class = 'privacy-settings';
        parent::__construct();
    }
    public function createElements() {
        $this->addTitleElement($this->translate->_('Privacy settings'));
        $privacySettings = $this->user->getPrivacySettingList();

        $postData    = array();
        $profileData = array();
        $galeryData  = array();
        foreach ($privacySettings as $key => $value) {
            switch ($value['type']) {
                case PrivacySetting::PRIVACY_TYPE_POST:
                    $postData = $value->toArray();
                    break;
                case PrivacySetting::PRIVACY_TYPE_PROFILE:
                    $profileData = $value->toArray();
                    break;
                case PrivacySetting::PRIVACY_TYPE_GALERY:
                    $galeryData = $value->toArray();
                    break;
            }
        }

        $profileData = $this->processDataArrays($profileData, 'privacy_settings.personal_info_visibility');
        $personalInfoVisibility = new Sportalize_Form_Element_PersonalSettingsPrivacy( 'personal_info_visibility', array(
             'title'           => $this->translate->_('Personal Info'),
             'visibilityClass' => 'personal-info',
             'data'            => $profileData,
            )
        );
        $this->addElement($personalInfoVisibility);
        $this->addDisplayGroup(array('personal_info_visibility'), 'personal_info_visibility_dg');

        $postData = $this->processDataArrays($postData, 'privacy_settings.post_visibility');
        $postsVisibility = new Sportalize_Form_Element_PersonalSettingsPrivacy( 'post_visibility', array(
             'title'           => $this->translate->_('Posts'),
             'visibilityClass' => 'posts',
             'data'            => $postData,
            )
        );
        $this->addElement($postsVisibility);
        $this->addDisplayGroup(array('post_visibility'), 'posts_visibility_dg');

        $galeryData = $this->processDataArrays($galeryData, 'privacy_settings.galery_visibility');
        $galeryVisibility = new Sportalize_Form_Element_PersonalSettingsPrivacy( 'galery_visibility', array(
             'title'           => $this->translate->_('Galery'),
             'visibilityClass' => 'posts',
             'data'            => $galeryData,
            )
        );
        $this->addElement($galeryVisibility);
        $this->addDisplayGroup(array('galery_visibility'), 'galery_visibility_dg');
    }

    public function redecorate() {
        parent::redecorate();

        $decorator = array(
            'ViewHelper',
            'Description',
            'Errors',
         
        );
        
        foreach ($this->getElements() as $key => $element) {
            $this->clearDecoratorsAndSetDecorator($element, $decorator);
        }
    }

    public function processDataArrays($arrayToReturn, $path) {
        $temp = Utils::arrayFetch($this->params, $path, false);
        if ($temp) {
            $temp['setting'] = $temp['visibility'];
            $opts = false;
            if ($temp['setting'] == PrivacySetting::PRIVACY_SETTING_SPECIFIC_LISTS) {
                $opts = Utils::arrayFetch($temp, 'friend_list', false);
            }
            if ($temp['setting'] == PrivacySetting::PRIVACY_SETTING_SPECIFIC_FRIENDS) {
                $opts = Utils::arrayFetch($temp, 'specific_friends', false);
            }
            if ($opts) {
                $temp['options'] = Zend_Json::encode($opts);
            } else {
                $temp['options'] = '';
            }
            $arrayToReturn = $temp;
        }

        return $arrayToReturn;
    }
}