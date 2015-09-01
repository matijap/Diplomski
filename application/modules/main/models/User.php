<?php
require_once 'User/Row.php';

class User extends User_Row
{

    const MAX_LINK_DURATION = 600;

    public function generateToken() {
        $string = $this->email . '_' . time();
        return hash('sha256', $string);
    }

    public function sendConfirmationEmail() {
        $subject = 'Hello from SPORTALIZE!';
        $text    = "Hey there, you have registered on our website. To complete the registration, please click";
        return $this->sendClientMailWithToken($subject, $text);
    }

    public function sendRegisterRenewToken() {
        $subject = 'Hello from SPORTALIZE!';
        $text    = "Hey there, you have requested new token for activation of your account. Please follow this link";
        return $this->sendClientMailWithToken($subject, $text);
    }

    public function sendClientMailWithToken($subject, $text) {
        $token   = $this->generateToken();
        $obj     = $this->edit(array('token' => $token, 'token_time_generation' => time()));
        $link    = APP_URL . '/login/index/activate?token=' . $obj->token;
        $text   .= ' ' . $link;
        return SportalizeMail::sendMail($obj, $subject, $text);
    }

    public static function getUserByToken($token) {
        return Main::fetchRow(Main::select('User')->where('token = ?', $token));
    }

    public static function getUserByEmail($email) {
        return Main::fetchRow(Main::select('User')->where('email = ?', $email));
    }

    public function activate() {
        $return = array('status' => 0, 'message' => '');
        if ($this->activated) {
            $return['message'] = 'User is already activated';
        } else {
            $tokenExpired = $this->hasTokenExpired();
            if ($tokenExpired) {
                $return['message'] = 'Link expired. Please click <a href="' . APP_URL . '/login/index/get-new-token?userID=' . $this->id . '&trigger=register">here</a> to resend confirmation email with new token.';
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
}