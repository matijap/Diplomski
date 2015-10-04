<?php

require_once 'Sport/Row.php';

class Sport extends Sport_Row
{
    const SPORT_FOOTBALL   = 'FOOTBALL';
    const SPORT_BASKETBALL = 'BASKETBALL';
    const SPORT_WATERPOLO  = 'WATERPOLO';
    const SPORT_VOLEYBALL  = 'VOLEYBALL';
    const SPORT_HANDBALL   = 'HANDBALL';
    const SPORT_TENIS      = 'TENIS';

    public static function getAvailableSports() {
        $sports = Main::select()
                 ->from(array('SP' => 'sport'))
                 ->query()->fetchAll();

        $translated = Sport::getTranslatedSports();
        $return = array();
        foreach ($sports as $key => $value) {
            $sports[$key]['name'] = $translated[$value['name']];
        }
        return $sports;
    }

    public static function getTranslatedSports() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return array(Sport::SPORT_FOOTBALL    => $translate->_('Football'),
                     Sport::SPORT_BASKETBALL  => $translate->_('Basketball'),
                     Sport::SPORT_WATERPOLO   => $translate->_('Waterpolo'),
                     Sport::SPORT_VOLEYBALL   => $translate->_('Voleyball'),
                     Sport::SPORT_HANDBALL    => $translate->_('Handball'),
                     Sport::SPORT_TENIS       => $translate->_('Tenis'),
                     );
    }

    public static function getSportMultioptions() {
        $sports = Sport::getAvailableSports();
        $return = array();
        foreach ($sports as $key => $value) {
            $return[$value['id']] = $value['name'];
        }
        return $return;
    }
    public static function getSportMultioptionsForDreamTeam() {
        $sports = Sport::getAvailableSports();
        $return = array();
        foreach ($sports as $key => $value) {
            $return[$value['id'] . '_' . $value['field_players']] = $value['name'];
        }
        return $return;
    }

    public static function extractSportNamesAndMergeIntoString($sports) {
        $translated = Sport::getTranslatedSports();
        $return     = array();
        foreach ($sports as $oneSport) {
            $return[] = $translated[$oneSport['name']];
        }
        return Utils::mergeStrings($return, ', ');
    }
}