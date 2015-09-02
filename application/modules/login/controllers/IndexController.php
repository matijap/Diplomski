<?php

require_once 'BaseController.php';

class Login_IndexController extends Login_BaseController
{
    public function indexAction() {
        $this->redirectToSignUpPage();
    }
    public function signInAction() {
        $this->view->form = $form = new SignInForm();
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $user  = User::getUserByEmail($this->params['email']);
                if ($user) {
                    $login = User::login($this->params['email'], $this->params['password'], true);
                    if ($login) {
                        $this->_redirect(APP_URL . '/');
                    } else {
                        $this->view->message = 'User not activated, or unknown email / password';
                    }    
                } else {
                    $this->view->message = 'Unknown email / password';
                }
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
                $this->_redirect(APP_URL . '/login/index/register?trigger=activate');
            }
        }
    }

    public function activateAction() {
        if (!isset($this->params['token'])) {
            $this->redirectToSignUpPage();
        }
        $token = $this->params['token'];
        $user  = User::getUserByToken($token);
        if ($user) {
            $activated = $user->activate();
            if ($activated['status']) {
                $login = $user->loginUser();
                if ($login) {
                    $this->_redirect(APP_URL . '/');
                }
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
                $this->redirectToSignUpPage();
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

    public function signOutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
        Zend_Session::destroy();
        $this->redirectToSignUpPage();
    }

    public function forgotPasswordAction() {
        $this->view->form = $form = new ForgotPasswordForm();
        $response         = $this->validateForm($form);
        if (isset($this->params['trigger'])) {
            $this->view->message = 'Email with recovery link sent. Please check your inbox.';
        }
        if ($response['isPost']) {
            if ($response['isValid']) {
                $user = User::getUserByEmail($this->params['email']);
                if ($user) {
                    $user->sendForgotPasswordToken();
                    $this->_redirect(APP_URL . '/login/index/forgot-password?trigger=activate');
                } else {
                    $this->view->message = 'Unknown email';
                }
            }
        }
    }

    public function resetPasswordAction() {
        if (!isset($this->params['token'])) {
            $this->redirectToSignUpPage();
        }
        $this->view->form = $form = new ResetPasswordForm();
        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $user = User::getUserByToken($this->params['token']);
                if ($user) {
                    $user         = $user->edit(array('password' => Utils::encrypt($this->params['password'])));
                    $tokenExpired = $user->hasTokenExpired();
                    if (!$tokenExpired) {
                        $login = $user->loginUser();
                        if ($login) {
                            $this->_redirect(APP_URL . '/');
                        } else {
                            $this->view->message = 'Unknown user';
                        }
                    } else {
                        $this->view->message = 'Token expired.';
                    }
                } else {
                    $this->view->message = 'Unknown token';
                }
            }
        }
    }
}