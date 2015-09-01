<?php

require_once 'BaseController.php';

class Login_IndexController extends Login_BaseController
{
    public function indexAction() {
        $this->_redirect(APP_URL . '/login/index/sign-up');
    }
    public function signUpAction() {
        $this->view->form = $form = new SignInForm();
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $this->_redirect(APP_URL . '/');
            }
        }
    }

    public function registerAction() {
        $this->view->form = $form = new RegisterForm();
        $response         = $this->validateForm($form);

        if ($response['isPost']) {
            if ($response['isValid']) {
                $user = User::create($this->params);
                $user->sendConfirmationEmail();
                $this->_redirect(APP_URL . '/login/index/register?action=activate');
            }
        }
    }

    public function activateAction() {
        if (!isset($this->params['token'])) {
            $this->_redirect(APP_URL . '/login/index/sign-up');
        }
        $token = $this->params['token'];
        $user  = User::getUserByToken($token);
        if ($user) {
            $activated = $user->activate();
            if ($activated['status']) {
                $this->_redirect(APP_URL . '/');     
            } else {
                $this->view->message = $activated['message'];
            }
        } else {
            $this->view->message = 'Invalid Token.';
        }
    }

    public function getNewTokenAction() {
        if (isset($this->params['email'])) {
            $user = User::getUserByEmail($this->params['email']);
        } else {
            if (!isset($this->params['userID']) && !isset($this->params['trigger'])) {
                $this->_redirect(APP_URL . '/login/index/sign-up');
            }
            $userID = $this->params['userID'];
            $user   = Main::buildObject('User', $userID);
        }
        
        $action = $this->params['trigger'];
        if ($user) {
            if ($action == 'register') {
                $this->view->status = $user->sendRegisterRenewToken();
            }
        }
    }
}