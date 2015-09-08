<?php

require_once 'BaseController.php';

class PostController extends Main_BaseController
{
    public function newPostAction() {
        $this->view->form = $form = new NewPostForm();

        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $post = Post::create($this->params);
                $this->setNotificationMessage(self::NOTIFICATION_SUCCESS, $this->translate->_('Posted successfully'));
                $this->_helper->json(array('status'  => 1,
                                           'url'     => APP_URL . '/'));
                
            }
        }
    }
}