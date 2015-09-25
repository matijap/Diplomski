<?php

require_once 'Language/Row.php';

class Language extends Language_Row
{
    const LANGUAGE_ENGLISH    = 'ENGLISH';
    const LANGUAGE_SERBIAN    = 'SERBIAN';

    const LANGUAGE_ENGLISH_ID = '1';
    const LANGUAGE_SERBIAN_ID = '2';

    public static function getMultiOptions() {
        return array(
            self::LANGUAGE_ENGLISH_ID => self::$translate->_('English'),
            self::LANGUAGE_SERBIAN_ID => self::$translate->_('Serbian')
        );
    }
}