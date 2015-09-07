<?php

require_once 'BaseController.php';

class PostController extends Main_BaseController
{
    public function newPostAction() {
        $this->view->form = new NewPostForm();
    }
}