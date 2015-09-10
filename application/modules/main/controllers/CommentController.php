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
        if (!isset($this->params['commented_post_id'])) {
            $status              = self::NOTIFICATION_ERROR;
            $notificationMessage = $this->translate->_('Post ID not set.');
        } else {
            $comment             = Comment::create($this->params);
            $status              = self::NOTIFICATION_SUCCESS;
            $notificationMessage = $this->translate->_('Comment posted.');
        }
        $this->setNotificationMessage($status, $notificationMessage);
        $this->_redirect('/');
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