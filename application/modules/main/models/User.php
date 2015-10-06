<?php
require_once 'User/Row.php';

class User extends User_Row
{
    const MAX_LINK_DURATION     = 600;
    const INDEX_PAGE_POST_LIMIT = 10;

    const FRIEND_STATUS_PENDING  = 'PENDING';
    const FRIEND_STATUS_ACCEPTED = 'ACCEPTED';

    const SPORTALIZE_USER_ID     = 1;

    const MAX_OLD_NOTIFICATIONS  = 5;

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
        $user             = parent::create($data, $tableName);
        PrivacySetting::createDefaultPrivacySetting($user->id);

        UserPage::create(array(
            'user_id' => $user->id,
            'page_id' => Page::SPORTALIZE_PAGE_ID,
        ));
        UserUser::create(array(
            'user_id'   => $user->id,
            'friend_id' => self::SPORTALIZE_USER_ID,
            'status'    => self::FRIEND_STATUS_ACCEPTED
        ));
        UserInfo::create(array(
            'user_id'     => $user->id,
            'country_id'  => 1,
            'language_id' => 1,
            'role_id'     => 2,
            'avatar'      => UserInfo::DEFAULT_AVATAR,
            'big_logo'    => UserInfo::DEFAULT_BIG_LOGO,
        ));

        Widget::createDefaultUserWidget($user->id);
        
        return $user;
    }

    public function getFriendList($includeSelf = true, $idOnly = false) {
        $friends = Main::select()
                    ->from(array('UU' => 'user_user'), '')
                    ->where('UU.user_id = ?', $this->id)
                    ->where('UU.status = ?', self::FRIEND_STATUS_ACCEPTED)
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
                    ->join(array('UI' => 'user_info'), 'UI.user_id = U.id', '')
                    ->where('U.id IN (?)', $filtered)
                    ->columns(array('U.id', 'UI.first_name', 'UI.last_name', 'UI.avatar'))
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
                   ->join(array('UP' => 'user_page'), 'PA.id = UP.page_id', '')
                   ->where('UP.user_id = ?', $this->id)
                   ->where('PA.id IN (?)', $pgs)
                   ->columns(array('PA.id', 'PA.title', 'PA.logo', 'UP.show_in_favorite_pages_widget'))
                   ->query()->fetchAll();

        return $allPages;
    }

    public static function getColumnsToBeFetched($includeUlid = true) {
        $return =  array('PO.title as post_title', 'PO.text as post_text', 'PO.id as post_id', 'PO.date as post_date', 'CO1.id',
                     'CO1.text as comment_text', 'CO1.date as comment_date', 'CO1.id as comment_id',
                     'CO1.parent_comment_id as parent_comment_id', 'CO1.forwarded', 'CO1.likes',
                     'CO1.first_name as commenter_first_name', 'CO1.last_name as commenter_last_name', 'PO.post_type as post_type',
                     'PO.video as post_video', 'PO.image as post_image', 'UL.id as post_like', 'UF.id as post_favorite', 'CO1.comment_favorite',
                     'PO.user_id', 'PO.page_id', 'CO1.reply_to_name', 'CO1.reply_to_id', 'CO1.avatar', 'CO1.commenter_id'
                    );
        if ($includeUlid) {
            $return[] = 'CO1.ulid as user_like';
        }
        return $return;
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
                ->joinLeft(array('UF' => 'user_favorite'), 'UF.comment_id = CO1.id AND UF.user_id = ' .  $this->id, '')
                ->joinLeft(array('US' => 'user'), 'CO1.commenter_id = US.id', '')
                ->joinLeft(array('UI' => 'user_info'), 'UI.user_id = US.id', '')
                ->joinLeft(array('CO2' => 'comment'), 'CO1.parent_comment_id = CO2.id', '')
                ->joinLeft(array('UI2' => 'user_info'), 'UI2.user_id = CO2.commenter_id', '')
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
                                'CO1.likes', 'UI.first_name', 'UI.last_name', 'UF.id as comment_favorite',
                                'CONCAT(UI2.first_name, " ", UI2.last_name) as reply_to_name', 'CO2.commenter_id as reply_to_id',
                                'UI.avatar', 'UI.user_id as commenter_id'));
                // fb("$commentSelect");
            $postFromFriendsArray = $commentColumnsToBeFetched;
            $postFromFriendsArray[] = 'CONCAT(UI5.first_name, " ", UI5.last_name) as post_author';
            // $postFromFriendsArray[] = 'CONCAT(UI6.first_name, " ", UI6.last_name) as original_post_author';
            $postFromFriendsArray[] = 'COALESCE(CONCAT(UI6.first_name, " ", UI6.last_name),PAG2.title) AS original_post_author';
            $postFromFriendsArray[] = 'PO.original_user_id';
            $postFromFriendsArray[] = 'PO.original_page_id';
            $select1 = Main::select()
                        ->from(array('PO' => 'post'), '')
                        ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
                        ->joinLeft(array('UL' => 'user_like'), 'UL.post_id = PO.id AND UL.user_id = ' . $this->id, '')
                        ->joinLeft(array('UL3' => 'user_like'), 'UL3.post_id = PO.id', '')
                        ->joinLeft(array('UF' => 'user_favorite'), 'UF.post_id = PO.id', '')
                        ->joinLeft(array('UI5' => 'user_info'), 'UI5.user_id = PO.user_id', '')
                        ->joinLeft(array('UI6' => 'user_info'), 'UI6.user_id = PO.original_user_id', '')
                        ->joinLeft(array('PAG2' => 'page'), 'PO.original_page_id = PAG2.id ', '')
                        ->where('PO.user_id IN (?)', $friendIDS)
                        ->columns($postFromFriendsArray);

            $select2 = false;
            if (!empty($pageIDS)) {
                $postFromPagesArray = $commentColumnsToBeFetched;
                $postFromPagesArray[] = 'PAG.title as post_author';
                $postFromPagesArray[] = 'PAG2.title as original_post_author';
                $postFromPagesArray[] = 'PO.original_user_id';
                $postFromPagesArray[] = 'PO.original_page_id';
                $select2 = Main::select()
                            ->from(array('PO' => 'post'), '')
                            ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
                            ->joinLeft(array('UL' => 'user_like'), 'UL.post_id = PO.id AND UL.user_id = ' . $this->id, '')
                            ->joinLeft(array('UL3' => 'user_like'), 'UL3.post_id = PO.id', '')
                            ->joinLeft(array('UF' => 'user_favorite'), 'UF.post_id = PO.id', '')
                            ->joinLeft(array('PAG' => 'page'), 'PO.page_id = PAG.id ', '')
                            ->joinLeft(array('PAG2' => 'page'), 'PO.original_page_id = PAG2.id ', '')
                            ->where('PO.page_id IN (?)', $pageIDS)
                            ->columns($postFromPagesArray);
            }

            $union = Main::select()->union(array($select1, $select2));

            $mainSelect = Main::select()
                        ->from($union, '')
                        ->columns(array('post_title', 'post_text', 'post_id', 'post_date', 'post_type', 'post_video', 'post_image',
                                        'comment_text', 'comment_date', 'comment_id', 
                                        'commenter_first_name', 'commenter_last_name', 'likes', 'forwarded', 'user_like',
                                        'parent_comment_id', 'post_like', 'post_favorite', 'comment_favorite', 'user_id', 'page_id',
                                        'post_author', 'original_post_author', 'original_user_id', 'original_page_id', 'reply_to_id',
                                        'reply_to_name', 'avatar', 'commenter_id'
                                    ))
                        ->order(array('post_date DESC', 'comment_date DESC'))
                        ->query()->fetchAll();

            $return = array();
            foreach ($mainSelect as $key => $onePost) {
                // fb($onePost);
                $return[$onePost['post_id']]['post_id']              = $onePost['post_id'];
                $return[$onePost['post_id']]['title']                = $onePost['post_title'];
                $return[$onePost['post_id']]['text']                 = $onePost['post_text'];
                $return[$onePost['post_id']]['date']                 = $onePost['post_date'];
                $return[$onePost['post_id']]['type']                 = $onePost['post_type'];
                $return[$onePost['post_id']]['video']                = $onePost['post_video'];
                $return[$onePost['post_id']]['image']                = $onePost['post_image'];
                $return[$onePost['post_id']]['post_like']            = $onePost['post_like'];
                $return[$onePost['post_id']]['post_favorite']        = $onePost['post_favorite'];
                $return[$onePost['post_id']]['user_id']              = $onePost['user_id'];
                $return[$onePost['post_id']]['page_id']              = $onePost['page_id'];
                $return[$onePost['post_id']]['post_author']          = $onePost['post_author'];
                $return[$onePost['post_id']]['original_post_author'] = $onePost['original_post_author'];
                $return[$onePost['post_id']]['original_user_id']     = $onePost['original_user_id'];
                $return[$onePost['post_id']]['original_page_id']     = $onePost['original_page_id'];
                if (!empty($onePost['comment_id'])) {
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['comment_id']        = $onePost['comment_id'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['text']              = $onePost['comment_text'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['parent_comment_id'] = $onePost['parent_comment_id'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['forwarded']         = $onePost['forwarded'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['likes']             = $onePost['likes'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['date']              = $onePost['comment_date'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['user_like']         = $onePost['user_like'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['comment_favorite']  = $onePost['comment_favorite'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['reply_to_name']     = $onePost['reply_to_name'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['reply_to_id']       = $onePost['reply_to_id'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['avatar']            = $onePost['avatar'];
                    $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['commenter_id']      = $onePost['commenter_id'];
                }
            }
            // fb($return);
            $paginator = Zend_Paginator::factory($return);
            $paginator->setCurrentPageNumber($page);
            $paginator->setItemCountPerPage(User::INDEX_PAGE_POST_LIMIT);
            // return false;
            return $paginator;
        } catch(Exception $e) {
            fb($e->getMessage());
        }
    }

    public function getPostsByUser($page = 1, $watcherID) {
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
            ->joinLeft(array('UL' => 'user_like'), 'UL.comment_id = CO1.id AND UL.user_id = ' .  $watcherID, '')
            ->joinLeft(array('UF' => 'user_favorite'), 'UF.comment_id = CO1.id AND UF.user_id = ' .  $watcherID, '')
            ->joinLeft(array('US' => 'user'), 'CO1.commenter_id = US.id', '')
            ->joinLeft(array('UI' => 'user_info'), 'UI.user_id = US.id', '')
            ->joinLeft(array('CO2' => 'comment'), 'CO1.parent_comment_id = CO2.id', '')
            ->joinLeft(array('UI2' => 'user_info'), 'UI2.user_id = CO2.commenter_id', '')
            ->where("
                (($subQueryForCount 
                ) - " . (Comment::COMMENT_SHOW_LIMIT + 1) . ")
             < ($subQuery)")
            ->where('PO.user_id = ?', $this->id)
            ->group(array('CO1.commented_post_id', 'CO1.id'))
            ->order(array('CO1.commented_post_id', 'CO1.date DESC', 'CO1.id DESC', ))
            ->columns(array('CO1.id', 'CO1.text', 'CO1.commented_post_id', 'CO1.date',
                            'UL.id as ulid', 'CO1.parent_comment_id', 'CO1.forwarded',
                            'CO1.likes', 'UI.first_name', 'UI.last_name', 'UF.id as comment_favorite',
                            'CONCAT(UI2.first_name, " ", UI2.last_name) as reply_to_name', 'CO2.commenter_id as reply_to_id',
                            'UI.avatar', 'UI.user_id as commenter_id'
                            ));

        $postFromFriendsArray   = $commentColumnsToBeFetched;
        $postFromFriendsArray[] = 'CONCAT(UI5.first_name, " ", UI5.last_name) as post_author';
        $postFromFriendsArray[] = 'COALESCE(CONCAT(UI6.first_name, " ", UI6.last_name),PAG2.title) AS original_post_author';
        $postFromFriendsArray[] = 'PO.original_user_id';
        $postFromFriendsArray[] = 'PO.original_page_id';
        $posts = Main::select()
                    ->from(array('PO' => 'post'), '')
                    ->joinLeft(array('CO1' => new Zend_Db_Expr("($commentSelect)")), 'CO1.commented_post_id = PO.id', '')
                    ->joinLeft(array('UL' => 'user_like'), 'UL.post_id = PO.id AND UL.user_id = ' .  $watcherID, '')
                    ->joinLeft(array('UF' => 'user_favorite'), 'UF.post_id = PO.id', '')
                    ->joinLeft(array('UI5' => 'user_info'), 'UI5.user_id = PO.user_id', '')
                    ->joinLeft(array('UI6' => 'user_info'), 'UI6.user_id = PO.original_user_id', '')
                    ->joinLeft(array('PAG2' => 'page'), 'PO.original_page_id = PAG2.id ', '')
                    ->where('PO.user_id = ?', $this->id)
                    ->columns($postFromFriendsArray)
                    ->query()->fetchAll();

        $return = array();
        foreach ($posts as $key => $onePost) {
            // fb($onePost);
            $return[$onePost['post_id']]['post_id']              = $onePost['post_id'];
            $return[$onePost['post_id']]['title']                = $onePost['post_title'];
            $return[$onePost['post_id']]['text']                 = $onePost['post_text'];
            $return[$onePost['post_id']]['date']                 = $onePost['post_date'];
            $return[$onePost['post_id']]['type']                 = $onePost['post_type'];
            $return[$onePost['post_id']]['video']                = $onePost['post_video'];
            $return[$onePost['post_id']]['image']                = $onePost['post_image'];
            $return[$onePost['post_id']]['post_like']            = $onePost['post_like'];
            $return[$onePost['post_id']]['user_id']              = $onePost['user_id'];
            $return[$onePost['post_id']]['page_id']              = $onePost['page_id'];
            $return[$onePost['post_id']]['post_author']          = $onePost['post_author'];
            $return[$onePost['post_id']]['original_post_author'] = $onePost['original_post_author'];
            $return[$onePost['post_id']]['original_user_id']     = $onePost['original_user_id'];
            $return[$onePost['post_id']]['original_page_id']     = $onePost['original_page_id'];
            if (!empty($onePost['comment_id'])) {
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['comment_id']        = $onePost['comment_id'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['text']              = $onePost['comment_text'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['parent_comment_id'] = $onePost['parent_comment_id'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['forwarded']         = $onePost['forwarded'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['likes']             = $onePost['likes'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['date']              = $onePost['comment_date'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['user_like']         = $onePost['user_like'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['reply_to_name']     = $onePost['reply_to_name'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['reply_to_id']       = $onePost['reply_to_id'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['avatar']            = $onePost['avatar'];
                $return[$onePost['post_id']]['comments'][$onePost['comment_id']]['commenter_id']      = $onePost['commenter_id'];
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

    public function getFavouriteSports($withPageNames = false) {
        $select =  Main::select()
                ->from(array('US' => 'user_sport'), 'US.sport_id')
                ->where('US.user_id = ?', $this->id);
        $return;
        if ($withPageNames) {
            $return = $select->join(array('SP' => 'sport'), 'US.sport_id = SP.id')
                             ->columns(array('US.sport_id', 'SP.name'))
                             ->query()->fetchAll();
        } else {
            $return = $select->query()->fetchAll(Zend_Db::FETCH_COLUMN, 0);
        }
        return $return;
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

    public function getFavoritePlayersForView() {
        $playersWithPage    = $this->getFavouritePlayersWithPage();
        $playersWithoutPage = $this->getFavouritePlayersAndTeamsWithoutPage();
        $return = array();
        $count = 0;
        foreach ($playersWithPage as $onePlayerWithPage) {
            $page = Main::buildObject('Page', $onePlayerWithPage);
            $return[$count]['page_id'] = $onePlayerWithPage;
            $return[$count]['name']    = $page->title;
            $count++;
        }
        foreach ($playersWithoutPage as $onePlayerWithoutPage) {
            if ($onePlayerWithoutPage['type'] == FavouriteItem::FAVOURITE_ITEM_TYPE_PLAYER) {
                $return[$count]['name'] = $onePlayerWithoutPage['name'];
                $count++;
            }
        }
        return $return;
    }

    public function getFavoriteTeamsForView() {
        $teamsWithPage   = $this->getFavouriteTeamsWithPage();
        $teamWithoutPage = $this->getFavouritePlayersAndTeamsWithoutPage();
        $return = array();
        $count = 0;
        foreach ($teamsWithPage as $oneTeamWithPage) {
            $page = Main::buildObject('Page', $oneTeamWithPage);
            $return[$count]['page_id'] = $oneTeamWithPage;
            $return[$count]['name']    = $page->title;
            $count++;
        }
        foreach ($teamWithoutPage as $oneTeamWithoutPage) {
            if ($oneTeamWithoutPage['type'] == FavouriteItem::FAVOURITE_ITEM_TYPE_TEAM) {
                $return[$count]['name'] = $oneTeamWithoutPage['name'];
                $count++;
            }
        }
        return $return;
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

    public function updatePersonalInfo($params) {
        $userInfo = $this->getUserInfo();
        if ($userInfo) {
            if (!empty($params['date_of_birth'])) {
                $locale                  = Zend_Registry::get('Zend_Locale');
                $date                    = new Zend_Date($params['date_of_birth'], Zend_Date::DATE_SHORT, $locale);
                $params['date_of_birth'] = $date->get(Zend_Date::TIMESTAMP);
                $userInfo->edit($params);
            }
            $fileName = Utils::uploadFile('avatar', UserInfo::AVATAR_IMAGES_FOLDER, $this->id);
            if ($fileName) {
                $userInfo->edit(array('avatar' => $fileName));
            }
        }
    }

    public function getFriendGroups() {
        $list =  Main::select()
                ->from(array('FL' => 'friend_list'))
                ->where('user_id = ?', $this->id)
                ->orWhere('is_system = ?', 1)
                ->columns(array('FL.id', 'FL.title'))
                ->query()->fetchAll();
        $return = array();
        foreach ($list as $key => $value) {
            if (in_array($value['title'], FriendList::getSystemGroupsArray())) {
                $value['title'] = FriendList::getTranslated($value['title']);
            }
            $return[$value['id']] = $value['title'];
        }
        return $return;
    }

    public function updatePrivacySettings($params) {
        $array = array('personal_info_visibility' => PrivacySetting::PRIVACY_TYPE_PROFILE,
                       'post_visibility'          => PrivacySetting::PRIVACY_TYPE_POST,
                       'galery_visibility'        => PrivacySetting::PRIVACY_TYPE_GALERY,
                       );
        foreach ($params as $key => $value) {
            $setting = Main::fetchRow(Main::select('PrivacySetting')
                            ->where('user_id = ?', $this->id)
                            ->where('type = ?', $array[$key]));
            $data = array();
            $data['setting'] = $value['visibility'];
            $data['options'] = '';

            $keyToFetch = '';
            if ($value['visibility'] == PrivacySetting::PRIVACY_SETTING_SPECIFIC_LISTS) {
                $keyToFetch = 'friend_list';
            }
            if ($value['visibility'] == PrivacySetting::PRIVACY_SETTING_SPECIFIC_FRIENDS) {
                $keyToFetch = 'specific_friends';
            }
            $options = Utils::arrayFetch($value, $keyToFetch, false);
            if ($options) {
               $data['options'] = Zend_Json::encode($options);
            }
            $setting->edit($data);
        }
    }

    public function getUserPages() {
        $pages = Main::select()
                ->from(array('PA' => 'page'), '')
                ->where('PA.user_id = ?', $this->id)
                ->columns(array('PA.id', 'PA.title'))
                ->query()->fetchAll();

        $return = array();
        foreach ($pages as $key => $value) {
            $return[$value['id']] = $value['title'];
        }
        return $return;
    }

    public function updateOtherData($data) {
        $userInfo = $this->getUserInfo();
        $userInfo->edit(array('language_id' => $data['language']));
    }

    public function getUserInfo() {
        return Main::fetchRow(Main::select('UserInfo')->where('user_id = ?', $this->id));
    }

    public function getLanguage() {
        $userInfo = $this->getUserInfo();
        return Main::fetchRow(Main::select('Language')->where('id = ?', $userInfo->language_id));
    }

    public function reconfigureWidgets($params) {
        foreach ($params as $widgetID => $placement) {
            $widget = Main::fetchRow(Main::select('UserWidget')->where('widget_id = ?', $widgetID)->where('user_id = ?', $this->id));
            if ($widget) {
                $widget->edit(array('placement' => $placement));
            } else {
                $params['widget_id'] = $widgetID;
                $params['user_id']   = $this->id;
                $params['placement'] = $placement;
                UserWidget::create($params);
            }
        }
    }

    public function getExFavoritePages() {
        $pages = Main::select()
                ->from(array('EFP' => 'ex_favorite_pages'), '')
                ->join(array('PA' => 'page'), 'EFP.page_id = PA.id', '')
                ->where('EFP.user_id = ?', $this->id)
                ->columns(array('PA.id', 'PA.title'))
                ->query()->fetchAll();
        return $pages;
    }

    public function getUserFavoritedItems($searchParams = array()) {
        $items =  Main::select()
                    ->from(array('UF' => 'user_favorite'))
                    ->join(array('PO' => 'post'), 'UF.post_id = PO.id', '')
                    ->join(array('UI' => 'user_info'), 'UI.user_id = PO.user_id', '')
                    ->columns(array('PO.id as post_id', 'PO.text as post_text', 'PO.date', 'PO.title', 'UI.first_name', 'UI.last_name'))
                    ->where('UF.user_id = ?', $this->id);
        $searchType   = Utils::arrayFetch($searchParams, 'search_type', false);
        if ($searchType == 'post') {
            $sort    = Utils::arrayFetch($searchParams, 'sort', false);
            $filter  = Utils::arrayFetch($searchParams, 'filter', false);
            if ($sort) {
                $items->order(array($sort));
            }
            if ($filter) {
                $items->where('PO.text LIKE ?', '%' . $filter . '%');
                $items->orWhere('PO.title LIKE ?', '%' . $filter . '%');
                $items->orWhere('UI.first_name LIKE ?', '%' . $filter . '%');
                $items->orWhere('UI.last_name LIKE ?', '%' . $filter . '%');
            }
        }

        $items = $items->query()->fetchAll();

        $return            = array();
        $return['post']    = array();
        $return['comment'] = array();
        foreach ($items as $oneItem) {
            $return['post'][$oneItem['post_id']] = $oneItem;
        }
        $items =  Main::select()
                    ->from(array('UF' => 'user_favorite'))
                    ->join(array('CO' => 'comment'), 'UF.comment_id = CO.id', '')
                    ->join(array('UI' => 'user_info'), 'UI.user_id = CO.commenter_id', '')
                    ->columns(array('CO.id as comment_id', 'CO.text as comment_text'));
        if ($searchType == 'comment') {
            $sort    = Utils::arrayFetch($searchParams, 'sort', false);
            $filter  = Utils::arrayFetch($searchParams, 'filter', false);
            if ($sort) {
                $items->order(array($sort));
            }
            if ($filter) {
                $items->where('CO.text LIKE ?', '%' . $filter . '%');
                $items->orWhere('UI.first_name LIKE ?', '%' . $filter . '%');
                $items->orWhere('UI.last_name LIKE ?', '%' . $filter . '%');
            }
        }
        $items = $items->where('UF.user_id = ?', $this->id)
                        ->query()->fetchAll();
        foreach ($items as $oneItem) {
            $return['comment'][$oneItem['comment_id']] = $oneItem;
        }
        return $return;
    }

    public function getNotifications() {
        $notifications =  Main::select()
                            ->from('notification')
                            ->where('user_id = ?', $this->id)
                            ->where('status = ?', Notification::STATUS_NEW)
                            ->order('id DESC')
                            ->query()->fetchAll();
        $count = count($notifications);
        $notificationsOld = array();
        if ($count < self::MAX_OLD_NOTIFICATIONS) {
            $diff = $count - self::MAX_OLD_NOTIFICATIONS;
            $notificationsOld =  Main::select()
                                ->from('notification')
                                ->where('user_id = ?', $this->id)
                                ->where('status = ?', Notification::STATUS_SEEN)
                                ->limit($diff)
                                ->order('id DESC')
                                ->query()->fetchAll();
        }
        return array_merge($notifications, $notificationsOld);
    }

    public function hasNewNotifications() {
        return Main::select()
                ->from('notification')
                ->where('user_id = ?', $this->id)
                ->where('status = ?', Notification::STATUS_NEW)
                ->columns(array('COUNT(id)'))
                ->query()->fetchColumn();
    }

    public function markAllNotificationsAsSeen() {
        Main::execQuery('UPDATE notification SET status = "SEEN" WHERE user_id = ? AND type != ?', array($this->id, Notification::TYPE_FRIEND_REQUEST));
    }

    public function sendFriendRequest($userID) {
        $receiver    = Main::buildObject('User', $userID);
        $alreadySent = $receiver->isFriendRequestSent($this->id);
        if (!$alreadySent) {
            $data = array(
                'user_id'     => $userID,
                'notifier_id' => $this->id,
                'text'        => ' ' . self::$translate->_('sent you a friend request'),
                'type'        => Notification::TYPE_FRIEND_REQUEST,
            );
            return Notification::create($data);
        } else {
            return false;
        }
    }

    public function isFriendRequestSent($userID) {
        return Main::select()
            ->from('notification')
            ->where('notifier_id = ?', $this->id)
            ->where('user_id = ?', $userID)
            ->where('status = ?', Notification::STATUS_NEW)
            ->where('type = ?', Notification::TYPE_FRIEND_REQUEST)
            ->columns(array('COUNT(id)'))
            ->query()->fetchColumn();
    }

    public function isFriendRequestReceived($userID) {
        return Main::select()
            ->from('notification')
            ->where('user_id = ?', $this->id)
            ->where('notifier_id = ?', $userID)
            ->where('status = ?', Notification::STATUS_NEW)
            ->where('type = ?', Notification::TYPE_FRIEND_REQUEST)
            ->columns(array('COUNT(id)'))
            ->query()->fetchColumn();
    }

    public function areFriends($userID) {
        return Main::select()
                ->from('user_user')
                ->where('user_id = ' . $this->id . ' AND friend_id = ' . $userID)
                ->orWhere('user_id = ' . $userID . ' AND friend_id = ' . $this->id)
                ->where('status = ?', self::FRIEND_STATUS_ACCEPTED)
                ->columns(array('COUNT(id)'))
                ->query()->fetchColumn();
    }

    public function withdrawFriendRequest($userID) {
        $areFriends = $this->areFriends($userID);
        if (!$areFriends) {
            Main::execQuery("DELETE FROM notification WHERE user_id = ? AND notifier_id = ? AND status = ?", array(
                $userID, $this->id, Notification::STATUS_NEW
            ));
            return true;
        } else {
            return false;
        }
    }

    public static function makeButtonDecision($userWatching, $userWatched) {
        $data = array();
        $isFriendRequestSent     = $userWatching->isFriendRequestSent($userWatched->id);
        $isFriendRequestReceived = $userWatching->isFriendRequestReceived($userWatched->id);
        $areFriends              = $userWatching->areFriends($userWatched->id);


        $data['text']          = self::$translate->_('Send Friend Request');
        $data['current_state'] = 'send';
        if ($isFriendRequestSent) {
            $data['current_state'] = 'withdraw';
            $data['text']          = self::$translate->_('Withdraw Request');
        }
        if ($isFriendRequestReceived) {
            $data['current_state'] = 'accept';
            $data['text']          = self::$translate->_('Accept Request');
        }
        if ($areFriends) {
            $data['current_state'] = 'remove';
            $data['text']          = self::$translate->_('Remove Friend');
        }
        return $data;
    }

    public function acceptFriendRequest($userID) {
        $notifier = Main::buildObject('User', $userID);
        $friendRequestSent = $notifier->isFriendRequestSent($this->id);
        if ($friendRequestSent) {
            $notification = Main::fetchRow(Main::select('Notification')
                ->where('user_id = ?', $this->id)
                ->where('notifier_id = ?', $userID)
                ->where('status = ?', Notification::STATUS_NEW)
            );
            $notification->edit(array('status' => Notification::STATUS_SEEN));
            UserUser::create(array('user_id' => $this->id, 'friend_id' => $userID, 'status' => self::FRIEND_STATUS_ACCEPTED));
            $c = new ElephantConnect(array('userID' => $this->id));
            $c->notifyThatFriendRequestIsAccepted(array('toNotify' => $userID));

            return true;
        } else {
            return false;
        }
    }

    public function removeFriend($userID) {
        $areFriends = $this->areFriends($userID);
        if ($areFriends) {
            $userUser = Main::fetchRow(Main::select('UserUser')->where('user_id = ?', $this->id)->where('friend_id = ?', $userID));
            if (!$userUser) {
                $userUser = Main::fetchRow(Main::select('UserUser')->where('friend_id = ?', $this->id)->where('user_id = ?', $userID));
            }
            $userUser->delete();
            return true;
        } else {
            return false;
        }
    }

    public function changeBigLogo() {
        $return = false;
        $ext      = pathinfo($_FILES['big_logo']['name'], PATHINFO_EXTENSION);
        if (in_array($ext, array('jpg, gif, png'))) {
            $userInfo = $this->getUserInfo();
            $fileName = Utils::uploadFile('big_logo', UserInfo::LOGOS_IMAGES_FOLDER, $userInfo->id);
            if ($fileName) {
                $userInfo->edit(array('big_logo' => $fileName));
                $return = true;
            }
        }
        return $return;
    }

    public function getOtherFriendLists($type = false) {
        $friendLists = Main::select()
        ->from(array('FL' => 'friend_list'), '')
        ->join(array('UFL' => 'user_friend_list'), 'FL.id = UFL.friend_list_id', '')
        ->join(array('UI' => 'user_info'), 'UI.user_id = UFL.friend_id', '')
        ->columns(array('FL.title', 'CONCAT(UI.first_name, " ", UI.last_name) as name',
                        'UI.user_id as id', 'UI.avatar', 'FL.is_system', 'FL.id as list_id'));
        
        if ($type) {
            $friendLists->where('FL.title = ?', $type)
                        ->where('FL.user_id = ?', $this->id);
        }
        $friendLists = $friendLists->query()->fetchAll();
        
        $return = array();
        $count = 0;
        foreach ($friendLists as $oneFriendList) {
            $title = FriendList::getTranslated($oneFriendList['title']);
            $return[$title][$count]['name']         = $oneFriendList['name'];
            $return[$title][$count]['id']           = $oneFriendList['id'];
            $return[$title][$count]['avatar']       = $oneFriendList['avatar'];
            $return[$title]['options']['list_id']   = $oneFriendList['list_id'];
            $return[$title]['options']['is_system'] = $oneFriendList['is_system'];
            $count++;
        }
        $friendLists = Main::select()
            ->from(array('FL' => 'friend_list'), '')
            ->where('FL.user_id = ' . $this->id . ' OR FL.is_system = 1')
            ->columns(array('FL.title', 'FL.is_system', 'FL.id as list_id'))
            ->query()->fetchAll();
        foreach ($friendLists as $value) {
            $title = FriendList::getTranslated($value['title']);
            $found = false;
            foreach ($return as $listTitle => $v) {
                if ($listTitle == $title) {
                    $found = true;
                }
            }
            if (!$found) {
                $return[$title] = array(
                    'options' => array(
                        'list_id'   => $value['list_id'],
                        'is_system' => $value['is_system'],
                    )
                );
            }
        }
        return $return;
    }
}