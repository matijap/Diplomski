<?php

require_once 'BaseController.php';

class IndexController extends Main_BaseController
{
    public function indexAction() {
        $this->view->widgets = Widget::gatherAllWidgetsForUser($this->user->id);
        $this->view->posts   = $this->user->getFriendsAndPagePosts();
        $this->view->form    = new AddCommentForm();
    }
}