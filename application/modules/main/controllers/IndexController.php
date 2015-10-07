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
        $this->view->form       = $form = new CreateFriendListForm();
        $this->view->allFriends = $friends = Search::findPeople('', $this->user->id, $this->user->id);
        $this->view->otherLists = $this->user->getOtherFriendLists();
        $response               = $this->validateForm($form);
        if ($response['isPost']) {
            $list = Main::fetchRow(Main::select('FriendList')->where('LOWER(title) = ?', strtolower($this->params['title'])));
            if ($response['isValid'] && !$list) {
                FriendList::create(array(
                    'title'   => $this->params['title'],
                    'user_id' => $this->user->id, 
                ));
                $this->setNotificationMessage($this->translate->_('Friend list created successfully'));
                $this->_redirect(APP_URL . '/index/friends');
            } else {
                $this->setNotificationMessage($this->translate->_('Friend list name invalid. Either empty provided, or already exists'),
                    Sportalize_Controller_Action::NOTIFICATION_ERROR);
            }
        }
    }

    public function deleteFriendListAction() {
        $this->view->form = $form = new DeleteFriendListForm(array('listID' => $this->params['listID']));

        $response         = $this->validateForm($form);
        if ($response['isPost']) {
            if ($response['isValid']) {
                $friendList = Main::buildObject('FriendList', $this->params['listID']);
                $friendList->delete();
                $this->setNotificationMessage($this->translate->_('Friend list deleted successfully'));
                $this->_helper->json(array('status' => 1, 'url' => APP_URL . '/index/friends'));
            }
        }
    }

    public function addFriendsIntoListAction() {
        $users   = Utils::arrayFetch($this->params, 'users', array());
        $status  = '';
        $message = '';
        $url     = '';
        if ($users) {
            foreach ($users as $oneUser) {
                $ufl = UserFriendList::create(array(
                    'friend_id'      => $oneUser,
                    'friend_list_id' => $this->params['friendListID'],
                    'list_owner_id'  => $this->user->id,
                ));
                $ufl->save();
            }
            $status = 1;
            $this->setNotificationMessage('Friends added to list successfully');
            $url     = APP_URL . '/index/friends';
        } else {
            $status  = 0;
            $message = '';
        }
        $this->_helper->json(array('status'  => $status,
                                   'message' => $message,
                                   'url'     => $url
                                   ));
    }

    public function deleteFriendFromListAction() {
        $fl = Main::fetchRow(Main::select('UserFriendList')
            ->where('friend_list_id = ?', $this->params['listID'])
            ->where('friend_id = ?', $this->params['friendID'])
            );
        $fl->delete();
        $this->view->friend = Main::buildObject('User', $this->params['friendID']);
    }
}