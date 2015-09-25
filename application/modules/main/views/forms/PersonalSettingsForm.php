<?php

class PersonalSettingsForm extends Sportalize_Form_Base {

    public function init() {
        $this->setAction(APP_URL . '/settings/index');
        parent::init();
    }

    public function createElements() {

        $personalInfoForm    = new PersonalSettings_PersonalInfo();
        $this->addSubForm($personalInfoForm, 'personal_info');

        $otherForm           = new PersonalSettings_Other();
        $this->addSubForm($otherForm, 'other');

        $privacySettingsForm = new PersonalSettings_PrivacySettings();
        $this->addSubForm($privacySettingsForm, 'privacy_settings');

        $favouritesForm      = new PersonalSettings_Favourites();
        $this->addSubForm($favouritesForm, 'favourites');

        $dreamTeamsForm      = new PersonalSettings_DreamTeam();
        $this->addSubForm($dreamTeamsForm, 'dream_teams');

        $this->addElement('submit', 'submit');
    }
}
