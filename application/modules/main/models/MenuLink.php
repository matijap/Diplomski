<?php

require_once 'MenuLink/Row.php';

class MenuLink extends MenuLink_Row
{
    const MENU_LINK_NOTIFICATIONS     = 'NOTIFICATIONS';
    const MENU_LINK_REFRESH_ME        = 'REFRESH_ME';
    const MENU_LINK_MY_POSTS          = 'MY_POSTS';
    const MENU_LINK_GAMES             = 'GAMES';
    const MENU_LINK_MORE              = 'MORE';
    const MENU_LINK_FANTASY           = 'FANTASY';
    const MENU_LINK_QUIZ              = 'QUIZ';
    const MENU_LINK_VIRTUAL_BOOKING   = 'VIRTUAL_BOOKING';
    const MENU_LINK_NEW_PAGE          = 'NEW_PAGE';
    const MENU_LINK_GALERY            = 'GALERY';
    const MENU_LINK_PERSONAL_SETTINGS = 'PERSONAL_SETTINGS';
    const MENU_LINK_FAVORITES         = 'FAVORITES';
    const MENU_LINK_SIGN_OUT          = 'SIGN_OUT';

    public static function fetchMenuLinks() {
        
        $links  = Main::fetchAll('MenuLink', Main::select('MenuLink')->order(array('parent_menu_link_id', 'display_order')));
        $return = array();
        foreach ($links as $oneLink) {
            if (empty($oneLink['parent_menu_link_id'])) {
                $return[$oneLink['id']] = array('url' => $oneLink['url'], 'title' => $oneLink['title'], 'class' => $oneLink['class']);
            } else {
                $return[$oneLink['parent_menu_link_id']]['sublinks'][$oneLink['id']] = array('url' => $oneLink['url'], 'title' => $oneLink['title'], 'class' => $oneLink['class']);
            }
        }
        return $return;
    }

    public static function getTranslatedMenuTitles() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return array(self::MENU_LINK_NOTIFICATIONS     => $translate->_('Notifications'),
                     self::MENU_LINK_REFRESH_ME        => $translate->_('Refresh Me'),
                     self::MENU_LINK_MY_POSTS          => $translate->_('My Posts'),
                     self::MENU_LINK_GAMES             => $translate->_('Games'),
                     self::MENU_LINK_MORE              => $translate->_('More'),
                     self::MENU_LINK_FANTASY           => $translate->_('Fantasy'),
                     self::MENU_LINK_QUIZ              => $translate->_('Quiz'),
                     self::MENU_LINK_VIRTUAL_BOOKING   => $translate->_('Virtual Booking'),
                     self::MENU_LINK_NEW_PAGE          => $translate->_('New Page'),
                     self::MENU_LINK_GALERY            => $translate->_('Galery'),
                     self::MENU_LINK_PERSONAL_SETTINGS => $translate->_('Personal Settings'),
                     self::MENU_LINK_FAVORITES         => $translate->_('Favorites'),
                     self::MENU_LINK_SIGN_OUT          => $translate->_('Sign Out'),
                     );
    }
}
