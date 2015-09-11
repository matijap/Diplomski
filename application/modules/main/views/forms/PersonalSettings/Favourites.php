<?php

class PersonalSettings_Favourites extends PersonalSettings_PersonalSettingsBaseForm {

    public function __construct() {
        parent::__construct();
    }

    public function createElements() {
        parent::createElements();

        $this->addTitleElement($this->translate->_('About Me'));

        $favouriteSport = $this->createElement('select', 'favourite_sport', array(
            'multioptions' => array('a', 'b'),
            'style'        => 'width: 100%;',
            'label'        => $this->translate->_('Favourite sport'),
            'multiple'     => 'multiple',
            'value'        => array('0')
        ));
        // $favouriteSport->setValue(array('a', ''));
        $this->addElement($favouriteSport);
        $this->addDisplayGroup(array('favourite_sport'), 'dg1');

        $data            = Utils::arrayFetch($this->params, 'personal_settings.favourites', false);

        $selectedPlayers = Utils::arrayFetch($this->params, 'personal_settings.favourites.available_players', false);
        $favouritePlayer = new Sportalize_Form_Element_PersonalSettingsFavouritePlayerOrTeam( 'favourite_player', array(
            'data'                   => $data,
            'selectedPlayersOrTeams' => $selectedPlayers,
            'title'                  => $this->translate->_('Favourite Players'),
            'playerOrTeam'           => $this->translate->_('Players'),
            'elementName'            => 'players'
        ));
        $this->addElement($favouritePlayer);
        $this->addDisplayGroup(array('favourite_player'), 'dg2');

        $selectedTeams = Utils::arrayFetch($this->params, 'personal_settings.favourites.available_teams', false);
        $favouriteTeam = new Sportalize_Form_Element_PersonalSettingsFavouritePlayerOrTeam( 'favourite_team', array(
            'data'                   => $data,
            'selectedPlayersOrTeams' => $selectedTeams,
            'title'                  => $this->translate->_('Favourite Teams'),
            'playerOrTeam'           => $this->translate->_('Teams'),
            'elementName'            => 'teams'
        ));
        $this->addElement($favouriteTeam);
        $this->addDisplayGroup(array('favourite_team'), 'dg3');
    }

    public function redecorate() {
        parent::redecorate();
        $element = $this->getElement('favourite_sport');
        $decorator = array(
            'ViewHelper',
            'Description',
            array('Errors'),
            array('HtmlTag', array('tag'  => 'div', 'class' => 'm-t-10')),
            array('Label', array('class' => 'display-inline-block"')),
        );
        $this->clearDecoratorsAndSetDecorator($element, $decorator);
    }
}