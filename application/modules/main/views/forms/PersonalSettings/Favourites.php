<?php

class PersonalSettings_Favourites extends PersonalSettings_PersonalSettingsBaseForm {

    public function __construct() {
        parent::__construct();
    }

    public function createElements() {
        parent::createElements();

        $this->addTitleElement($this->translate->_('About Me'));

        $data            = Utils::arrayFetch($this->params, 'personal_settings.favourites', false);
        $selectedPlayers = Utils::arrayFetch($this->params, 'personal_settings.favourites.available_players', false);
        $favouritePlayer = new Sportalize_Form_Element_PersonalSettingsFavouritePlayer( 'favourite_player', array(
            'data'            => $data,
            'selectedPlayers' => $selectedPlayers,
        ));

        $this->addElement($favouritePlayer);
        $this->addDisplayGroup(array('favourite_player'), 'dg1');
    }
}