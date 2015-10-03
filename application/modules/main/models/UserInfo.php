<?php

require_once 'UserInfo/Row.php';

class UserInfo extends UserInfo_Row
{
    const AVATAR_IMAGES_FOLDER = 'user_images/avatars';
    const DEFAULT_AVATAR       = 'silhouette.png';

    public function getFullName() {
        return implode(' ', array($this->first_name, $this->last_name));
    }
}