<?php

require_once 'BaseController.php';

class SearchController extends Main_BaseController
{
    public function indexAction() {
        $search = Utils::arrayFetch($this->params, 'global_search', '');
        if (empty($search)) {
            $this->view->message = $this->translate->_('No search paramaters specified');
        } else {
            $this->view->people = Search::findPeople($search, $this->params['exclude_id']);
            $this->view->pages  = Search::findPages($search, $this->params['exclude_id']);
        }
    }
}