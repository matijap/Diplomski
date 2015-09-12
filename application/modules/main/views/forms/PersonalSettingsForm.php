<?php

class PersonalSettingsForm extends Sportalize_Form_Base {

    public function init() {
        $this->setAction(APP_URL . '/settings/index');
        parent::init();
    }

    public function createElements() {

        $personalInfoForm = new PersonalSettings_PersonalInfo();
        $this->addSubForm($personalInfoForm, 'personal_info');

        $favouritesForm = new PersonalSettings_Favourites();
        $this->addSubForm($favouritesForm, 'favourites');

        $dreamTeamsForm = new PersonalSettings_DreamTeam();
        $this->addSubForm($dreamTeamsForm, 'dream_teams');

        $this->addElement('submit', 'submit');
    }
}
