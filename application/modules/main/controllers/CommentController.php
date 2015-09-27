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
            $redirectURL = 'galery/galery-image?galeryID=1';
        }
        $this->_redirect($redirectURL);
    }

    public function likeOrUnlikeCommentAction() {
        $comment = $this->comment->likeOrUnlike($this->user->id);
        $this->_helper->json(array('message' => $comment->likes . ' ' . $this->translate->_('Likes')));
        $this->_helper->json(array('message' => 'test'));
    }

    public function loadMoreCommentsAction() {
        $this->view->comments = $this->comment->loadNextComments($this->user->id);
    }
}