<?php

require_once 'UserInfo/Row.php';

class UserInfo extends UserInfo_Row
{
    const AVATAR_IMAGES_FOLDER = 'user_images/avatars';
    const LOGOS_IMAGES_FOLDER  = 'user_images/big_logos';
    const DEFAULT_AVATAR       = 'silhouette.png';
    const DEFAULT_BIG_LOGO     = 'ball.jpg';

    public function getFullName() {
        return implode(' ', array($this->first_name, $this->last_name));
    }

    public function getCountryName() {
        $country = Main::buildObject('Country', $this->country_id);
        return $country->country_name;
    }
}