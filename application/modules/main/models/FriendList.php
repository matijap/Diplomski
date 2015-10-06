<?php

require_once 'FriendList/Row.php';

class FriendList extends FriendList_Row
{
    const FRIEND_LIST_WORK         = 'WORK';
    const FRIEND_LIST_FAMILY       = 'FAMILY';
    const FRIEND_LIST_BEST_FRIENDS = 'BEST_FRIENDS';

    public static function getSystemGroupsArray() {
        return array(self::FRIEND_LIST_WORK, self::FRIEND_LIST_FAMILY, self::FRIEND_LIST_BEST_FRIENDS);
    }

    public static function getTranslated($constant) {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $array = array(self::FRIEND_LIST_WORK         => $translate->_('Work'),
                       self::FRIEND_LIST_FAMILY       => $translate->_('Family'),
                       self::FRIEND_LIST_BEST_FRIENDS => $translate->_('Best Friends'));
        return Utils::arrayFetch($array, $constant, $constant);
    }

    public function delete() {
        if ($this->is_system) {
            return false;
        }
        Main::execQuery('DELETE FROM user_friend_list WHERE friend_list_id = ?', array($this->id));
        parent::delete();
        return true;
    }
}