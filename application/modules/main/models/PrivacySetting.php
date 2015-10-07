<?php

require_once 'PrivacySetting/Row.php';

class PrivacySetting extends PrivacySetting_Row
{
    const PRIVACY_TYPE_POST    = 'POST';
    const PRIVACY_TYPE_PROFILE = 'PROFILE';
    const PRIVACY_TYPE_GALERY  = 'GALERY';

    const PRIVACY_SETTING_ALL                 = 'ALL';
    const PRIVACY_SETTING_FRIENDS_OF_FRIENDS  = 'FRIENDS_OF_FRIENDS';
    const PRIVACY_SETTING_FRIENDS_ONLY        = 'FRIENDS_ONLY';
    const PRIVACY_SETTING_SPECIFIC_LISTS      = 'SPECIFIC_LISTS';
    const PRIVACY_SETTING_SPECIFIC_FRIENDS    = 'SPECIFIC_FRIENDS';
    const PRIVACY_SETTING_SPECIFIC_NONE       = 'NONE';

    public static function getPrivacySettingsMultioptions() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        return array(
            self::PRIVACY_SETTING_ALL                => $translate->_('All'),
            self::PRIVACY_SETTING_FRIENDS_OF_FRIENDS => $translate->_('Friends of a Friends'),
            self::PRIVACY_SETTING_FRIENDS_ONLY       => $translate->_('Friends Only'),
            self::PRIVACY_SETTING_SPECIFIC_LISTS     => $translate->_('Specific friend groups'),
            self::PRIVACY_SETTING_SPECIFIC_FRIENDS   => $translate->_('Specific Friends'),
            self::PRIVACY_SETTING_SPECIFIC_NONE      => $translate->_('None'),
        );
    }

    public static function createDefaultPrivacySetting($userID) {
        PrivacySetting::create(array('user_id' => $userID, 'type' => PrivacySetting::PRIVACY_TYPE_POST, 'setting' => PrivacySetting::PRIVACY_SETTING_ALL));
        PrivacySetting::create(array('user_id' => $userID, 'type' => PrivacySetting::PRIVACY_TYPE_PROFILE, 'setting' => PrivacySetting::PRIVACY_SETTING_ALL));
        PrivacySetting::create(array('user_id' => $userID, 'type' => PrivacySetting::PRIVACY_TYPE_GALERY, 'setting' => PrivacySetting::PRIVACY_SETTING_ALL));
    }

    public static function canView($watcherID, $watchedID, $whatIsViewed) {
        $privacy  = Main::fetchRow(Main::select('PrivacySetting')
                                ->where('type = ?', $whatIsViewed)
                                ->where('user_id = ?', $watchedID)
                                );

        $options  = !empty($privacy->options) ? Zend_Json::decode($privacy->options) : false;

        $decision = false;
        $watcher  = Main::buildObject('User', $watcherID);
        $watched  = Main::buildObject('User', $watchedID);
        switch($privacy->setting) {
            case self::PRIVACY_SETTING_ALL:
                $decision = true;
                break;
            case self::PRIVACY_SETTING_FRIENDS_OF_FRIENDS:
                $decision = $watched->areFriends($watcher->id);
                if (!$decision) {
                    $watchedFriendList = $watched->getFriendList(false, true);
                    foreach ($watchedFriendList as $oneFriendID) {
                        if ($oneFriendID != User::SPORTALIZE_USER_ID) {
                            $friend = Main::buildObject('User', $oneFriendID);
                            $decision = $friend->areFriends($watcherID);
                            if ($decision) {
                                break;
                            }
                        }
                    }
                }
                break;
            case self::PRIVACY_SETTING_FRIENDS_ONLY:
                $decision = $watched->areFriends($watcher->id);
                break;
            case self::PRIVACY_SETTING_SPECIFIC_LISTS:
                foreach ($options as $oneFriendList) {
                    $friendList = Main::buildObject('FriendList', $oneFriendList);
                    $decision   = $friendList->doesUserExistsInFriendList($watcherID, $watchedID);
                    if ($decision) {
                        break;
                    }
                }
                break;
            case self::PRIVACY_SETTING_SPECIFIC_FRIENDS:
                $decision = in_array($watcherID, $options);
                break;
            case self::PRIVACY_SETTING_SPECIFIC_NONE:
                $decision = false;
                break;
        }
        return $decision;
    }

}