<?php
require_once 'User/Row.php';

class User extends User_Row
{
    const MAX_LINK_DURATION = 600;

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

    public function getFriendList() {
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
        $filtered[] = $this->id;
        $allFriends = Main::select()
                    ->from(array('U' => 'user'), '')
                    ->where('id IN (?)', $filtered)
                    ->columns(array('U.id', 'U.first_name', 'U.last_name'))
                    ->query()->fetchAll();
        return $allFriends;
    }
}