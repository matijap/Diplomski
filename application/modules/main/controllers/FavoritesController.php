<?php

require_once 'BaseController.php';

class FavoritesController extends Main_BaseController
{
    public function indexAction() {
        $this->view->favoritePages      = $this->user->getPageList();
        $this->view->exFavoritePages    = $this->user->getExFavoritePages();
        $searchType   = Utils::arrayFetch($this->params, 'search_type', false);
        $searchParams = array();
        if ($searchType) {
            $sort                        = Utils::arrayFetch($this->params, 'sort', false);
            $filter                      = Utils::arrayFetch($this->params, 'filter', false);
            $searchParams['search_type'] = $searchType;
            $searchParams['sort']        = $sort;
            $searchParams['filter']      = $filter;
        }
        $favorites                      = $this->user->getUserFavoritedItems($searchParams);

        $this->view->commentFavorites   = $favorites['comment'];
        $this->view->postFavorites      = $favorites['post'];
        $this->view->searchPostsForm    = new FavoritesSearchForm(array('type' => 'post', 'searchParams' => $searchParams));
        $this->view->searchCommentsForm = new FavoritesSearchForm(array('type' => 'comment', 'searchParams' => $searchParams));
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

    public function postOrCommentDetailedAction() {
        $postID               = Utils::arrayFetch($this->params, 'postID', false);
        $commentID            = Utils::arrayFetch($this->params, 'commentID', false);
        $this->view->viewOnly = Utils::arrayFetch($this->params, 'viewonly', false);
        if ($postID) {
            $this->view->post = Main::buildObject('Post', $postID);
        } else {
            $this->view->comment = Main::buildObject('Comment', $commentID);
        }

        if ($this->request->isPost()) {
            if ($postID) {
                $this->view->post->favoriteOrUnfavoritePost($this->user->id);
                $this->setNotificationMessage($this->translate->_('Post removed from favorites'));
            } else {
                $this->view->comment->favoriteOrUnfavoriteComment($this->user->id);
                $this->setNotificationMessage($this->translate->_('Comment removed from favorites'));
            }
            $this->_helper->json(array('status' => 1, 'url' => APP_URL . '/favorites/index'));
        }
    }
}