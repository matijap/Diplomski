<?php

class PersonalSettings_DreamTeam extends PersonalSettings_PersonalSettingsBaseForm {

    public function __construct() {
        $this->class = 'dream-team';
        parent::__construct();
    }

    public function createElements() {
        parent::createElements();

        $this->addTitleElement($this->translate->_('Dream Teams'), 'pull-left');

        $sports = Utils::getAvailableSports();
        $this->addElement('select', 'dream_team_sport_select', array(
            'multioptions' => $sports,
            'class'        => 'width-200px add-dream-team-sport',
        ));
        $this->addElement('text', 'dream_team_name', array(
            'class'       => 'add-dream-team-name',
            'placeholder' => $this->translate->_('Dream Team Name'),
        ));
        $button = new Sportalize_Form_Element_Button('add_dream_team', array(
            'text'  => $this->translate->_('Add a Team'),
            'class' => 'blue-button add-dream-team',
        ));
        $this->addElement($button);
        
        $this->addDisplayGroup(array('dream_team_sport_select', 'dream_team_name', 'add_dream_team'), 'dream_team_add_team');

        $dreamTeams = Utils::arrayFetch($this->params, 'personal_settings.dream_team', false);
        $dreamTeams = $dreamTeams ? $dreamTeams: $this->user->getDreamTeams();

        if (!empty($dreamTeams)) {
            foreach ($dreamTeams as $key => $oneTeam) {
                if (!is_array($oneTeam['data'])) {
                    $oneTeam['data'] = Zend_Json::decode($oneTeam['data']);
                }
                $dreamTeam = new Sportalize_Form_Element_PersonalSettingsDreamTeam( 'dream_team_' . $key, array(
                    'data'      => $oneTeam['data'],
                    'team_name' => $oneTeam['name'],
                    'key'       => $key,
                ));
                $this->addElement($dreamTeam);
                $this->addDisplayGroup(array('dream_team_' . $key), 'dg_' . $key);
            }
        }
    }

    public function redecorate() {
        parent::redecorate();

        $dg = $this->getDisplayGroup('dream_team_add_team');
        $decorator = array(
                        'FormElements',
                        array('HtmlTag', array('tag'   =>'div','class'  => 'add-dream-team-container'))
                    );
        $this->clearDecoratorsAndSetDecorator($dg, $decorator);
    }
}