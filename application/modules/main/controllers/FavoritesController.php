<?php

require_once 'BaseController.php';

class FavoritesController extends Main_BaseController
{
    public function indexAction() {
        $this->view->favoritePages   = $this->user->getPageList();
        $this->view->exFavoritePages = $this->user->getExFavoritePages();
        
        $favorites                   = $this->user->getUserFavoritedItems();

        $this->view->commentFavorites = $favorites['comment'];
        fb($favorites['comment']);
        $this->view->postFavorites    = $favorites['post'];
    }

    public function likeOrUnlikePageAction() {
        $page   = Main::buildObject('Page', $this->params['pageID']);
        $return = $page->likeOrUnlike($this->user->id);
        $this->_helper->json($return);
    }

    public function showOrHideInFavoritePagesWidgetAction() {
        $page   = Main::buildObject('Page', $this->params['pageID']);
        $return = $page->showOrHideInFavoritePagesWidget($this->user->id);
        $this->_helper->json(array('message' => $return));
    }
}