<?php

class PersonalSettingsForm extends Sportalize_Form_Base {

    public function init() {
        $this->setAction(APP_URL . '/settings/index');
        parent::init();
    }

    public function createElements() {

        $favourites = new PersonalSettings_Favourites();
        $this->addSubForm($favourites, 'favourites');

        $this->addElement('submit', 'submit');
    }
}
