<?php

class PersonalSettings_Favourites extends PersonalSettings_PersonalSettingsBaseForm {

    public function __construct() {
        $this->dgClass = 'favorite';
        parent::__construct();
    }

    public function createElements() {
        parent::createElements();

        $this->addTitleElement($this->translate->_('About Me'));

        $sports             = Sport::getSportMultioptions();
        $userFavouriteSport = $this->user->getFavouriteSports();
        
        $favouriteSport     = $this->createElement('select', 'favourite_sport', array(
            'multioptions' => $sports,
            'style'        => 'width: 100%;',
            'label'        => $this->translate->_('Favourite sport'),
            'multiple'     => 'multiple',
            'value'        => $userFavouriteSport,
            'belongsTo'    => 'personal_settings[favourites]'
        ));
        $this->addElement($favouriteSport);
        $this->addDisplayGroup(array('favourite_sport'), 'dg1');

        $data = Utils::arrayFetch($this->params, 'personal_settings.favourites', false);
        if (!$data) {
            $temp = array();
            $data = $this->user->getFavouritePlayersAndTeamsWithoutPage();
            foreach ($data as $key => $value) {
                if ($value['type'] == FavouriteItem::FAVOURITE_ITEM_TYPE_PLAYER) {
                    $temp['players'][] = $value['name'];
                } else {
                    $temp['teams'][] = $value['name'];
                }
            }
            //adding this here - faking one input that is in template html
            $temp['players'][] = '';
            $temp['teams'][]   = '';
            $data = $temp;
        }

        $favouritePlayers = $this->user->getFavouriteSports();
        $selectedPlayers  = Utils::arrayFetch($this->params, 'personal_settings.favourites.available_players', false);
        $selectedPlayers  = $selectedPlayers ? $selectedPlayers : $this->user->getFavouritePlayersWithPage();
        $favouritePlayer  = new Sportalize_Form_Element_PersonalSettingsFavouritePlayerOrTeam( 'favourite_player', array(
            'data'                   => $data,
            'selectedPlayersOrTeams' => $selectedPlayers,
            'title'                  => $this->translate->_('Favourite Players'),
            'playerOrTeam'           => $this->translate->_('Players'),
            'elementName'            => 'players'
        ));
        $this->addElement($favouritePlayer);
        $this->addDisplayGroup(array('favourite_player'), 'dg2');

        $selectedTeams = Utils::arrayFetch($this->params, 'personal_settings.favourites.available_teams', false);
        $selectedTeams = $selectedTeams ? $selectedTeams : $this->user->getFavouriteTeamsWithPage();
        $favouriteTeam = new Sportalize_Form_Element_PersonalSettingsFavouritePlayerOrTeam( 'favourite_team', array(
            'data'                   => $data,
            'selectedPlayersOrTeams' => $selectedTeams,
            'title'                  => $this->translate->_('Favourite Teams'),
            'playerOrTeam'           => $this->translate->_('Teams'),
            'elementName'            => 'teams'
        ));
        $this->addElement($favouriteTeam);
        $this->addDisplayGroup(array('favourite_team'), 'dg3');

        $playerTemplate = new Sportalize_Form_Element_PlainHtml('player_template', array(
            'value' => '<div class="favorite-players-template display-none">
                    <div class="to-be-removed">
                        <input type="text" class="m-t-5" name="personal_settings[favourites][players][]">
                        <i class="fa fa-times m-l-5 cursor-pointer remove-item" data-closest="favorite"></i>
                    </div>
                </div>'
        ));
        $this->addElement($playerTemplate);
        $teamTemplate = new Sportalize_Form_Element_PlainHtml('team_template', array(
            'value' => '<div class="favorite-teams-template display-none">
                    <div class="to-be-removed">
                        <input type="text" class="m-t-5" name="personal_settings[favourites][teams][]">
                        <i class="fa fa-times m-l-5 cursor-pointer remove-item" data-closest="favorite"></i>
                    </div>
                </div>'
        ));
        $this->addElement($teamTemplate);
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