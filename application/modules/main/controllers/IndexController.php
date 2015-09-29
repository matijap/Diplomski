<?php

require_once 'BaseController.php';

class IndexController extends Main_BaseController
{
    public function indexAction() {
        $this->view->widgets = Widget::gatherAllWidgetsForUser($this->user->id);
        $this->view->posts   = $this->user->getFriendsAndPagePosts();
        $this->view->form    = new AddCommentForm();
    }

    public function profileAction() {
        if (!isset($this->params['userID'])) {
            $this->setNotificationMessage($this->translate->_('User ID not sent.'), Sportalize_Controller_Action::NOTIFICATION_ERROR);
            $this->_redirect('/');
        }
        $user              = Main::buildObject('User', $this->params['userID']);
        $this->view->posts = $user->getPostsByUser();
        $this->view->form  = new AddCommentForm();
    }

    public function likeOrUnlikePostAction() {
        $post    = Main::buildObject('Post', $this->params['postID']);
        $message = $post->likeOrUnlikePost($this->params['userID']);
        $this->_helper->json(array('message' => $message));
    }

    public function favoriteOrUnfavoritePostAction() {
        $post    = Main::buildObject('Post', $this->params['postID']);
        $message = $post->favoriteOrUnfavoritePost($this->params['userID']);
        $this->_helper->json(array('message' => $message));
    }
}