<?php

require_once 'BaseController.php';

class FavoritesController extends Main_BaseController
{
    public function indexAction() {
        $this->view->favoritePages   = $this->user->getPageList();
        $this->view->exFavoritePages = $this->user->getExFavoritePagesList();
        $likes                       = $this->user->getUserLikeList();
        $this->view->commentLikes    = array();
        $this->view->postLikes       = array();
        foreach ($likes as $oneLike) {
            if (!is_null($oneLike->comment_id)) {
                $this->view->commentLikes = $oneLike;
            } else {
                $this->view->postLikes    = $oneLike;
            }
        }
    }
}