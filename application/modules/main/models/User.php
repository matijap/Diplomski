<?php
require_once 'User/Row.php';

class User extends User_Row
{
    const MAX_LINK_DURATION     = 600;
    const INDEX_PAGE_POST_LIMIT = 10;

    public function generateToken() {
        $string = $this->email . '_' . time();
        return Utils::encrypt($string);
    }

    public function sendConfirmationEmail() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $subject   = $translate->_('Hello from SPORTALIZE!');
        $text      = $translate->_('Hey there, you have registered on our website. To complete the registration, please click');
        $link      = '/login/index/activate';
        return $this->sendClientMailWithToken($subject, $text, $link);
    }

    public function sendRegisterRenewToken() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $subject   = $translate->_('Hello from SPORTALIZE!');
        $text      = $translate->_('Hey there, you have requested new token for activation of your account. Please follow this link');
        $link      = '/login/index/activate';
        return $this->sendClientMailWithToken($subject, $text, $link);
    }

    public function sendForgotPasswordToken() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $subject   = $translate->_('Hello from SPORTALIZE!');
        $text      = $translate->_('Hey there, you have requested your password to be changed. If this was not you, or do not wish to change password, ignore this email. Please follow this link');
        $link      = '/login/index/reset-password';
        return $this->sendClientMailWithToken($subject, $text, $link);
    }

    public function sendClientMailWithToken($subject, $text, $link) {
        $token   = $this->generateToken();
        $obj     = $this->edit(array('token' => $token, 'token_time_generation' => time()));
        $link    = APP_URL . $link . '?token=' . $obj->token;
        $text   .= ' ' . $link;
        $text   .= " \n\n\nSportalize.com";
        return SportalizeMail::sendMail($obj, $subject, $text);
    }

    public static function getUserByToken($token) {
        return Main::fetchRow(Main::select('User')->where('token = ?', $token));
    }

    public static function getUserByEmail($email) {
        return Main::fetchRow(Main::select('User')->where('email = ?', $email));
    }

    public function activate() {
        $translate = Zend_Registry::getInstance()->Zend_Translate;
        $return    = array('status' => 0, 'message' => '');

        if ($this->activated) {
            $return['message'] = $translate->_('User is already activated.');
        } else {
            $tokenExpired = $this->hasTokenExpired();
            if ($tokenExpired) {
                $link = '<a href="' . APP_URL . '/login/index/get-new-token?userID=' . $this->id . '&trigger=register">';
                $return['message'] = vsprintf($translate->_('Link expired. Please click %shere%s to resend confirmation email with new token.'), array($link, '</a>'));
            } else {
                $return['status'] = 1;
                $this->edit(array('activated' => 1));
            }
        }
        return $return;
    }

    public function hasTokenExpired() {
        $tokenTimeGeneration = new Zend_Date($this->token_time_generation, Zend_Date::TIMESTAMP);
        $now                 = Zend_Date::now();
        $diff                = Utils::getDateDiffInSeconds($now, $tokenTimeGeneration);
        return $diff > self::MAX_LINK_DURATION;
    }

    public static function login($email, $password, $encrypt = false) {
        $values  = array("email" => $email, "password" => $password, 'encrypt' => $encrypt);
        $adapter = self::getAuthAdapter($values); 
        $auth    = Zend_Auth::getInstance();
        $result  = $auth->authenticate($adapter);

        $user    = User::getUserByEmail($email);

        $return  = false;
        if ($user->activated) {
            if ($result->isValid()) {
                $storage  = $auth->getStorage();
                $storage->write($adapter->getResultRowObject(array('email', 'password')));
                $return   = true;
                $elephant = new ElephantConnect(array('userID' => $user->id));
                $elephant->initializePersonOnline();
            }
        }
        return $return;
    }

    public function loginUser() {
        return User::login($this->email, $this->password);
    }

    public static function getAuthAdapter($params) {
        $registry    = Zend_Registry::getInstance();
        $auth        = Zend_Auth::getInstance();
        $dbAdapter   = Zend_Registry::getInstance()->dbAdapter;
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter->setTableName('user')
                    ->setIdentityColumn('email')
                    ->setCredentialColumn('password');
        // Set the input credential values
        
        $password = $params['encrypt'] ?
                    Utils::encrypt($params['password']) :
                    $params['password'];
        $authAdapter->setIdentity($params['email']);
        $authAdapter->setCredential($password);
        $res = $auth->authenticate($authAdapter);
        if (isset($_POST['rememberme']) && $_POST['rememberme'] == "on") {
            Zend_Session::rememberMe();
        }
        return $authAdapter;
    }

    public static function create($data, $tableName = false) {
        $data['password'] = Utils::encrypt($data['password']);
        return parent::create($data, $tableName);
    }

    public function getFriendList($includeSelf = true, $idOnly = false) {
        $friends = Main::select()
                    ->from(array('UU' => 'user_user'), '')
                    ->where('UU.user_id = ?', $this->id)
                    ->orWhere('UU.friend_id = ?', $this->id)
                    ->columns(array('UU.user_id', 'UU.friend_id'))
                    ->query()->fetchAll();

        $filtered = array();
        foreach ($friends as $key => $value) {
            $filtered[] = $value['user_id'] == $this->id ? $value['friend_id'] : $value['user_id'];
        }
        if ($includeSelf) {
            $filtered[] = $this->id;
        }
        if ($idOnly) {
            return $filtered;
        }
        $allFriends = Main::select()
                    ->from(array('U' => 'user'), '')
                    ->where('id IN (?)', $filtered)
                    ->columns(array('U.id', 'U.first_name', 'U.last_name'))
                    ->query()->fetchAll();
        return $allFriends;
    }

    public function getPageList($idOnly = false) {
        $pages = Main::select()
                ->from(array('UP' => 'user_page'), '')
                ->where('UP.user_id = ?', $this->id)
                ->columns(array('UP.page_id'))
                ->query()->fetchAll();

        $pgs = array();
        foreach ($pages as $key => $value) {
            $pgs[] = $value['page_id'];
        }
        if ($idOnly) {
            return $pgs;
        }

        $allPages = Main::select()
                   ->from(array('PA' => 'page'), '')
                   ->where('id IN (?)', $pgs)
                   ->columns(array('PA.id', 'PA.title', 'PA.logo'))
                   ->query()->fetchAll();

        return $allPages;
    }

    public static function getColumnsToBeFetched() {
        return array('PO.title as post_title', 'PO.text as post_text', 'PO.id as post_id', 'PO.date as post_date', 'CO1.id',
                     'CO1.text as comment_text', 'CO1.date as comment_date', 'CO1.id as comment_id', 'CO1.ulid as user_like',
                     'CO1.parent_comment_id as parent_comment_id', 'CO1.forwarded', 'CO1.likes',
                     'CO1.first_name as commenter_first_name', 'CO1.last_name as commenter_last_name', 'PO.post_type as post_type',
                     'PO.video as post_video', 'PO.image as post_image',
                    );
    }

    public function getFriendsAndPagePosts($page = 1) {
        $friendIDS = $this->getFriendList(false, true);
        $pageIDS   = $this->getPageList(true);
        $limit     = Comment::COMMENT_SHOW_LIMIT;
        try {
            
            // for more reference visit http://frishit.com/2010/07/mysql-selecting-top-n-per-group/

            $commentColumnsToBeFetched = User::getColumnsToBeFetched();
            $subQuery = Main::select()
                        ->from(array('CO2' => 'comment'), '')
                        ->where('CO2.commented_post_id = PO.id AND CO1.id > CO2.id')
                        ->columns(array('COUNT(*)'));

            $implodedFriend = implode(',', $friendIDS);
            $implodedPage   = implode(',', $pageIDS);

            $subQueryForCount = Main::select()
                        ->from(array('CO3' => 'comment'), 'COUNT(*)')
                        ->join(array('PO3' => 'post'), 'CO3.commented_post_id = PO3.id', '')
                        ->where("PO3.user_id IN (" . $implodedFriend . ") OR PO3.page_id IN (" . $implodedPage . ")")
                        ->where('CO3.commented_post_id = PO.id');
            
            $commentSelect = Main::select()
                ->from(array('CO1' => 'comment'), '')
                ->join(array('PO' => 'post'), 'CO1.commented_post_id = PO.id', '')
                ->joinLeft(array('UL' => 'user_like'), 'UL.comment_id = CO1.id AND UL.user_id = ' .  $this->id, '')
                ->joinLeft(array('US' => 'user'), 'CO1.commenter_id = US.id', '')
                ->where("
                    (($subQueryForCount 
                    ) - " . (Comment::COMMENT_SHOW_LIMIT + 1) . ")
                 < ($subQuery)")
                ->where('PO.user_id IN (?)', $friendIDS)
                ->orWhere('PO.page_id IN (?)', $pageIDS)
                ->group(array('CO1.commented_post_id', 'CO1.id'))
                ->order(array('CO1.commented_post_id', 'CO1.date DESC', 'CO1.id DESC', ))
                ->columns(array('CO1.id', 'CO1.text', 'CO1.commented_post_id', 'CO1.date',
                                'UL.id as ulid', 'CO1.parent_comment_id', 'CO1.forwarded',
                                'CO1.likes', 'US.first_name', 'US.last_name'));
                // fb("$commentSelect");
            
            // @todo Implement that when user registers, automatically favourited system page, and have one post with comment (as a welcome)
             $select1 = Main::select()
                        ->from(array('PO' => 'post'), '')
                        ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
                        ->where('PO.user_id IN (?)', $friendIDS)
                        ->columns($commentColumnsToBeFetched);

            $select2 = false;
            if (!empty($pageIDS)) {
                $select2 = Main::select()
                            ->from(array('PO' => 'post'), '')
                            ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
                            ->where('PO.page_id IN (?)', $pageIDS)
                            ->columns($commentColumnsToBeFetched);
            }

            $union = Main::select()->union(array($select1, $select2));

            $mainSelect = Main::select()
                        ->from($union, '')
                        ->columns(array('post_title', 'post_text', 'post_id', 'post_date', 'post_type', 'post_video', 'post_image',
                                        'comment_text', 'comment_date', 'comment_id', 
                                        'commenter_first_name', 'commenter_last_name', 'likes', 'forwarded', 'user_like',
                                        'parent_comment_id',
                                    ))
                        ->order(array('post_date DESC', 'comment_date DESC'))
                        ->query()->fetchAll();

            $return = array();
            foreach ($mainSelect as $key => $onePost) {
                // fb($onePost);
                $return[$onePost['post_id']]['post_id'] = $onePost['post_id'];
                $return[$onePost['post_id']]['title']   = $onePost['post_title'];
                $return[$onePost['post_id']]['text']    = $onePost['post_text'];
                $return[$onePost['post_id']]['date']    = $onePost['post_date'];
                $return[$onePost['post_id']]['type']    = $onePost['post_type'];
                $return[$onePost['post_id']]['video']   = $onePost['post_video'];
                $return[$onePost['post_id']]['image']   = $onePost['post_image'];
                if (!empty($onePost['comment_id'])) {
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['comment_id']        = $onePost['comment_id'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['text']              = $onePost['comment_text'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['parent_comment_id'] = $onePost['parent_comment_id'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['forwarded']         = $onePost['forwarded'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['likes']             = $onePost['likes'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['date']              = $onePost['comment_date'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['user_like']         = $onePost['user_like'];
                }
            }

            $paginator = Zend_Paginator::factory($return);
            $paginator->setCurrentPageNumber($page);
            $paginator->setItemCountPerPage(User::INDEX_PAGE_POST_LIMIT);
            // return false;
            return $paginator;
        } catch(Exception $e) {
            fb($e->getMessage());
        }
    }

    public function getPostsByUser($page = 1) {

        $commentColumnsToBeFetched = User::getColumnsToBeFetched();
        $subQuery = Main::select()
                    ->from(array('CO2' => 'comment'), '')
                    ->where('CO2.commented_post_id = PO.id AND CO1.id > CO2.id')
                    ->columns(array('COUNT(*)'));

        $subQueryForCount = Main::select()
                            ->from(array('CO3' => 'comment'), 'COUNT(*)')
                            ->join(array('PO3' => 'post'), 'CO3.commented_post_id = PO3.id', '')
                            ->where("PO3.user_id = ?", $this->id)
                            ->where('CO3.commented_post_id = PO.id');

        $commentSelect = Main::select()
            ->from(array('CO1' => 'comment'), '')
            ->join(array('PO' => 'post'), 'CO1.commented_post_id = PO.id', '')
            ->joinLeft(array('UL' => 'user_like'), 'UL.comment_id = CO1.id AND UL.user_id = ' .  $this->id, '')
            ->joinLeft(array('US' => 'user'), 'CO1.commenter_id = US.id', '')
            ->where("
                (($subQueryForCount 
                ) - " . (Comment::COMMENT_SHOW_LIMIT + 1) . ")
             < ($subQuery)")
            ->where('PO.user_id = ?', $this->id)
            ->group(array('CO1.commented_post_id', 'CO1.id'))
            ->order(array('CO1.commented_post_id', 'CO1.date DESC', 'CO1.id DESC', ))
            ->columns(array('CO1.id', 'CO1.text', 'CO1.commented_post_id', 'CO1.date',
                            'UL.id as ulid', 'CO1.parent_comment_id', 'CO1.forwarded',
                            'CO1.likes', 'US.first_name', 'US.last_name'));

        
        $posts = Main::select()
                    ->from(array('PO' => 'post'), '')
                    ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
                    ->where('PO.user_id = ?', $this->id)
                    ->columns($commentColumnsToBeFetched)
                    ->query()->fetchAll();

        $return = array();
        foreach ($posts as $key => $onePost) {
            // fb($onePost);
            $return[$onePost['post_id']]['post_id'] = $onePost['post_id'];
            $return[$onePost['post_id']]['title']   = $onePost['post_title'];
            $return[$onePost['post_id']]['text']    = $onePost['post_text'];
            $return[$onePost['post_id']]['date']    = $onePost['post_date'];
            $return[$onePost['post_id']]['type']    = $onePost['post_type'];
            $return[$onePost['post_id']]['video']   = $onePost['post_video'];
            $return[$onePost['post_id']]['image']   = $onePost['post_image'];
            if (!empty($onePost['comment_id'])) {
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['comment_id']        = $onePost['comment_id'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['text']              = $onePost['comment_text'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['parent_comment_id'] = $onePost['parent_comment_id'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['forwarded']         = $onePost['forwarded'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['likes']             = $onePost['likes'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['date']              = $onePost['comment_date'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['user_like']         = $onePost['user_like'];
            }
        }        

        // fb($return);
        $paginator = Zend_Paginator::factory($return);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(User::INDEX_PAGE_POST_LIMIT);
        // return false;
        return $paginator;
    }

    public function getDreamTeams() {
        return Main::select()
                ->from(array('DT' => 'dream_team'), '')
                ->where('DT.user_id = ?', $this->id)
                ->columns(array('DT.name', 'DT.data'))
                ->query()->fetchAll();
    }

    public function getFavouriteSports() {
        return Main::select()
            ->from(array('US' => 'user_sport'), 'US.sport_id')
            ->where('US.user_id = ?', $this->id)
            ->query()->fetchAll(Zend_Db::FETCH_COLUMN, 0);
    }

    public function getFavouritePlayersWithPage() {
        return $this->getFavouriteItemsWithPage(Page::PAGE_TYPE_PLAYER);
    }

    public function getFavouriteTeamsWithPage() {
        return $this->getFavouriteItemsWithPage(Page::PAGE_TYPE_TEAM);
    }

    public function getFavouriteItemsWithPage($type) {
        return Main::select()
            ->from(array('FI' => 'favourite_item'), '')
            ->join(array('PA' => 'page'), 'FI.page_id = PA.id', '')
            ->where('FI.user_id = ?', $this->id)
            ->where('FI.page_id IS NOT NULL')
            ->where('PA.type = ?', $type)
            ->columns(array('PA.id'))
            ->query()->fetchAll(Zend_Db::FETCH_COLUMN, 0);
    }

    public function getFavouritePlayersAndTeamsWithoutPage() {
        return Main::select()
            ->from(array('FI' => 'favourite_item'), '')
            ->where('FI.user_id = ?', $this->id)
            ->where('FI.type = "' . FavouriteItem::FAVOURITE_ITEM_TYPE_PLAYER . '" OR FI.type = "' . FavouriteItem::FAVOURITE_ITEM_TYPE_TEAM . '"')
            ->columns(array('FI.name', 'FI.type'))
            ->query()->fetchAll();
    }

    public function updateFavouritesAndDreamTeam($data) {
        $cache = Zend_Registry::get('cache');
        // In order to easy unset it from array, make category as key. We want to unset it because, if nothing is
        // selected in form, that form key will not be sent. So after all regular checks, we need to see if there
        // is something left in favouritesArray. If there is something, that means that it is not sent from from and
        // that is should be removed completely from db
        $favouritesArray = array('favourite_sport'   => 1,
                                 'players'           => 1,
                                 'teams'             => 1,
                                 'available_players' => 1,
                                 'available_teams'   => 1);
        foreach ($data['personal_settings']['favourites'] as $key => $value) {
            unset($favouritesArray[$key]);
            $cacheKey   = $key . '_' . $this->id;
            $serialized = serialize($value);
            $compared   = true;
            $toCompare  = '';
            if (!$cache->test($cacheKey)) {
                $cache->save($serialized, $cacheKey);
                $compared = false;
            } else {
                $toCompare = $cache->load($cacheKey);
                if ($serialized != $toCompare) {
                    $compared = false;
                    $cache->remove($cacheKey);
                    $cache->save($serialized, $cacheKey);
                }
            }
            if (!$compared && $key == 'favourite_sport') {
                Main::execQuery('DELETE FROM `user_sport` WHERE `user_id` = ?', $this->id);
                foreach ($value as $v) {
                    Main::execQuery("INSERT INTO `user_sport` (`user_id`, `sport_id`) VALUES ('$this->id', '$v')");
                }
            }
            if (!$compared && ($key == 'players' || $key == 'teams')) {
                $type   = $key == 'players' ? FavouriteItem::FAVOURITE_ITEM_TYPE_PLAYER : FavouriteItem::FAVOURITE_ITEM_TYPE_TEAM;
                Main::execQuery('DELETE FROM `favourite_item` WHERE `user_id` = ? AND `page_id` IS NULL AND `type` = ?', array($this->id, $type));
                foreach ($value as $v) {
                    if (!empty($v)) {
                        Main::execQuery("INSERT INTO `favourite_item` (`user_id`, `name`, `type`) VALUES ('$this->id', '$v', '$type')");
                    }
                }
            }
            if (!$compared && ($key == 'available_players' || $key == 'available_teams')) {
                $type = $key == 'players' ? Page::PAGE_TYPE_PLAYER : Page::PAGE_TYPE_TEAM;
                Main::execQuery('DELETE FA FROM `favourite_item` FA JOIN page PA on FA.page_id = PA.id WHERE FA.`user_id` = ? AND `page_id` IS NOT NULL AND PA.type = ?', array($this->id, $type));
                foreach ($value as $v) {
                    if (!empty($v)) {
                        Main::execQuery("INSERT INTO `favourite_item` (`user_id`, `page_id`) VALUES ('$this->id', '$v')");
                    }
                }
            }
        }
        foreach ($favouritesArray as $key => $value) {
            if ($key == 'players' || $key == 'teams') {
                $type   = $key == 'players' ? FavouriteItem::FAVOURITE_ITEM_TYPE_PLAYER : FavouriteItem::FAVOURITE_ITEM_TYPE_TEAM;
                Main::execQuery('DELETE FROM `favourite_item` WHERE `user_id` = ? AND `page_id` IS NULL AND `type` = ?', array($this->id, $type));
            }
            if ($key == 'available_players' || $key == 'available_teams') {
                $type = $key == 'players' ? Page::PAGE_TYPE_PLAYER : Page::PAGE_TYPE_TEAM;
                Main::execQuery('DELETE FA FROM `favourite_item` FA JOIN page PA on FA.page_id = PA.id WHERE FA.`user_id` = ? AND `page_id` IS NOT NULL AND PA.type = ?', array($this->id, $type));
            }
            $cache->remove($cacheKey);
        }

        $serialized = serialize($data['personal_settings']['dream_team']);
        $cacheKey   = 'dream_teams_' . $this->id;
        if (!$cache->test($cacheKey)) {
            $cache->save($serialized, $cacheKey);
            $compared = false;
        } else {
            $toCompare = $cache->load($cacheKey);
            if ($serialized != $toCompare) {
                $compared = false;
                $cache->remove($cacheKey);
                $cache->save($serialized, $cacheKey);
            }
        }
        if (!$compared) {
            Main::execQuery("DELETE FROM dream_team WHERE user_id = ?", $this->id);
            foreach ($data['personal_settings']['dream_team'] as $key => $value) {
                $name = $value['name'];
                $data = Zend_Json::encode($value['data']);
                Main::execQuery("INSERT INTO `dream_team` (`user_id`, `name`, `data`) VALUES ('$this->id', '$name', '$data');");
            }
        }
    }
}