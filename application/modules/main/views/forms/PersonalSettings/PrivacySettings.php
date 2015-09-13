<?php

class PersonalSettings_PrivacySettings extends PersonalSettings_PersonalSettingsBaseForm {
    
    public function __construct() {
        $this->class = 'privacy-settings';
        parent::__construct();
    }
    public function createElements() {
        $this->addTitleElement($this->translate->_('Privacy settings'));

        $personalInfoVisibility = new Sportalize_Form_Element_PersonalSettingsPrivacy( 'personal_info_visibility', array(
             'title'        => $this->translate->_('Personal Info'),
             'visibility'   => 'personal-info'
            )
        );
        $this->addElement($personalInfoVisibility);
        $this->addDisplayGroup(array('personal_info_visibility'), 'personal_info_visibility_dg');

        $postsVisibility = new Sportalize_Form_Element_PersonalSettingsPrivacy( 'post_visibility', array(
             'title'        => $this->translate->_('Posts'),
             'visibility'   => 'posts'
            )
        );
        $this->addElement($postsVisibility);
        $this->addDisplayGroup(array('post_visibility'), 'posts_visibility_dg');

        $galeryVisibility = new Sportalize_Form_Element_PersonalSettingsPrivacy( 'galery_visibility', array(
             'title'        => $this->translate->_('Galery'),
             'visibility'   => 'posts'
            )
        );
        $this->addElement($galeryVisibility);
        $this->addDisplayGroup(array('galery_visibility'), 'galery_visibility_dg');
    }
}