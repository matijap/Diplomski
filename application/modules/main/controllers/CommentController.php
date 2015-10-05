<?php

require_once 'BaseController.php';

class CommentController extends Main_BaseController
{
    public $comment = false;

    public function preDispatch() {
        parent::preDispatch();

        if (isset($this->params['commentID'])) {
            $this->comment = Main::buildObject('Comment', $this->params['commentID']);
        }
        
    }

    public function submitCommentAction() {
        if (!isset($this->params['commented_post_id']) && !isset($this->params['commented_image_id'])) {
            $status              = self::NOTIFICATION_ERROR;
            $notificationMessage = $this->translate->_('Post or Image ID not set.');
        } else {
            $comment             = Comment::create($this->params);
            $status              = self::NOTIFICATION_SUCCESS;
            $notificationMessage = $this->translate->_('Comment posted.');
        }
        $this->setNotificationMessage($notificationMessage, $status);
        $redirectURL = '/';
        if (isset($this->params['page_id'])) {
            $redirectURL = 'page/index?pageID=' . $this->params['page_id'];
        }
        if (isset($this->params['commented_image_id'])) {
            $image = Main::buildObject('Image', $this->params['commented_image_id']);
            $redirectURL = 'galery/galery-image?galeryID=' . $image->galery_id;
        }
        $this->_redirect($redirectURL);
    }

    public function likeOrUnlikeCommentAction() {
        $return            = $this->comment->likeOrUnlike($this->user->id);
        $comment           = $return['comment'];
        unset($return['comment']);
        $return['message'] = $comment->likes . ' ' . $this->translate->_('Likes');
        $this->_helper->json($return);
    }

    public function loadMoreCommentsAction() {
        $this->view->comments = $this->comment->loadNextComments($this->user->id);
    }

    public function favoriteOrUnfavoriteCommentAction() {
        $message = $this->comment->favoriteOrUnfavoriteComment($this->user->id);
        $this->_helper->json(array('message' => $message));
    }
}