<?php

class SportalizeMail extends Zend_Mail 
{
    const PASSWORD = 'palacinke753951';
    const USERNAME = 'matijap87';
    const EMAIL    = 'matijap87@gmail.com';
    const NAME     = 'SPORTALIZE';

    public static function sendMail($user, $subject, $text) {
        $smtpHost = 'smtp.gmail.com';
        $smtpConf = array(
            'auth'     => 'login',
            'ssl'      => 'ssl',
            'port'     => '465',
            'username' => self::USERNAME,
            'password' => self::PASSWORD,
        );
        $transport = new Zend_Mail_Transport_Smtp($smtpHost, $smtpConf);

        //Create email
        $mail = new Zend_Mail();
        $mail->setFrom(self::EMAIL, self::NAME);
        $mail->addTo($user->email, '');
        $mail->setSubject($subject);
        $mail->setBodyText($text);
        try {
            $mail->send($transport);
            return true;
        }
        catch (Exception $e) {
            fb($e->getMessage());
        }
        return false;
    }
}