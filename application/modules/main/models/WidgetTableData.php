<?php

require_once 'WidgetTableData/Row.php';

class WidgetTableData extends WidgetTableData_Row
{
    const SHORT_GF = 'GF';
    const SHORT_GA = 'GA';
    const SHORT_PT = 'PT';
    const SHORT_GD = 'GD';
    const SHORT_RB = 'RB';
    const SHORT_AS = 'AS';
    const SHORT_ST = 'ST';
    const SHORT_W  = 'W';
    const SHORT_L  = 'L';
    const SHORT_D  = 'D';

    const LONG_GOALS_FOR        = 'GOALS_FOR';
    const LONG_GOALS_AGAINST    = 'GOALS_AGAINST';
    const LONG_POINTS           = 'POINTS';
    const LONG_GOALS_DIFFERENCE = 'GOALS_DIFFERENCE';
    const LONG_REBOUNDS         = 'REBOUNDS';
    const LONG_ASSISTS          = 'ASSISTS';
    const LONG_STEALS           = 'STEALS';
    const LONG_WINS             = 'WINS';
    const LONG_LOSES            = 'LOSES';
    const LONG_DRAWS            = 'DRAWS';

    public static function getTranslatedSystemShort() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return(array(self::SHORT_GF => $translate->_('GF'),
                     self::SHORT_GA => $translate->_('GA'),
                     self::SHORT_PT => $translate->_('PT'),
                     self::SHORT_GD => $translate->_('GD'),
                     self::SHORT_RB => $translate->_('RB'),
                     self::SHORT_AS => $translate->_('AS'),
                     self::SHORT_ST => $translate->_('ST'),
                     self::SHORT_W  => $translate->_('W'),
                     self::SHORT_L  => $translate->_('L'),
                     self::SHORT_D  => $translate->_('D'),
               ));
    }

    public static function getTranslatedSystemLong() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return(array(self::LONG_GOALS_FOR        => $translate->_('Goals For'),
                     self::LONG_GOALS_AGAINST    => $translate->_('Goals Against'),
                     self::LONG_POINTS           => $translate->_('Points'),
                     self::LONG_GOALS_DIFFERENCE => $translate->_('Goal Difference'),
                     self::LONG_REBOUNDS         => $translate->_('Rebounds'),
                     self::LONG_ASSISTS          => $translate->_('Assists'),
                     self::LONG_STEALS           => $translate->_('Steals'),
                     self::LONG_WINS             => $translate->_('Wins'),
                     self::LONG_LOSES            => $translate->_('Loses'),
                     self::LONG_DRAWS            => $translate->_('Draws'),
              ));
    }

    public static function getMultioptions($userID = false) {
        $translatedLong = self::getTranslatedSystemLong();
        $return = array(self::SHORT_GF => $translatedLong[self::LONG_GOALS_FOR],
                        self::SHORT_GA => $translatedLong[self::LONG_GOALS_AGAINST],
                        self::SHORT_PT => $translatedLong[self::LONG_POINTS],
                        self::SHORT_GD => $translatedLong[self::LONG_GOALS_DIFFERENCE],
                        self::SHORT_RB => $translatedLong[self::LONG_REBOUNDS],
                        self::SHORT_AS => $translatedLong[self::LONG_ASSISTS],
                        self::SHORT_ST => $translatedLong[self::LONG_STEALS],
                        self::SHORT_W  => $translatedLong[self::LONG_WINS],
                        self::SHORT_L  => $translatedLong[self::LONG_LOSES],
                        self::SHORT_D  => $translatedLong[self::LONG_DRAWS],
                    );
        if ($userID) {
            $res = self::getUserCreatedData($userID);
            foreach ($res as $value) {
                $return[$value['short']] = $value['long'];
            }
        }
        return $return;
    }

    public static function translateShort($toTranslate) {
        $translatedShort = self::getTranslatedSystemShort();
        return in_array($toTranslate, $translatedShort) ? $translatedShort[$toTranslate] : $toTranslate;
    }

    public static function getUserCreatedData($userID) {
        return Main::select()
                    ->from(array('WDT' => 'widget_table_data'), '*')
                    ->where('user_id = ?', $userID)
                    ->query()->fetchAll();
    }
}