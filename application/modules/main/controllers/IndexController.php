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
        $user                          = Main::buildObject('User', $this->params['userID']);
        $this->view->posts             = $user->getPostsByUser(1, $this->user->id);
        $this->view->form              = new AddCommentForm();
        $this->view->watcher           = $this->user;
        $this->view->watched           = $user;
        $this->view->bigLogoChangeForm = new BigLogoChangeForm();
    }

    public function likeOrUnlikePostAction() {
        $post    = Main::buildObject('Post', $this->params['postID']);
        $return  = $post->likeOrUnlikePost($this->params['userID']);
        $this->_helper->json($return);
    }

    public function favoriteOrUnfavoritePostAction() {
        $post    = Main::buildObject('Post', $this->params['postID']);
        $message = $post->favoriteOrUnfavoritePost($this->params['userID']);
        $this->_helper->json(array('message' => $message));
    }

    public function markAllNotificationsAction() {
        $user = Main::buildObject('User', $this->params['userID']);
        $user->markAllNotificationsAsSeen();
    }

    public function sendFriendRequestAction() {
        $user    = Main::buildObject('User', $this->params['userID']);
        $status  = $user->sendFriendRequest($this->params['requestSentTo']);
        $message = $status ? '' : $this->translate->_('This person has already sent friend request to you. Please refresh the page');
        $this->_helper->json(array('status' => $status, 'message' => $message));
    }

    public function withdrawFriendRequestAction() {
        $user    = Main::buildObject('User', $this->params['userID']);
        $status  = $user->withdrawFriendRequest($this->params['requestSentTo']);
        $message = $status ? '' : $this->translate->_('This person has already accepted your friend request. Please refresh the page');
        $this->_helper->json(array('status' => $status, 'message' => $message));
    }

    public function acceptFriendRequestAction() {
        $user    = Main::buildObject('User', $this->params['userID']);
        $status  = $user->acceptFriendRequest($this->params['requestSentTo']);
        $message = $status ? '' : $this->translate->_('This person has withdrawn friend request. Please refresh the page');
        $this->_helper->json(array('status' => $status, 'message' => $message));
    }

    public function removeFriendAction() {
        $user    = Main::buildObject('User', $this->params['userID']);
        if ($this->params['requestSentTo'] != User::SPORTALIZE_USER_ID) {
            $status  = $user->removeFriend($this->params['requestSentTo']);
            $message = $status ? '' : $this->translate->_('This person has already removed you from the friends. Please refresh the page');
        } else {
            $status  = false;
            $message = $this->translate->_('Can not remove system admin from friends');
        }
        $this->_helper->json(array('status' => $status, 'message' => $message));
    }

    public function getHtmlForNotificationAction() {
        $this->view->type      = $this->params['type'];
        $this->view->postID    = Utils::arrayFetch($this->params, 'postID', false);
        $this->view->commentID = Utils::arrayFetch($this->params, 'commentID', false);
        $this->view->notifier  = Main::buildObject('User', $this->params['notifierID']);
    }

    public function forwardPostOrCommentAction() {
        $postOrComment = Utils::arrayFetch($this->params, 'postOrComment');
        $objectID      = Utils::arrayFetch($this->params, 'objectID');
        $userID        = Utils::arrayFetch($this->params, 'userID');
        $strings       = array();
        if ($postOrComment == 'post') {
            $strings[] = $this->translate->_('Post');
            $post      = Main::buildObject('Post', $objectID);
            $post->forward($userID);
        } else {
            $strings[] = $this->translate->_('Comment');
            $comment   = Main::buildObject('Comment', $objectID);
            $comment->forward($userID);
        }
        $strings[] = $this->translate->_(' forwarded successfully');
        $this->setNotificationMessage(Utils::mergeStrings($strings));
    }

    public function changeBigLogoAction() {
        $status = $this->user->changeBigLogo();
        $message = $status ? $this->translate->_('Big logo changed successfully') : $this->translate->_('Unable to change logo');
        $status  = $status ? Sportalize_Controller_Action::NOTIFICATION_SUCCESS : Sportalize_Controller_Action::NOTIFICATION_ERROR;
        $this->setNotificationMessage($message, $status);
        $this->_redirect(APP_URL . '/index/profile?userID=' . $this->user->id);
    }

    public function friendsAction() {
        $this->view->allFriends = $friends = Search::findPeople('', $this->user->id, $this->user->id);
    }
}