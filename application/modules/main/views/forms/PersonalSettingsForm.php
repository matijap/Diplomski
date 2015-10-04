<?php

class PersonalSettingsForm extends Sportalize_Form_Base {

    public function init() {
        $this->setAction(APP_URL . '/settings/index');
        parent::init();
    }

    public function createElements() {
        $this->addElement('submit', 'submit_1',array(
            'class' => 'green-button submit-personal-settings-button',
            'label' => $this->translate->_('Submit')
        ));
        $clear =  new Sportalize_Form_Element_PlainHtml('clear_1', array(
            'value' => '<div class="clear"></div>'
        ));
        $this->addElement($clear);

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

        $clear =  new Sportalize_Form_Element_PlainHtml('clear_2', array(
            'value' => '<div class="clear"></div>'
        ));
        $this->addElement($clear);

        $this->addElement('submit', 'submit_2',array(
            'class' => 'green-button submit-personal-settings-button',
            'label' => $this->translate->_('Submit')
        ));
    }

    public function redecorate() {
        parent::redecorate();
        $submit = $this->getElement('submit_1');
        $this->clearDecoratorsAndSetViewHelperOnly($submit);
        $submit = $this->getElement('submit_2');
        $this->clearDecoratorsAndSetViewHelperOnly($submit);
    }
}
